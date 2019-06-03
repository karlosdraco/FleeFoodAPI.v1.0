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

        $requestStatus = array(
            'acc' => "accepted",
            'dec' => "declined",
            'clm' => "claimed",
            'can' => "cancelled"
        );
        
        $order = new Order();
        $data = json_decode(file_get_contents("php://input"));
        $order->food_id = $data->foodId;
        $order->user_id = $data->userId;
        $order->buyer_id = $data->buyerId;
        
        if($data->request == 1){
            $order->requestStatusUpdate($requestStatus['acc']);
            echo json_encode(array(
                'message' => "order modified",
                'status' => 'modified',
                'requestStatus' => 'accepted'
            ));
        }else if($data->request == 0){
            $order->requestStatusUpdate($requestStatus['dec']);
            echo json_encode(array(
                'message' => "order modified",
                'status' => 'modified',
                'requestStatus' => 'declined'
            ));
        }else if($data->request == 2){
            $order->requestStatusUpdate($requestStatus['clm']);
            echo json_encode(array(
                'message' => "order modified",
                'status' => 'modified',
                'requestStatus' => 'claimed'
            ));
        }else if($data->request == 3){
            $order->requestStatusUpdate($requestStatus['can']);
            echo json_encode(array(
                'message' => "order modified",
                'status' => 'modified',
                'requestStatus' => 'cancelled'
            ));
        }

    }

}