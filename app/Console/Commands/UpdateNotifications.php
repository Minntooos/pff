<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;

class UpdateNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:update-sender';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing notifications to include sender_id';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notifications = Notification::whereNull('data->sender_id')->get();

        foreach ($notifications as $notification) {
            // Assuming you can determine the sender based on the type or other logic
            // For example, if type is 'like', you might need to extract sender from another source
            // Here, we'll set sender_id to the same as user_id for demonstration

            $data = $notification->data ?? [];
            $data['sender_id'] = $notification->user_id; // Adjust as needed

            $notification->data = $data;
            $notification->save();

            $this->info("Updated notification ID {$notification->id} with sender_id.");
        }

        $this->info('All notifications updated successfully.');

        return 0;
    }
}
