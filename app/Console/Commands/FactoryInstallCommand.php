<?php

namespace App\Console\Commands;

use Database\Seeders\FoundationSeeder;
use Illuminate\Console\Command;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class FactoryInstallCommand extends Command
{
    protected $signature = 'factory:install {--company=} {--company-bn=} {--admin-email=} {--admin-password=}';

    protected $description = 'Create the company, units, roles and the first administrator';

    public function handle(): int
    {
        if (app()->environment('production') && ! $this->option('admin-password') && ! $this->input->isInteractive()) {
            $this->error('Pass --admin-password when installing without a prompt.');

            return self::FAILURE;
        }

        $company = $this->option('company') ?: ($this->input->isInteractive()
            ? text('Company name', default: (string) config('factory.company.name'), required: true)
            : (string) config('factory.company.name'));
        $companyBn = $this->option('company-bn') ?: ($this->input->isInteractive()
            ? text('Company name in Bangla', default: (string) config('factory.company.name_bn'), required: true)
            : (string) config('factory.company.name_bn'));
        $email = $this->option('admin-email') ?: ($this->input->isInteractive()
            ? text('Administrator email', default: 'admin@sweaterfactory.test', required: true)
            : 'admin@sweaterfactory.test');
        $adminPassword = $this->option('admin-password') ?: ($this->input->isInteractive()
            ? password('Administrator password', required: true)
            : 'password');

        $seeder = new FoundationSeeder;
        $seeder->companyName = $company;
        $seeder->companyNameBn = $companyBn;
        $seeder->adminEmail = $email;
        $seeder->adminPassword = $adminPassword;
        $seeder->setContainer($this->laravel);
        $seeder->run();

        $this->info('Factory foundation is installed.');
        $this->line('Administrator: '.$email);
        if (! $this->input->isInteractive() && ! $this->option('admin-password')) {
            $this->warn('The local administrator password is "password". Change it before this system leaves the office network.');
        }

        return self::SUCCESS;
    }
}
