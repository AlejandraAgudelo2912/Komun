<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\GeneralNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotifyUsersCommand extends Command
{
    protected $signature = 'admins:notify {--subject= : Asunto del email}';

    protected $description = 'Send email notifications to all users with the admin role';

    public function handle()
    {
        $admins = User::role('admin')->get();

        $subject = $this->option('subject') ?? 'Important Notification';

        $this->info('Starting to send emails to admins...');

        if ($admins->isEmpty()) {
            $this->info('There are no admins to notify.');
            return 0;
        }

        $this->info('Found Admins:' . $admins->count());

        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->queue(new GeneralNotificationMail($admin, $subject));

                $this->info("Email sent to: {$admin->email}");

                Log::info("Email sent to admin with ID: {$admin->id} ({$admin->email})");
                sleep(5);

            } catch (\Exception $e) {
                $this->error("Error enviando email a {$admin->email}: " . $e->getMessage());
            }
        }

        $this->info('Sent emails to all admins.');
        return 0;
    }
}
