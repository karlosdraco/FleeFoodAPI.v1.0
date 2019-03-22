<?php
    require_once './model/profile.model.php';
    require_once './controllers/login_user.controller.php';

    class ProfileController{

        public function getUser(){
            $verified_user_id = new login_user();
            $fetchProfileData = new Profile();
            
            $uid = $verified_user_id->isLoggedIn();
                if($fetchProfileData->read($uid)){
                    echo json_encode($fetchProfileData->read($uid));
                }else{
                    echo json_encode(
                        array(
                            'message' => "Invalid id",
                            'error' => true 
                        )
                    );
                } 
        }

        public function getUserName(){
            $fetchProfileData = new Profile();
                if(isset($_GET['name'])){
                    if($fetchProfileData->readUserName($_GET['name'])){
                        echo json_encode($fetchProfileData->readUserName($_GET['name']));
                    }else{
                        echo json_encode(
                            array(
                                'message' => "This page isn't available",
                                'error' => true 
                            )
                        );
                    } 
                }
        }

        public function updateUser(){
            $fetchProfileData = new Profile();
            $verified_user_id = new login_user();
            $uid = $verified_user_id->isLoggedIn();

            $data = json_decode(file_get_contents("php://input"));
            $fetchProfileData->bio = $data->bio;
            $fetchProfileData->bdate = $data->birthdate;
            $fetchProfileData->age = $data->age;
            $fetchProfileData->gender = $data->gender;
            $fetchProfileData->occupation = $data->occupation;
            $fetchProfileData->add1 = $data->addressLine1;
            $fetchProfileData->add2 = $data->addressLine2;
            $fetchProfileData->country = $data->country;
            $fetchProfileData->zip = $data->zipCode;

            if($fetchProfileData->create($uid)){
                $fetchProfileData->update($uid);
                echo json_encode(
                    array(
                        'message' => "Updated",
                        'error' => false
                    )
                );
            }
        }
    }