<?php

require_once './config/RequestMethod.php';
require_once 'controllers/signup_user.controller.php';
require_once 'controllers/login_user.controller.php';
require_once 'controllers/logout.controller.php';
require_once 'controllers/profile.controller.php';


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

$api->post("logout", function(){
    $controller = new logOut();
    $controller->logout();
});

//USER PROFILE

$api->get("profile", function(){
    $controller = new ProfileController();
    $controller->fetchProfile();
});


