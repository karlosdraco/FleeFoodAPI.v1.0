<?php

require_once './config/RequestMethod.php';
require_once 'controllers/user.controller.php';

$api = new RequestMethod();

$api->get("vendors", function(){
    $controller = new user();
    $controller->fetch_user_data();
});

$api->get("user", function(){
    $controller = new user();
    $controller->fetch_single_data();
}); 

$api->post("signin", function(){
    $controller = new user();
    $controller->signin();
}); 


