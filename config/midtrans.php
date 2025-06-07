<?php

return [
  'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
  'serverKey' => env('MIDTRANS_SERVER_KEY'),
  'clientKey' => env('MIDTRANS_CLIENT_KEY'),
  'isProduction' => env('MIDTRANS_IS_PRODUCTION'),
  'isSanitized' => env('MIDTRANS_IS_SANITIZED'),
  'is3ds' => env('MIDTRANS_IS_3DS'),

  'notificationUrl' => env('MIDTRANS_NOTIFICATION_URL', config('app.url') . '/payment/callback'),
];
