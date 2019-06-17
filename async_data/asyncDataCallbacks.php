<?php

require_once 'controllers/fetch_async_data.controller.php';
require_once 'config/RequestMethod.php';

$api = new RequestMethod();

$api->get('getAsyncNotifications', function(){
    $notif = new AsyncData();
    $notif->notifcation();
});
