<?php 
    require_once './model/login_user.model.php';
    require_once 'controllers/login_user.controller.php';
    require_once './classes/S3-upload-form.php';

    class UploadController{

        
        public function UploadProfile(){
            $verified_user_id = new login_user();
            $loginCredentials = new login_model();
            $uid = $verified_user_id->isLoggedIn();
            
            $data= $loginCredentials->loginCredentials($uid);
            $folderOwner = $data['id'].'.'.$data['firstname'];

            $uploadProfile = new S3Upload($folderOwner, "profile Images");

        }
    }