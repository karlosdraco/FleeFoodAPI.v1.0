<?php

    require_once './config/DB.php';
    
    class Post{

        public $conn;
        public $uid;
        public $foodImg;
        public $foodName;
        public $foodDesc;
        public $foodPrice;
        public $foodCurrency;
        public $foodAvailability;
        public $deliveryFee;
        public $address1;
        public $address2;
        public $long;
        public $lat;

        public function __construct()
        {
            $this->conn = new DB();
        }

        public function create_post(){

            $statement = $this->conn->query("INSERT INTO food_post(user_id, food_name, food_description, 
            food_price, food_availability, delivery_type, currency, addressLine1, addressLine2) 
            VALUES (:uid, :fname, :fdesc, :fprice, :favail, :delFee, :currency, :add1, :add2)");

            $statement->bindParam(':uid', $this->uid);
            $statement->bindParam(':fname', $this->foodName);
            $statement->bindParam(':fdesc', $this->foodDesc);
            $statement->bindParam(':fprice', $this->foodPrice);
            $statement->bindParam(':currency', $this->foodCurrency);
            $statement->bindParam(':favail', $this->foodAvailability);
            $statement->bindParam(':delFee', $this->deliveryFee);
            $statement->bindParam(':add1', $this->address1);
            $statement->bindParam(':add2', $this->address2);
            
            if($statement->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function read_post(){
            
            $statement = $this->conn->query("SELECT users.id, users.profile_image, users.firstname, users.lastname,
            users.email, users.contact, food_post.* FROM users RIGHT JOIN food_post ON users.id=food_post.user_id 
            ORDER BY food_post.post_date DESC");
            $statement->execute();
            
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

    }