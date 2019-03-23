<?php

    require_once './config/DB.php';

    class Profile{

        public $conn;
        public $bio;
        public $bdate;
        public $age;
        public $gender;
        public $occupation;
        public $add1;
        public $add2;
        public $country;
        public $zip;

        public function __construct(){
            $this->conn = new DB();
        }

        public function create($id){
            
            $statement = $this->conn->query("SELECT user_id FROM user_info WHERE user_id=:id");
            $statement->bindParam(':id', $id);
            if($statement->execute()){
                if($statement->rowCount() > 0){
                    return true;
                }else{
                    $statement = $this->conn->query(
                        "INSERT INTO user_info(
                            user_id, bio, birthdate, age,
                            gender, occupation, addressLine1, addressLine2, 
                            country,zipCode
                        ) VALUES(
                            :uid, :bio, :bdate, :age, :gender, :occupation, :add1, :add2,
                            :country, :zip 
                        )");
        
                    $statement->bindParam(':uid', $id);
                    $statement->bindParam(':bio', $this->bio);
                    $statement->bindParam(':bdate', $this->bdate);
                    $statement->bindParam(':age', $this->age);
                    $statement->bindParam(':gender', $this->gender);
                    $statement->bindParam(':occupation', $this->occupation);
                    $statement->bindParam(':add1', $this->add1);
                    $statement->bindParam(':add2', $this->add2);
                    $statement->bindParam(':country', $this->country);
                    $statement->bindParam(':zip', $this->zip);
                    $statement->execute();
                }
            }
         
        }

        public function read($id){
            $statement = $this->conn->query("SELECT id,firstname,lastname,email,contact FROM users WHERE id=:id");
            $statement->bindParam(':id', $id);
            if($statement->execute()){
                if($statement->rowCount() > 0){
                    return $statement->fetchAll(PDO::FETCH_ASSOC);
                }
                else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function readUserName($name){

            $statement = $this->conn->query("SELECT id FROM users WHERE firstname=:name");
            $statement->bindParam(':name', $name);
            if($statement->execute()){
                if($statement->rowCount() > 0){
                    $data =  $statement->fetch();
                    $statement = $this->conn->query("SELECT users.id, users.firstname, users.lastname,
                    users.email, users.contact, user_info.* FROM users LEFT JOIN user_info ON users.id=user_info.user_id WHERE users.id=:uid");
                    $statement->bindParam(':uid', $data['id']);
                    $statement->execute();
                    return $statement->fetchAll(PDO::FETCH_ASSOC);
                }
                else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function update($uid){
           $statement = $this->conn->query("UPDATE user_info SET bio=:bio, birthdate=:bdate, age=:age, 
           gender=:gender, occupation=:occupation, addressLine1=:add1, addressLine2=:add2, country=:country, zipCode=:zipCode WHERE user_id=:uid");

            $statement->bindParam(':uid', $uid);
            $statement->bindParam(':bio', $this->bio);
            $statement->bindParam(':bdate', $this->bdate);
            $statement->bindParam(':age', $this->age);
            $statement->bindParam(':gender', $this->gender);
            $statement->bindParam(':occupation', $this->occupation);
            $statement->bindParam(':add1', $this->add1);
            $statement->bindParam(':add2', $this->add2);
            $statement->bindParam(':country', $this->country);
            $statement->bindParam(':zipCode', $this->zip);
            $statement->execute();
        }
    }