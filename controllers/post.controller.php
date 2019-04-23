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
             $post->foodName = $auth->sanitize($data->foodName);
             $post->foodDesc = $auth->sanitize($data->foodDescription);
             $post->foodPrice = $auth->sanitize($data->foodPrice);
             $post->foodCurrency = $auth->sanitize($data->currency);
             $post->foodAvailability = $auth->sanitize($data->foodAvailability);
             $post->deliveryFee = $auth->sanitize($data->deliveryFee);
             $post->address1 = $auth->sanitize($data->addressLine1);
             $post->address2 = $auth->sanitize($data->addressLine2);

             /*if(!$auth->isEmpty($post->foodName) || !$auth->isEmpty($post->foodDesc) || !$auth->isEmpty($post->foodPrice) || !$auth->isEmpty($post->foodCurrency) || !$auth->isEmpty($post->foodAvailability) || !$auth->isEmpty($post->deliveryFee) || !$auth->isEmpty($post->address1) || !$auth->isEmpty($post->address2)){
                 echo $response = json_encode(array(
                        'message' => 'Post empty',
                        'error'=> false
                ));
             }else{*/
                if($post->create_post()){
                    echo json_encode(array(
                        'message' => 'Posted',
                        'error'=> false
                    ));
                }else{
                    http_response_code(401);
                }
            //}

            /*INPUT AUTHENTICATION AND VERIFICATION*/
            //                                     //
            /*INPUT AUTHENTICATION AND VERIFICATION*/
        }

        public function read_post(){
            $post = new Post();
            $loggedIn = new login_user();
            

            $fetchData = $post->read_post($loggedIn->isLoggedIn());
            echo json_encode($fetchData);
        
           
        }
    }
    