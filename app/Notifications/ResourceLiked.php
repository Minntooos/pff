<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ResourceLiked extends Notification
{
    use Queueable;

    protected $resource;

    protected $user;

    public function __construct($user, $resource)
    {
        $this->user = $user;
        $this->resource = $resource;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "{$this->user->name} liked your resource: {$this->resource->title}",
        ];
    }
}
