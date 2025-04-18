<?php

namespace App\Console\Commands;

use App\Core\UserRole;
use App\Models\User;
use Illuminate\Console\Command;

class AddAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add admin priviledges to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        try {
            $user = User::firstWhere('email', $email);
            if ($user->isAdmin()) {
                $this->info("'{$email}' is already an admin");

                return;
            }
            $user->role = UserRole::ADMIN;
            $user->save();
        } catch (\Exception) {
            $this->error("'{$email}' not found");

            return;
        }

        $this->info("'{$email}' is now an admin");
    }
}
