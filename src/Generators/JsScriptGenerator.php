<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Str;

class JsScriptGenerator
{
    public function generate($name, $columns = [])
    {
        $model = ucfirst($name);
        $modelLower = Str::lower($name);
        $plural = Str::plural($modelLower);

        $jsPath = public_path("assets/js/{$modelLower}.js");

        if (file_exists($jsPath)) {
            return [
                'status' => 'exists',
                'message' => "JavaScript for {$name} already exists at:\n" . $jsPath,
            ];
        }

        // Create directory if not exists
        if (!is_dir(dirname($jsPath))) {
            mkdir(dirname($jsPath), 0755, true);
        }

        /*
        |--------------------------------------------------------------------------
        | RENDER COLUMNS
        |--------------------------------------------------------------------------
        */
        $renderColumns = "";

        foreach ($columns as $col) {

            if ($col['name'] === 'id') {
                continue;
            }

            if ($col['name'] === 'created_at') {
                continue; // handled separately
            }

            $renderColumns .= "<td>\${{$modelLower}.{$col['name']} ?? ''}</td>";
        }

        /*
        |--------------------------------------------------------------------------
        | EDIT FIELDS (DYNAMIC)
        |--------------------------------------------------------------------------
        */
        $editFields = "";

        foreach ($columns as $col) {

            if (in_array($col['name'], ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $field = $col['name'];

            // Checkbox (boolean)
            if (Str::contains($col['type'], ['boolean'])) {

                $editFields .= "
                    if(res.{$modelLower}.{$field}) {
                        $('#edit-{$field}').prop('checked', true);
                    } else {
                        $('#edit-{$field}').prop('checked', false);
                    }
                ";

            } else {

                $editFields .= "
                    $('#edit-{$field}').val(res.{$modelLower}.{$field} ?? '');
                ";
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FILE CONTENT
        |--------------------------------------------------------------------------
        */
        $content = "
const {$model} = {

    init() {
        this.create();
        this.update();
        this.events();
    },

    events() {
        $(document).on('click', '.delete-{$modelLower}', (e) => this.delete(e));
        $(document).on('click', '.edit-{$modelLower}', (e) => this.edit(e));
    },

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    create() {

        $('#create-{$modelLower}-form').validate({

            rules: {
                {$modelLower}: { required: true }
            },

            submitHandler: (form) => {

                AjaxHelper.request({
                    url: '/{$plural}',
                    method: 'POST',
                    data: $(form).serialize(),
                    loader: '#loader',
                    button: '#save-{$modelLower}'
                })

                .then(res => {

                    UI.toast(res.message);

                    $('#create-{$modelLower}-modal').modal('hide');

                    form.reset();
                    $(form).validate().resetForm();

                    {$model}.render(res.{$plural});

                });

            }

        });

    },

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    update() {

        $('#edit-{$modelLower}-form').validate({

            rules: {
                {$modelLower}: { required: true }
            },

            submitHandler: (form) => {

                const id = $('#edit-{$modelLower}-id').val();

                AjaxHelper.request({
                    url: '/{$plural}/' + id,
                    method: 'PUT',
                    data: $(form).serialize(),
                    loader: '#edit-loader',
                    button: '#edit-save-{$modelLower}'
                })

                .then(res => {

                    UI.toast(res.message);

                    $('#edit-{$modelLower}-modal').modal('hide');

                    form.reset();
                    $(form).validate().resetForm();

                    {$model}.render(res.{$plural});

                });

            }

        });

    },

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    delete(event) {

        event.preventDefault();

        const id = $(event.currentTarget).data('id');

        UI.confirm().then(result => {

            if (!result.isConfirmed) return;

            AjaxHelper.request({
                url: '/{$plural}/' + id,
                method: 'DELETE'
            })

            .then(res => {

                UI.toast(res.message);

                {$model}.render(res.{$plural});

            });

        });

    },

    /*
    |--------------------------------------------------------------------------
    | EDIT (DYNAMIC)
    |--------------------------------------------------------------------------
    */
    edit(event) {

        event.preventDefault();

        const id = $(event.currentTarget).data('id');

        AjaxHelper.request({
            url: '/{$plural}/' + id + '/edit',
            method: 'GET'
        })

        .then(res => {

            // Reset form first
            $('#edit-{$modelLower}-form')[0].reset();

            {$editFields}

            $('#edit-{$modelLower}-id').val(id);

            $('#edit-{$modelLower}-modal').modal('show');

        });

    },

    /*
    |--------------------------------------------------------------------------
    | RENDER TABLE
    |--------------------------------------------------------------------------
    */
    render({$plural}) {

        const tbody = $('#{$modelLower}-table-body');

        tbody.empty();

        let startIndex = {$plural}.from || 0;

        {$plural}.data.forEach(({$modelLower}, index) => {

            tbody.append(\`
                <tr>
                    <td>\${startIndex + index}</td>
                    {$renderColumns}
                    <td>\${{$modelLower}.created_at_formatted ?? ''}</td>
                    <td>
                        <div class=\"d-flex justify-content-evenly\">

                            <a href=\"#\"
                               class=\"delete-{$modelLower}\"
                               data-id=\"\${{$modelLower}.encrypted_id}\">
                               <i class=\"fa-solid fa-trash text-danger\"></i>
                            </a>

                            <a href=\"#\"
                               class=\"edit-{$modelLower}\"
                               data-id=\"\${{$modelLower}.encrypted_id}\">
                               <i class=\"fa-solid fa-pen-to-square text-success\"></i>
                            </a>

                        </div>
                    </td>
                </tr>
            \`);

        });

    }

};

$(document).ready(function () {
    {$model}.init();
});
";

        file_put_contents($jsPath, $content);

        return [
            'status' => 'created',
            'message' => "JavaScript for {$name} created at:\n" . $jsPath,
        ];
    }
}