<?php

use App\Models\Notification;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        $notifications = Notification::whereNull('data->sender_id')->get();

        foreach ($notifications as $notification) {
            // Determine the sender based on your application's logic
            // For demonstration, we'll set sender_id to user_id
            $data = $notification->data ?? [];
            $data['sender_id'] = $notification->user_id; // Adjust as needed

            $notification->data = $data;
            $notification->save();
        }
    }

    public function down()
    {
        // Optionally, remove sender_id from data if needed
        $notifications = Notification::whereNotNull('data->sender_id')->get();

        foreach ($notifications as $notification) {
            $data = $notification->data ?? [];
            unset($data['sender_id']);

            $notification->data = $data;
            $notification->save();
        }
    }
};
