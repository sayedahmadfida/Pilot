<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MigrationGenerator
{
    public function generate($name)
    {
        
        $nameLower = Str::lower($name);
        $plural = Str::plural($nameLower);

        $timestamp = date('Y_m_d_His');

        $migrationName = "{$timestamp}_create_{$plural}_table.php";

        $migrationPath = database_path("migrations/{$migrationName}");


        $existingFiles = File::files(database_path('migrations'));
        foreach ($existingFiles as $file) {
            if (Str::contains($file->getFilename(), "create_{$plural}_table.php")) {
                return [
                    'status' => 'exists',
                    'message' => "Migration already exists:\n" . $file->getPathname(),
                ];
            }
        }


        $content = "<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{$plural}', function (Blueprint \$table) {
            \$table->id();
            
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$plural}');
    }
};
";

        file_put_contents($migrationPath, $content);
        return [
            'status' => 'created',
            'message' => "Migration for {$name} created at:\n".$migrationPath,
        ];
    }
}