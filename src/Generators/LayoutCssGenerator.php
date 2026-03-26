<?php

namespace Fida\Crud\Generators;

class LayoutCssGenerator
{
    public function generate()
    {
        $layoutPath = resource_path('views/layouts');

        if (!is_dir($layoutPath)) {
            mkdir($layoutPath, 0755, true);
        }

        $file = $layoutPath . '/css.blade.php';

        if (!file_exists($file)) {

            $content = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">';

            file_put_contents($file, $content);

            return [
                'status' => 'created',
                'message' => "pilot.php created at: " . $file,
            ];

        } else {

            return [
                'status' => 'exists',
                'message' => "pilot.php already exists at: " . $file,
            ];

        }
    }
}