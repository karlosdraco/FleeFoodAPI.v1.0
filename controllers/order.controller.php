<?php
use function GuzzleHttp\json_encode;

require_once './model/order.model.php';
require_once 'login_user.controller.php';

class OrderController{

    public function orderRequest(){

        $order = new Order();
        $data = json_decode(file_get_contents("php://input"));
        $order->food_id = $data->foodId;
        $order->user_id = $data->userId;
        $order->buyer_id = $data->buyerId;
        $order->qty = $data->quantity;
        
        if(!$order->orderRequest()){
            echo json_encode(
                array(
                    'message' => "You have this existing order, wait until your order is declined or claimed",
                    'error' => true
                )
            );
        }else{
            echo json_encode(
                array(
                    'message' => "Order requested",
                    'error' => false
                )
            );
        }
    }

    public function fetchOrderSingle(){
        $order = new Order();

        if(isset($_GET['name']) && isset($_GET['id'])){
            echo json_encode($order->readOrder($_GET['name'], $_GET['id']));
        }
    }

    public function updateRequestStatus(){
        
        $order = new Order();
        $data = json_decode(file_get_contents("php://input"));
        $order->food_id = $data->foodId;
        $order->user_id = $data->userId;
        $order->buyer_id = $data->buyerId;
        
        if($data->request == 1){
            $order->requestStatusUpdate("accepted");
        }else if($data->request == 0){
            $order->requestStatusUpdate("declined");
        }

    }

}