<?php
use function GuzzleHttp\json_encode;
require_once './model/order.model.php';
require_once './model/notifications.model.php';
require_once 'login_user.controller.php';



class OrderController{

    public function orderRequest(){

        $order = new Order();
        $notif = new Notifications();
        $initiator = new login_user();
        $notifConf = require_once './config/notifications-config.php';

        $data = json_decode(file_get_contents("php://input"));
        $order->food_id = $data->foodId;
        $order->user_id = $data->userId;
        $order->buyer_id = $data->buyerId;
        $order->qty = $data->quantity;
        $order->price = $data->price;
        
        if(!$order->orderRequest()){
            echo json_encode(
                array(
                    'message' => "You have this existing order, wait until your order is declined or claimed",
                    'error' => true
                )
            );
        }else{

            $notif->pushNotification($order->user_id, $initiator->isLoggedIn(), $order->food_id, $notifConf['verb'][0], "order");
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
        $verified_user = new login_user();

        if($verified_user->isLoggedIn() == false){
            $verified_user->isLoggedIn();
        }else{
            if(isset($_GET['name']) && isset($_GET['id'])){
                echo json_encode($order->readOrder($_GET['name'], $_GET['id']));
            }
        }
     
    }

    public function fetchMyOrder(){
        $order = new Order();
        $verified_user = new login_user();

        if($verified_user->isLoggedIn() == false){
            $verified_user->isLoggedIn();
        }else{

            if($order->readMyOrder($_GET['id']) == false){
                echo json_encode(
                    array(
                        'count'=> 0,
                        'message' => "You have no order"
                    )
                );
            }else{
                if(isset($_GET['id'])){
                    echo json_encode($order->readMyOrder($_GET['id']));
                }
            }
            
        }
    }

    public function updateRequestStatus(){

        $initiator = new login_user();
        $notif = new Notifications();
        $notifConf = require_once './config/notifications-config.php';

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
        $order->order_id = $data->orderId;
        
        if($data->request == 1){
            $order->requestStatusUpdate($requestStatus['acc']);
            echo json_encode(array(
                'message' => "order modified",
                'status' => 'modified',
                'requestStatus' => 'accepted'
            ));
            $notif->pushNotification($order->buyer_id, $initiator->isLoggedIn(),$order->food_id,$notifConf['verb'][2], $notifConf['subject'][0]);
        }else if($data->request == 0){
            $order->requestStatusUpdate($requestStatus['dec']);
            echo json_encode(array(
                'message' => "order modified",
                'status' => 'modified',
                'requestStatus' => 'declined'
            ));
            $notif->pushNotification($order->buyer_id, $initiator->isLoggedIn(),$order->food_id,$notifConf['verb'][3], $notifConf['subject'][0]);
        }else if($data->request == 2){
            $order->requestStatusUpdate($requestStatus['clm']);
            echo json_encode(array(
                'message' => "order modified",
                'status' => 'modified',
                'requestStatus' => 'claimed'
            ));
            $notif->pushNotification($order->buyer_id, $initiator->isLoggedIn(),$order->food_id,$notifConf['verb'][4], $notifConf['subject'][0]);
        }else if($data->request == 3){
            $order->requestStatusUpdate($requestStatus['can']);
            echo json_encode(array(
                'message' => "order modified",
                'status' => 'modified',
                'requestStatus' => 'cancelled'
            ));
            $notif->pushNotification($order->buyer_id, $initiator->isLoggedIn(),$order->food_id,$notifConf['verb'][5], $notifConf['subject'][0]);
        }

    }

}