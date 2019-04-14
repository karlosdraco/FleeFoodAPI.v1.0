<?php
use function GuzzleHttp\json_encode;

require_once './model/post.model.php';
    require_once 'controllers/login_user.controller.php';

    class PostController{

        public function create_post(){
             $post = new Post();
             $loggedIn = new login_user();
             
             $uid = $loggedIn->isLoggedIn();
             $post->uid = $uid;

             $data = json_decode(file_get_contents("php://input"));

             $post->foodName = $data->foodName;
             $post->foodDesc = $data->foodDescription;
             $post->foodPrice = $data->foodPrice;
             $post->foodCurrency = $data->currency;
             $post->foodAvailability = $data->foodAvailability;
             $post->deliveryFee = $data->deliveryFee;
             $post->address1 = $data->addressLine1;
             $post->address2 = $data->addressLine2;

             if($post->create_post()){
                 echo json_encode(array(
                     'message' => 'Posted',
                     'error'=> false
                 ));
             }else{
                 http_response_code(401);
             }

            /*INPUT AUTHENTICATION AND VERIFICATION*/
            //                                     //
            /*INPUT AUTHENTICATION AND VERIFICATION*/

        }



    }
    