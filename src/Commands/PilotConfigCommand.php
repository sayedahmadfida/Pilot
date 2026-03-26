<?php

namespace Fida\Crud\Commands;

use AppendIterator;
use Fida\Crud\Generators\AppGenerator;
use Fida\Crud\Generators\ConfigGenerator;
use Fida\Crud\Generators\FooterGenerator;
use Fida\Crud\Generators\GeneralJsGenerator;
use Fida\Crud\Generators\HeaderGenerator;
use Fida\Crud\Generators\HeadGenerator;
use Fida\Crud\Generators\LayoutAppGenerator;
use Fida\Crud\Generators\LayoutCssGenerator;
use Fida\Crud\Generators\ScriptGenerator;
use Fida\Crud\Generators\SidebarGenerator;
use Illuminate\Console\Command;

class PilotConfigCommand extends Command
{
    protected $signature = 'pilot:config';

    protected $description = 'Setup Pilot configuration';

    public function handle()
    {
        $this->info('Pilot configuration started...');

        $this->outputResult((new ConfigGenerator())->generate());
        // $this->outputResult((new LayoutCssGenerator())->generate());
        $this->outputResult((new AppGenerator())->generate());
        $this->outputResult((new SidebarGenerator())->generate());
        $this->outputResult((new HeadGenerator())->generate());
        $this->outputResult((new HeaderGenerator())->generate());
        $this->outputResult((new ScriptGenerator())->generate());
        $this->outputResult((new GeneralJsGenerator())->generate());
        // $this->outputResult((new FooterGenerator())->generate());

        $this->info('Pilot setup completed.');
    }

    protected function outputResult($result)
    {
        if ($result['status'] === 'exists') {
            $this->info($result['message']); 
        } else {
            $this->line($result['message']); 
        }
    }
}
