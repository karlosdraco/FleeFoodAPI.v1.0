<?php

    //MODEL
    require_once './model/login_user.model.php';
    require_once './model/upload.model.php';
    //CONTROLLER
    require_once 'controllers/login_user.controller.php';
    require_once './classes/S3-upload-form.php';

    class UploadController{
     
        public function UploadProfile(){
            $verified_user_id = new login_user();
            $loginCredentials = new login_model();
            $insertProfileImage = new Upload();

            //USER LOGGED IN ID
            $uid = $verified_user_id->isLoggedIn();
            
            //USER CREDENTIALS
            $data = $loginCredentials->loginCredentials($uid);
            //PASSED USER ID AND FIRSTNAME IN AWS S3 FOLDER NAME
            $folderOwner = $data['id'].'.'.$data['firstname'];

            //UPLOAD IMAGE TO AWS S3 AND RETURN URL
            $uploadProfile = new S3Upload($folderOwner, "profile Images");
            //INSERT URL TO DATABASE
            $insertProfileImage->InsertImageLink($uid, $uploadProfile->imgUrl);

        }
    }