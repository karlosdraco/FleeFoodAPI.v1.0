<?php
use function GuzzleHttp\json_encode;

require_once './classes/input-authentication.php';
require_once './model/post.model.php';
require_once 'login_user.controller.php';


    class PostController{

        public function create_post(){
             
             $auth = new inputAuthentication();
             $post = new Post();
             $loggedIn = new login_user();

             $uid = $loggedIn->isLoggedIn();

             //SUBMIT JSON FORM
             $data = json_decode(file_get_contents("php://input"));

             $post->uid = $uid;
             $post->foodName = $data->foodName;
             $post->foodDesc = $auth->sanitize($data->foodDescription);
             $post->foodPrice = $auth->sanitize($data->foodPrice);
             $post->foodCurrency = $auth->sanitize($data->currency);
             $post->foodAvailability = $auth->sanitize($data->foodAvailability);
             $post->deliveryFee = $auth->sanitize($data->deliveryFee);
             $post->address1 = $auth->sanitize($data->addressLine1);
             $post->address2 = $auth->sanitize($data->addressLine2);

                    
            if(!$auth->isEmpty($post->foodName) || !$auth->isEmpty($post->foodDesc) 
                || !$auth->isEmpty($post->foodPrice) || !$auth->isEmpty($post->foodCurrency)
                || !$auth->isEmpty($post->foodAvailability) || !$auth->isEmpty($post->deliveryFee)
                || !$auth->isEmpty($post->address1) || !$auth->isEmpty($post->address2)){
 
                 echo json_encode(array(
                        'message' => 'Cannot leave field(s) empty',
                        'error'=> true,
                        'msgColor' => '#ce2626'
                ));
            }else{
                if($post->create_post()){
                    echo json_encode(array(
                        'message' => 'Posted',
                        'error'=> false,
                        'msgColor' => '#44d809'
                    ));
                }else{
                    http_response_code(401);
                }
            }
        }

        public function read_post(){
            $post = new Post();
            $uid = new login_user();

            if($uid->isLoggedIn() == false){
                $uid->isLoggedIn();
            }else{
                $fetchData = $post->read_post();
                echo json_encode($fetchData);
            }
        }

        
        
        public function read_post_single(){
            $post = new Post();
            $loggedIn = new login_user();

            if($loggedIn->isLoggedIn()){
                if(isset($_GET['name']) && isset($_GET['id'])){
                
                    if($fetchData = $post->read_post_single($_GET['name'], $_GET['id'])){
                        echo json_encode($fetchData);
                    }else{
                        echo json_encode(array(
                            'message' => 'You have no post',
                            'count' => 0
                        ));
                    }
                }
            }else{
                return $loggedIn->isLoggedIn();
            }
        }

        
        
        public function read_following_post(){
            $post = new Post();
            $loggedIn = new login_user();

            if($loggedIn->isLoggedIn() == false){
                $loggedIn->isLoggedIn();
            }else{
                /*if($post->read_following_post($loggedIn->isLoggedIn()) == false){
                    echo json_encode(array(
                        'message' => 'You are not following a user',
                        'following' => false
                    ));
                }else{
                    echo json_encode($post->read_following_post($loggedIn->isLoggedIn()));
                }*/
                echo json_encode($post->read_following_post($loggedIn->isLoggedIn()));
            }
        }

        
        
        
        public function update_post(){
            $auth = new inputAuthentication();
            $post = new Post();
            $data = json_decode(file_get_contents("php://input"));

            $post->uid = $data->userId;
            $post->foodId = $data->foodId;
            $post->foodName = $data->foodName;
            $post->foodDesc = $auth->sanitize($data->foodDesc);
            $post->foodPrice = $auth->sanitize($data->foodPrice);
            $post->foodCurrency = $auth->sanitize($data->foodCurrency);
            $post->foodAvailability = $auth->sanitize($data->foodAvailability);
            $post->deliveryFee = $auth->sanitize($data->foodDelivery);
            $post->address1 = $auth->sanitize($data->foodAdd1);
            $post->address2 = $auth->sanitize($data->foodAdd2);

            if(!$auth->isEmpty($post->foodName) || !$auth->isEmpty($post->foodDesc) 
                || !$auth->varSet($post->foodPrice) || !$auth->isEmpty($post->foodCurrency)
                || !$auth->varSet($post->foodAvailability) || !$auth->varSet($post->deliveryFee)
                || !$auth->isEmpty($post->address1) || !$auth->isEmpty($post->address2)){
                 echo json_encode(array(
                        'message' => 'Cannot leave field(s) empty',
                        'error'=> true,
                        'msgColor' => '#ce2626'
                ));
            }else{
                if($post->update_post()){
                    echo json_encode(array(
                        'message' => 'Updated',
                        'error'=> false,
                        'msgColor' => '#44d809'
                    ));
                }else{
                    http_response_code(401);
                }
            }
        }


        public function delete_post(){
            $post = new Post();
            $data = json_decode(file_get_contents("php://input"));
            
            $post->foodId = $data->foodId;
            $post->uid = $data->userId;
            
            if($post->delete_post()){
                echo json_encode(array(
                    'response' => "deleted",
                    'errorCode' => 0
                ));
            }else{
                http_response_code(304);
            }
        }
    }
    