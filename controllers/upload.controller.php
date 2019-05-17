<?php
use function GuzzleHttp\json_encode;

//MODEL
    require_once './model/login_user.model.php';
    require_once './model/upload.model.php';
    //CONTROLLER
    require_once 'controllers/login_user.controller.php';
    require_once './classes/S3uploadForm.php';

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
                $uploadProfile = new S3Upload($folderOwner, "Profile images");
                //INSERT URL TO DATABASE

                if($uploadProfile->errorUploadResponse == 1){
                    $insertProfileImage->profileImageLink($uid, $uploadProfile->imgUrl);
                }else if($uploadProfile->errorUploadResponse == 0){
                    echo json_encode($uploadProfile->response);
                }
               
        }

        public function uploadFoodPostGallery(){
            
            $verified_user_id = new login_user();
            $loginCredentials = new login_model();
            $uploadFoodPost = new Upload();

            
                //USER LOGGED IN ID
                $uid = $verified_user_id->isLoggedIn();
                
                //USER CREDENTIALS
                $data = $loginCredentials->loginCredentials($uid);

                //PASSED USER ID AND FIRSTNAME IN AWS S3 FOLDER NAME
                $folderOwner = $data['id'].'.'.$data['firstname'];
    
                //UPLOAD IMAGE TO AWS S3 AND RETURN URL
                $foodImage = new S3Upload($folderOwner, "Food post");
                
                //INSERT URL TO DATABASE
                $uploadFoodPost->uploadFoodPostGallery($uid, $foodImage->imgUrl);

        }
    }