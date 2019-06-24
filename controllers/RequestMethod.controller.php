<?php

session_start();
//CONFIGURATION
require_once './config/RequestMethod.php';

//CONTROLLER
require_once 'controllers/signup_user.controller.php';
require_once 'controllers/login_user.controller.php';
require_once 'controllers/logout.controller.php';
require_once 'controllers/profile.controller.php';
require_once 'controllers/upload.controller.php';
require_once 'controllers/post.controller.php';
require_once 'controllers/follow.controller.php';
require_once 'controllers/order.controller.php';

//MODEL
require_once "./model/login_user.model.php";


$api = new RequestMethod();

$api->post("signup", function(){
    $controller = new signup_user();
    $controller->signup();
});

$api->post("login", function(){
    $controller = new login_user();
    $controller->login();
});

//SIGN UP LOGIN LOGOUT
$api->delete("logout", function(){
    $controller = new logOut();
    $controller->logout();
});

//LOGGED IN USER CREDENTIALS
$api->get("loggedIn", function(){
    $controller = new login_user();
    $model = new login_model();

    $uid = $controller->isLoggedIn();

    if($uid == false){
        http_response_code(401);
    }else{
        $data = $model->loginCredentials($uid);    
        echo json_encode(
            array(
                'id' => $data['id'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'imgUrl' => $data['profile_image'],
                'loggedIn' => true,
                'path' => 'home.html'
            )
        );
    }
   
});

/*********************************USER PROFILE CRUD*****************************************/
//USER PROFILE
$api->get("profile", function(){
    $controller = new ProfileController();
    $controller->getUserName();
});

//UPDATE PROFILE
$api->put("profile", function(){
    $controller = new ProfileController();
    $controller->updateUser();
});

//UPLOAD PROFILE IMAGE
$api->post("upload", function(){
    $upload = new UploadController();
    $upload->UploadProfile();
});
/*********************************USER PROFILE CRUD*****************************************/

/*********************************FOOD POST CRUD*****************************************/
//POST FOOD ITEM
$api->post("post", function(){
    $controller = new PostController();
    $controller->create_post();
});

//READ SINGLE FOOD POST - POST FEED
$api->get("post", function(){
    $controller = new PostController();
    $controller->read_post_single();
});

//READ ALL USER POST
$api->get("feed", function(){
    $controller = new PostController();
    $controller->read_post();
});

$api->get("getFollowingPost", function(){
    $controller = new PostController();
    $controller->read_following_post();
});

$api->put("post", function(){
    $controller = new PostController();
    $controller->update_post();
});

$api->delete("post", function(){
    $controller = new PostController();
    $controller->delete_post();
});
/*********************************FOOD POST CRUD END**************************************/


//UPLOAD FOOD GALLERY
$api->post("food", function(){
    $upload = new UploadController();
    $upload->uploadFoodPostGallery();
    session_destroy();
});


/*********************************FOLLOW CRUD*****************************************/
//GET FOLLOWER STATUS USER
$api->get("follow", function(){
    $followStatus = new followController();
    $followStatus->followStatus();
});

//GET FOLLOW STATUS
$api->post("follow", function(){
    $follow = new followController();
    $follow->followUser();
});


/*********************************FOLLOW CRUD END*****************************************/



/*********************************ORDER CRUD*****************************************/
//REQUEST ORDER
$api->post("order", function(){
    $orderController = new OrderController();
    $orderController->orderRequest();
});

$api->get("orders", function(){
    $orderController = new OrderController();
    $orderController->fetchOrderSingle();
});

$api->get("myOrder", function(){
    $orderController = new OrderController();
    $orderController->fetchMyOrder();
});

$api->put("orders", function(){
    $orderController = new OrderController();
    $orderController->updateRequestStatus();
});
//END REQUEST ORDER ENDPOINT
/*********************************ORDER CRUD*****************************************/


