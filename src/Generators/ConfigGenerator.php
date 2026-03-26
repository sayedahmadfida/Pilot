<?php

namespace Fida\Crud\Generators;
class ConfigGenerator
{
    public function generate()
    {
        $configPath = config_path('pilot.php');

        if (!file_exists($configPath)) {

            $content = "<?php

return [
    'views_path' => 'resources/views',
    'js_path' => 'public/js',
];
";

            file_put_contents($configPath, $content);

            return [
                'status' => 'created',
                'message' => "pilot.php config file created at:\n".$configPath,
            ];

        } else {

            return [
                'status' => 'exists',
                'message' => "pilot.php config file already exists at:\n".$configPath,
            ];

        }
    }
}