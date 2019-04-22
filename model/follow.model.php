<?php

    require_once './config/DB.php';

    class follow{

        public $conn;

        public function __construct()
        {
            $this->conn = new DB();
        }

        public function followUser($fid, $name){
            
            $statement = $this->conn->query("SELECT id FROM users WHERE firstname=:name");
            $statement->bindParam(':name', $name);
            
            if($statement->execute()){
                $data = $statement->fetch();
                $uid = $data['id'];
                  
                $statement = $this->conn->query("INSERT INTO follow_user(user_id, follow_id) VALUES(:uid, :fid)");
                $statement->bindParam('uid', $uid);
                $statement->bindParam('fid', $fid);
                $statement->execute();
            }
        }

        public function unfollowUser($fid, $name){
            $statement = $this->conn->query("SELECT id FROM users WHERE firstname=:name");
            $statement->bindParam(':name', $name);
            
            if($statement->execute()){
                $data = $statement->fetch();
                $uid = $data['id'];
                  
                $statement = $this->conn->query("DELETE FROM follow_user WHERE user_id=:uid AND follow_id=:fid");
                $statement->bindParam('uid', $uid);
                $statement->bindParam('fid', $fid);
                $statement->execute();
            }
        }

        public function getFollowStatus($name){
            $statement = $this->conn->query("SELECT id FROM users WHERE firstname=:firstname");
            $statement->bindParam(':firstname', $name);
            
            if($statement->execute()){
                
                $data = $statement->fetch();
                $statement = $this->conn->query("SELECT user_id FROM follow_user WHERE user_id=:uid");
                $uid = $data['id'];
                $statement->bindParam(':uid', $uid);
                $statement->execute();

                if($statement->rowCount() > 0){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }