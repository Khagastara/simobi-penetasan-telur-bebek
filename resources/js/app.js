import './bootstrap';

import * as PusherPushNotifications from "@pusher/push-notifications-web";

const beamsClient = new PusherPushNotifications.Client({
    instanceId: import.meta.env.VITE_PUSHER_BEAMS_INSTANCE_ID,
  });

  beamsClient.start()
    .then(() => {
        return Promise.all([
        beamsClient.addDeviceInterest('hello'),
        beamsClient.addDeviceInterest('owner-1')
        ]);
    })
    .then(() => console.log('Successfully registered and subscribed!'))
    .catch(console.error);
