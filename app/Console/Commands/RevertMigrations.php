<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RevertMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:revert-migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revert all migrations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Reverting all migrations...');

        // Run the migration rollback command
        $this->call('migrate:rollback', [
            '--step' => 1000, // Rollback all migrations
            '--force' => true,
        ]);

        $this->info('All migrations have been reverted.');
    }
}
