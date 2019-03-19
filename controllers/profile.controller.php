<?php
    require_once './model/profile.model.php';
    require_once './controllers/login_user.controller.php';

    class ProfileController{

        public function fetchProfile(){
            $verified_user_id = new login_user();
            $fetchProfileData = new Profile();
            
            $uid = $verified_user_id->isLoggedIn();
            echo json_encode($fetchProfileData->read($uid));
        }
    }