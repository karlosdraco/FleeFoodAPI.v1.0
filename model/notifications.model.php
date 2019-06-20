<?php

require_once './config/DB.php';

    class Notifications{

        public $conn;
        public $notifCount;
        public $isFetched;
        public $notifId;
        public $isViewed;

        public function __construct()
        {
            $this->conn = new DB();
        }


        public function pushNotification($user_id, $agent_id, $food_id, $verb, $subject){
            $statement = $this->conn->query("INSERT INTO notifications(user_id, agent_id, food_id, verb, subject)
            VALUES(:uid, :aid, :fid, :verb, :sub);");

            $statement->bindParam(':uid', $user_id);
            $statement->bindParam(':aid', $agent_id);
            $statement->bindParam(':fid', $food_id);
            $statement->bindParam(':verb', $verb);
            $statement->bindParam(':sub', $subject);
            $statement->execute();
        }

        public function fetchNotification($loggedUserId){
            
            $statement = $this->conn->query("SELECT * FROM notifications WHERE user_id=:uid");
            $statement->bindParam(':uid', $loggedUserId);
            
            if($statement->execute()){
                if($statement->rowCount() > 0){
                   $data = $statement->fetch();
                   $uid = $data['user_id'];

                   $statement = $this->conn->query("SELECT notifications.*, 
                   DATE_FORMAT(notifications.notif_date, '%W %M %e %Y')  AS notif_date, 
                   food_post.food_name,users.firstname,users.lastname,users.profile_image
                   FROM notifications RIGHT JOIN food_post ON notifications.food_id=food_post.id
                   RIGHT JOIN users ON notifications.agent_id=users.id
                   WHERE notifications.user_id=:uid ORDER BY notifications.notif_date DESC");
                   $statement->bindParam(':uid', $uid);
                   $statement->execute();
                   return $statement->fetchAll(PDO::FETCH_ASSOC);
                }
            }
        }

        public function notificationCount($loggedUserId){
            $statement = $this->conn->query("SELECT user_id,fetched,viewed FROM notifications WHERE user_id=:uid AND fetched='0'");
            $statement->bindParam(':uid', $loggedUserId);
            
            if($statement->execute()){
                if($statement->rowCount() > 0){
                    $data = $statement->fetch(PDO::FETCH_ASSOC);
                    $this->notifCount = $statement->rowCount();
                    $this->notifId = $data['user_id'];
                    $this->isFetched = $data['fetched'];
                    $this->isViewed = $data['viewed'];
                    return true;
                }else{
                    $this->notifCount = 0;
                    $this->isFetched = 1;
                    $this->isViewed = 0;
                    return false;
                }
            }
        }

       
        public function updateFetching($loggedUserId){

            $fetch = 1;
            $statement = $this->conn->query("UPDATE notifications SET fetched=:fetch WHERE user_id=:uid");
            $statement->bindParam(':fetch', $fetch);
            $statement->bindParam(':uid', $loggedUserId);
            
            $statement->execute();
         
        }



    }