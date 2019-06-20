<?php

    require_once './config/DB.php';
    
    class Post{

        public $conn;
        public $uid;
        public $foodId;
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
        public $userReport;

        public function __construct()
        {
            $this->conn = new DB();
        }

        public function create_post(){

            $statement = $this->conn->query("INSERT INTO food_post(user_id, food_name, food_description, 
            food_price, food_availability, delivery_type, currency, addressLine1, addressLine2, report, reported, fetched) 
            VALUES (:uid, :fname, :fdesc, :fprice, :favail, :delFee, :currency, :add1, :add2, report='0', reported='0', fetched='0')");

            $statement->bindParam(':uid', $this->uid);
            $statement->bindParam(':fname', $this->foodName);
            $statement->bindParam(':fdesc', $this->foodDesc);
            $statement->bindParam(':fprice', $this->foodPrice);
            $statement->bindParam(':currency', $this->foodCurrency);
            $statement->bindParam(':favail', $this->foodAvailability);
            $statement->bindParam(':delFee', $this->deliveryFee);
            $statement->bindParam(':add1', $this->address1);
            $statement->bindParam(':add2', $this->address2);
            $_SESSION['food_name'] = $this->foodName;
            
            if($statement->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function read_post(){
            
            $statement = $this->conn->query("SELECT users.id, users.profile_image, users.firstname, users.lastname,
            users.email, users.contact, food_image_gallery.image_link, food_post.*,TIME_FORMAT(food_post.post_date, '%r') 
            AS post_expiration, DATE_FORMAT(food_post.post_date, '%W %M %e %Y') AS post_date FROM users RIGHT JOIN food_post ON users.id=food_post.user_id 
            RIGHT JOIN food_image_gallery ON food_post.id=food_image_gallery.food_id ORDER BY food_post.post_date DESC");
           
            $statement->execute();
            
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public function read_post_single($name, $id){

            $statement = $this->conn->query("SELECT id FROM users WHERE firstname=:fname AND id=:uid");
            $statement->bindParam(':fname', $name);
            $statement->bindParam(':uid', $id);
            
            if($statement->execute()){
                if($statement->rowCount() > 0){
                    $data = $statement->fetch();
                    $uid = $data['id'];
                    $statement = $this->conn->query("SELECT users.id,food_image_gallery.image_link, food_post.* FROM users RIGHT JOIN food_post ON users.id=food_post.user_id 
                    RIGHT JOIN food_image_gallery ON food_post.id=food_image_gallery.food_id WHERE users.id = :uid ORDER BY food_post.post_date DESC");
                    $statement->bindParam(':uid', $uid);
                    $statement->execute();

                    if($statement->rowCount() > 0){
                        return $statement->fetchAll(PDO::FETCH_ASSOC);
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }
        }

        public function read_following_post($uid){
            
            $statement = $this->conn->query("SELECT users.id, users.profile_image, users.firstname, users.lastname,
            users.email, users.contact, food_image_gallery.image_link, food_post.*,TIME_FORMAT(food_post.post_date, '%r') 
            AS post_expiration, DATE_FORMAT(food_post.post_date, '%W %M %e %Y') AS post_date, follow_user.user_id
            FROM users RIGHT JOIN food_post ON users.id=food_post.user_id 
            RIGHT JOIN food_image_gallery ON food_post.id=food_image_gallery.food_id
            RIGHT JOIN follow_user ON users.id=follow_user.user_id
            WHERE follow_user.follow_id=:folid ORDER BY food_post.post_date DESC");
            $statement->bindParam(':folid', $uid);
           
            if($statement->execute()){
                if($statement->rowCount() > 0){
                    return $statement->fetchAll(PDO::FETCH_ASSOC);;
                }else{
                    return false;
                }
            }
            
            
        }

        public function update_post(){
            $statement = $this->conn->query("UPDATE food_post SET food_name=:fn, food_description=:fd, food_price=:fp,
            food_availability=:fa, delivery_type=:dt, currency=:fc, addressLine1=:add1, addressLine2=:add2 WHERE id=:fid AND user_id=:uid");
            
            $statement->bindParam(':fid', $this->foodId);
            $statement->bindParam(':uid', $this->uid);
            $statement->bindParam(':fn', $this->foodName);
            $statement->bindParam(':fd', $this->foodDesc);
            $statement->bindParam(':fp', $this->foodPrice);
            $statement->bindParam(':fa', $this->foodAvailability);
            $statement->bindParam(':dt', $this->deliveryFee);
            $statement->bindParam(':fc', $this->foodCurrency);
            $statement->bindParam(':add1', $this->address1);
            $statement->bindParam(':add2', $this->address2);
            
            if($statement->execute()){
                return true;
            }else{
                return false;
            }
    
        }

        public function delete_post(){
            
            $statement = $this->conn->query("DELETE food_post.*, food_image_gallery.* FROM food_post RIGHT JOIN food_image_gallery 
            ON food_post.id=food_image_gallery.food_id WHERE food_post.id=:fid AND food_post.user_id=:uid");
            $statement->bindParam(':fid', $this->foodId);
            $statement->bindParam(':uid', $this->uid);
            
            if($statement->execute()){
                return true;
            }else{
                return false;
            }

            
        }

    }