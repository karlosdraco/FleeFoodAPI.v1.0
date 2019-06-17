<?php
require_once './model/notifications.model.php';
require_once 'login_user.controller.php';

class AsyncData{

    public function notifcation(){
        $fetchNotif = new Notifications();
        $loggedIn = new login_user();
        $uid = $loggedIn->isLoggedIn();
        
        echo json_encode($fetchNotif->fetchNotification($uid));
    }




}