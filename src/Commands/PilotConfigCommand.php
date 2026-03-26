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

        $this->info((new ConfigGenerator())->generate());
        $this->info((new LayoutCssGenerator())->generate());
        $this->info((new AppGenerator())->generate());
        $this->info((new SidebarGenerator())->generate());
        $this->info((new HeadGenerator())->generate());
        $this->info((new HeaderGenerator())->generate());
        $this->info((new ScriptGenerator())->generate());
        $this->info((new GeneralJsGenerator())->generate());
        $this->info((new FooterGenerator())->generate());

        $this->info('Pilot setup completed.');
    }
}