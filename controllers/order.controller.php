<?php

require_once './model/order.model.php';
require_once 'login_user.controller.php';

class OrderController{

    public function orderRequest(){

        $order = new Order();
        $data = json_decode(file_get_contents("php://input"));
        $order->food_id = $data->foodId;
        $order->user_id = $data->userId;
        $order->buyer_id = $data->buyerId;
        $order->orderRequest();
    }

    public function fetchOrderSingle(){
        $order = new Order();

        if(isset($_GET['name']) && isset($_GET['id'])){
            echo json_encode($order->readOrder($_GET['name'], $_GET['uid']));
        }
        
    }

}