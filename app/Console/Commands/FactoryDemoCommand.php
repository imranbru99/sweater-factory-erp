<?php

namespace App\Console\Commands;

use Database\Seeders\DemoSeeder;
use Database\Seeders\FoundationSeeder;
use Illuminate\Console\Command;

class FactoryDemoCommand extends Command
{
    protected $signature = 'factory:demo';

    protected $description = 'Load the Gazipur demo: buyers, styles, machines and staff logins';

    public function handle(): int
    {
        $foundation = new FoundationSeeder;
        $foundation->setContainer($this->laravel);
        $foundation->run();

        $demo = new DemoSeeder;
        $demo->setContainer($this->laravel);
        $demo->run();

        $this->info('Demo factory loaded.');
        $this->warn('Staff sign in with their role address at sweaterfactory.test. The local password is "password".');

        return self::SUCCESS;
    }
}
