<?php

namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

trait SendNotification
{
    public function sendNotification($title, $desc, $image = "https://authentic.myori.my/icon.png")
    {
        $factory = (new Factory)->withServiceAccount(public_path() . '/' . \Config::get('notification.notification_key'));
        $messaging = $factory->createMessaging();

        $message = CloudMessage::fromArray([
            'topic' => 'ssa',
            'notification' => ["title" => $title, "body" => $desc, "imageUrl" => $image]
        ])->withHighestPossiblePriority();

        return $messaging->send($message);

    }
}
