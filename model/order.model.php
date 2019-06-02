<?php
require_once './config/DB.php';

class Order{

    public $food_id;
    public $user_id;
    public $buyer_id;
    public $qty;
    public $requestCount;
    public $conn;

    public function __construct()
    {
      $this->conn = new DB();
    }

    public function orderRequest(){
        $statement = $this->conn->query("SELECT buyer_id, food_id FROM orders WHERE food_id=:fid AND buyer_id=:bid");
        $statement->bindParam(':fid', $this->food_id);
        $statement->bindParam(':bid', $this->buyer_id);
        
        if($statement->execute()){
            if($statement->rowCount() > 0){
                return false;
            }else{
                $statement = $this->conn->query("INSERT INTO orders(food_id, user_id, buyer_id, quantity, request) VALUES(:fid, :uid, :bid, :qty, request='pending');");
                $statement->bindParam(':fid', $this->food_id);
                $statement->bindParam(':uid', $this->user_id);
                $statement->bindParam(':bid', $this->buyer_id);
                $statement->bindParam(':qty', $this->qty);
                $statement->execute();
                return true;
            }
           
        }
        
    }

    public function readOrder($name, $id){
        $statement = $this->conn->query("SELECT id FROM users WHERE firstname=:fname AND id=:id");
        $statement->bindParam(':fname', $name);
        $statement->bindParam(':id', $id);
        
        if($statement->execute()){
            if($statement->rowCount() > 0){
                $data = $statement->fetch();
                $uid = $data['id'];
                $statement = $this->conn->query("SELECT orders.id, orders.food_id, orders.buyer_id, orders.user_id, orders.request, orders.quantity,
                                                 users.firstname, users.lastname, users.contact, users.profile_image, food_post.food_name, 
                                                 food_image_gallery.image_link FROM orders
                                                 RIGHT JOIN users ON orders.buyer_id = users.id
                                                 RIGHT JOIN food_post ON orders.food_id = food_post.id
                                                 RIGHT JOIN food_image_gallery ON food_post.id = food_image_gallery.food_id
                                                 WHERE orders.user_id=:id AND (orders.request = 'accepted' OR orders.request = 'pending') ORDER BY orders.order_date DESC");
               $statement->bindParam(':id', $uid);
               $statement->execute();

            }
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRequestCount($uid){
        $statement = $this->conn->query("SELECT user_id FROM orders WHERE user_id=:uid AND (request = 'accepted' OR request = 'pending')");
        $statement->bindParam(':uid', $uid);
        $statement->execute();

        if($statement->rowCount() > 0){
            return $statement->rowCount();
        }else{
            return  0;
        }
    }

    public function requestStatusUpdate($request){
    
        $statement = $this->conn->query("UPDATE orders SET request=:req WHERE food_id=:fid AND buyer_id=:bid AND user_id=:uid");
        $statement->bindParam(':req', $request);
        $statement->bindParam(':fid', $this->food_id);
        $statement->bindParam(':bid', $this->buyer_id);
        $statement->bindParam(':uid', $this->user_id);
        $statement->execute();
    }


}