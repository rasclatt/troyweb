<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FirstRunSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:first-run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run first-run setup tasks';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Rolling back migrations...');
        Artisan::call('migrate:rollback', ['--step' => 1000]);

        $this->info('Running migrations...');
        Artisan::call('migrate');

        foreach([
            'Roles',
            'SiteSettings',
            'Books',
            'Users',
        ] as $seed) {
            $this->info('Seeding database...');
            Artisan::call('db:seed', ['--class' => $seed]);
        }
        $this->info('Clearing cache...');
        Artisan::call('cache:clear');

        $this->info('First-run setup completed successfully.');
        return 0;
    }
}