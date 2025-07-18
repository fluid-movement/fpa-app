<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    protected $signature = 'mail:test {to : The email address to send the test mail to}';

    protected $description = 'Send a test email to verify SMTP configuration';

    public function handle(): void
    {
        $to = $this->argument('to');
        try {
            Mail::raw('This is a test email to verify SMTP configuration.', function ($message) use ($to) {
                $message->to($to)->subject('SMTP Test Email');
            });

            $this->info("Test email sent successfully to {$to}.");
        } catch (\Exception $e) {
            $this->error('Failed to send test email: '.$e->getMessage());
        }
    }
}
