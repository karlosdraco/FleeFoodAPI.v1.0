<?php

require_once './config/RequestMethod.php';
require_once 'controllers/signup_user.controller.php';
require_once 'controllers/login_user.controller.php';

$api = new RequestMethod();


$api->post("signup", function(){
    $controller = new signup_user();
    $controller->signup();
});

$api->post("login", function(){
    $controller = new login_user();
    $controller->login();
});

