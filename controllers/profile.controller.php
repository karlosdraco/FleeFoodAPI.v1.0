<?php
    require_once './model/profile.model.php';
    require_once './model/follow.model.php';
    require_once './model/order.model.php';

    require_once './controllers/login_user.controller.php';
    require_once './classes/input-authentication.php';
   

    class ProfileController{

        public function getUserName(){
            $fetchProfileData = new Profile();
            $verified_user = new login_user();
            $follow = new follow();
            $order = new Order();

            
            if($verified_user->isLoggedIn() == false){
                $verified_user->isLoggedIn();
            }else{
                if(isset($_GET['name']) && isset($_GET['id'])){
                    if($fetchProfileData->readUserName($_GET['name'], $_GET['id'])){
                        $dataTemp = array(
                            'followers' => $follow->showFollowers($_GET['name']), 
                            'following' => $follow->showFollowing($_GET['name']),
                            'followerCount' => $follow->followerCount(),
                            'followingCount' => $follow->followingCount(),
                            'requestOrderCount' => $order->getRequestCount($_GET['id'])
                        );

                        $profileData = array(
                            'user' => $fetchProfileData->readUserName($_GET['name'], $_GET['id'])
                        );

                        $profileData = array_merge($profileData['user'][0], $dataTemp);
                        echo json_encode(array(
                            'profile_data' => $profileData
                          )
                        );
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
                
        }

        public function updateUser(){
            
            $auth = new inputAuthentication();
            $fetchProfileData = new Profile();
            $verified_user_id = new login_user();
            
            $uid = $verified_user_id->isLoggedIn();

            $data = json_decode(file_get_contents("php://input"));
            $fetchProfileData->bio = $auth->sanitize($data->bio);
            $fetchProfileData->bdate = $auth->sanitize($data->birthdate);
            $fetchProfileData->age = $auth->sanitize($data->age);
            $fetchProfileData->add1 = $auth->sanitize($data->addressLine1);
            $fetchProfileData->add2 = $auth->sanitize($data->addressLine2);
            $fetchProfileData->occupation = $auth->sanitize($data->occupation);
            $fetchProfileData->country = $auth->sanitize($data->country);
            $fetchProfileData->zip = $auth->sanitize($data->zipCode);
            $fetchProfileData->gender = $auth->sanitize($data->gender);

            if($fetchProfileData->create($uid)){
               if($fetchProfileData->update($uid)){
                echo json_encode(
                    array(
                        'message' => "Updated",
                        'error' => false,
                        'status' => "Modified"
                    )
                );
               }else{
                   http_response_code(304);
               }
            }else{
                http_response_code(304);
            }
        }
    }