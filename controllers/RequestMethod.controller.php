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

//SIGN UP LOGIN LOGOUT
$api->post("signup", function(){
    $controller = new signup_user();
    $controller->signup();
});

$api->post("login", function(){
    $controller = new login_user();
    $controller->login();
});

$api->delete("logout", function(){
    $controller = new logOut();
    $controller->logout();
});

//LOGGED IN USER CREDENTIALS
$api->get("loggedIn", function(){
    $controller = new login_user();
    $model = new login_model();

    $uid = $controller->isLoggedIn();
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
});


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

$api->delete("post", function(){
    $controller = new PostController();
    $controller->delete_post();
});

//UPLOAD FOOD GALLERY
$api->post("food", function(){
    $upload = new UploadController();
    $upload->uploadFoodPostGallery();
    session_destroy();
});

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

//REQUEST ORDER
$api->post("order", function(){
    $orderController = new OrderController();
    $orderController->orderRequest();
});

$api->get("orders", function(){
    $orderController = new OrderController();
    $orderController->fetchOrderSingle();
});

$api->put("orders", function(){
    $orderController = new OrderController();
    $orderController->updateRequestStatus();
});
//END REQUEST ORDER ENDPOINT



