<?php
use function GuzzleHttp\json_encode;

require_once './model/notifications.model.php';
require_once './model/follow.model.php';
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
        $followAsyncData = new follow();
        $loggedIn = new login_user();
        $uid = $loggedIn->isLoggedIn();

        $fetchNotif->notificationCount($uid);
        $followAsyncData->asyncFollowerCount($uid);
        $followAsyncData->asyncFollowingCount($uid);
       

        if($uid == false){
            http_response_code(401);
        }else{

            $dataTemp = array(
                'followerCount' => $followAsyncData->followerCount(),
                'followingCount' => $followAsyncData->followingCount()
            );

            $notifAsyncData =  array(
                'notificationId' => $fetchNotif->notifId,
                'notificationCount' => $fetchNotif->notifCount,
                'fetched' => $fetchNotif->isFetched,
                'viewed' => $fetchNotif->isViewed
            );

            $globalAsyncData = array_merge($notifAsyncData, $dataTemp);
            echo json_encode(
               $globalAsyncData
            );
        }
    }


    public function updateFetch(){
        $fetchNotif = new Notifications();
        $loggedIn = new login_user();

        $fetchNotif->updateFetching($loggedIn->isLoggedIn());
    }


}