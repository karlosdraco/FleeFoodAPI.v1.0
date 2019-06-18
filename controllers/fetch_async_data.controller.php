<?php
use function GuzzleHttp\json_encode;

require_once './model/notifications.model.php';
require_once 'login_user.controller.php';

class AsyncData{

    public function notifcation(){
        $fetchNotif = new Notifications();
        $loggedIn = new login_user();
        $uid = $loggedIn->isLoggedIn();
        
        echo json_encode($fetchNotif->fetchNotification($uid));
    }

    public function asyncNotifCount(){
        $fetchNotif = new Notifications();
        $loggedIn = new login_user();
        $uid = $loggedIn->isLoggedIn();
        $notifGlobals = $fetchNotif->notificationCount($uid);

        echo json_encode(
            array(
                'notificationId' => $fetchNotif->notifId,
                'notificationCount' => $fetchNotif->notifCount,
                'fetched' => $fetchNotif->isFetched,
                'viewed' => $fetchNotif->isViewed
            )
        );
    }

    public function updateFetch(){
        $fetchNotif = new Notifications();
        $loggedIn = new login_user();

        $fetchNotif->updateFetching($loggedIn->isLoggedIn());
         
    }


}