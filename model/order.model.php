<?php
require_once './config/DB.php';

class Order{

    public $food_id;
    public $user_id;
    public $buyer_id;
    public $conn;

    public function __construct()
    {
      $this->conn = new DB();
    }

    public function orderRequest(){
        $statement = $this->conn->query("INSERT INTO orders(food_id, user_id, buyer_id) VALUES(:fid, :uid, :bid);");
        $statement->bindParam(':fid', $this->food_id);
        $statement->bindParam(':uid', $this->user_id);
        $statement->bindParam(':bid', $this->buyer_id);
        $statement->execute();
    }

    public function readOrder($name, $id){
        $statement = $this->conn->query("SELECT id FROM users WHERE firstname=:fname AND id=:id");
        $statement->bindParam(':fname', $name);
        $statement->bindParam(':id', $id);
        
        if($statement->execute()){
            if($statement->rowCount() > 0){
                $data = $statement->fetch();
                $uid = $data['id'];
                $statement = $this->conn->query("SELECT orders.id, orders.food_id, orders.buyer_id, orders.user_id, 
                                                 users.firstname, users.lastname, users.email, food_post.food_name, 
                                                 food_image_gallery.image_link FROM orders
                                                 RIGHT JOIN users ON orders.buyer_id = users.id
                                                 RIGHT JOIN food_post ON orders.food_id = food_post.id
                                                 RIGHT JOIN food_image_gallery ON food_post.id = food_image_gallery.food_id
                                                 WHERE orders.user_id=:id");
               $statement->bindParam(':id', $uid);
               $statement->execute();
            }
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}