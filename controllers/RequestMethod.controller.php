<?php

//CONFIGURATION
require_once './config/RequestMethod.php';

//CONTROLLER
require_once 'controllers/signup_user.controller.php';
require_once 'controllers/login_user.controller.php';
require_once 'controllers/logout.controller.php';
require_once 'controllers/profile.controller.php';
require_once 'controllers/upload.controller.php';

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
            'loggedIn' => true
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

$api->post("upload", function(){
    $upload = new UploadController();
    $upload->UploadProfile();
});




