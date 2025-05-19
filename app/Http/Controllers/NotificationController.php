<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\PushNotifications\PushNotifications;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        $beamsClient = new PushNotifications([
            'instanceId' => env('PUSHER_BEAMS_INSTANCE_ID'),
            'secretKey' => env('PUSHER_BEAMS_SECRET_KEY'),
        ]);

        $response = $beamsClient->publishToInterests(
            ['hello'],
            [
                'web' => [
                    'notification' => [
                        'title' => 'Hello',
                        'body' => 'Hello, Tester world!',
                    ],
                ],
            ]
        );

        return response()->json($response);
    }
}
