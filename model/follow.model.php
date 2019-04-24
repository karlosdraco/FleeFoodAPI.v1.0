<?php

    require_once './config/DB.php';

    class follow{

        public $conn;
        private $followerCount;
        private $followingCount;

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
                $statement = $this->conn->query("SELECT user_id FROM follow_user WHERE user_id=:uid AND follow_id=:uid");
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

        public function showFollowers($name){
            $statement = $this->conn->query("SELECT id FROM users WHERE firstname=:fname");
            $statement->bindParam(':fname', $name);
            
            if($statement->execute()){
                $data = $statement->fetch();
                $uid = $data['id'];

                $statement = $this->conn->query("SELECT follow_user.follow_id,users.firstname, users.lastname, users.email,users.profile_image
                 FROM follow_user RIGHT JOIN users ON follow_user.follow_id = users.id WHERE user_id=:uid");
                $statement->bindParam(':uid', $uid);
                $statement->execute();

                if($statement->rowCount() > 0){
                    $this->followerCount = $statement->rowCount();
                }else{
                    $this->followerCount = 0;
                }
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        public function showFollowing($name){
            $statement = $this->conn->query("SELECT id FROM users WHERE firstname=:fname");
            $statement->bindParam(':fname', $name);
            
            if($statement->execute()){
                $data = $statement->fetch();
                $fid = $data['id'];
                
                $statement = $this->conn->query("SELECT follow_user.user_id, users.firstname,users.lastname,users.email,users.profile_image 
                FROM follow_user RIGHT JOIN users ON follow_user.user_id=users.id WHERE follow_id=:fid");
                $statement->bindParam(':fid', $fid);
                $statement->execute();

                if($statement->rowCount() > 0){
                    $this->followingCount = $statement->rowCount();
                }else{
                    $this->followingCount = 0;
                }

                return $statement->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        public function followerCount(){
           return $this->followerCount;
        }

        public function followingCount(){
           return $this->followingCount;
        }
    }