<?php
use function GuzzleHttp\json_encode;

require_once './model/follow.model.php';
require_once 'login_user.controller.php';


    class followController{

        public function followUser(){

            $follow = new follow();
            $uid = new login_user();
            
            if(isset($_GET['name'])){
                $isFollowing = $follow->getFollowStatus($_GET['name']);
                
                if(!$isFollowing){
                    $follow->followUser($uid->isLoggedIn(), $_GET['name']);   
                }else{
                    $follow->unfollowUser($uid->isLoggedIn(), $_GET['name']);
                }

            }
        }

        

        public function followStatus(){
            $isFollowing = new follow();
            $uid = new login_user();

            if($uid->isLoggedIn()){
                if($isFollowing->getFollowStatus($_GET['name'])){
                    echo json_encode(array(
                       'name' => $_GET['name'],
                       'following' => true,
                       'Status' => "Unfollow"
                   ));
               }else{
                   echo json_encode(array(
                       'name' => $_GET['name'],
                       'following' => false,
                       'Status' => "Follow"
                   ));
               }
            }else{
                http_response_code(401);
            }
        }

        

    }