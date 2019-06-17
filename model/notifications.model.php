<?php

require_once './config/DB.php';

    class Notifications{

        public $conn;

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

                   $statement = $this->conn->query("SELECT notifications.*,food_post.food_name,users.firstname,
                   users.lastname,users.profile_image
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
            $statement = $this->conn->query("SELECT * FROM notifications WHERE agent_id=:aid");
            $statement->bindParam(':aid', $loggedUserId);
        }



    }