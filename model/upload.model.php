<?php
   require_once './config/DB.php';

    class Upload{

        public $conn;

        public function __construct()
        {
            $this->conn = new DB();    
        }

        public function InsertImageLink($uid, $keyname){

            $statement = $this->conn->query("SELECT id FROM users WHERE id=:id");
            $statement->bindParam(':id', $uid);

            if($statement->execute()){
                if($statement->rowCount() > 0){
                    $statement = $this->conn->query("UPDATE users, user_info 
                    SET users.profile_image=:imgPath, user_info.profile_image=:imgPath
                    WHERE users.id = user_info.user_id 
                    AND users.id=:uid");
                    $statement->bindParam(':uid', $uid);
                    $statement->bindParam(':imgPath', $keyname);

                    if($statement->execute()){
                        $statement = $this->conn->query("INSERT INTO profile_image(image_link, user_id) 
                        VALUES(:imgPath, :uid);");
                        $statement->bindParam(':uid', $uid);
                        $statement->bindParam(':imgPath', $keyname);
                        $statement->execute();
                    }
                }
            }
        }

      
        public function uploadFoodPostGallery($uid, $keyname){

            $statement = $this->conn->query("SELECT id FROM food_post WHERE id=:id");
            $statement->bindParam(':id', $uid);

            if($statement->execute()){
                if($statement->rowCount() > 0){
                    $data = $statement->fetch();
                    $statement = $this->conn->query("INSERT INTO food_image_gallery(user_id, food_id, image_link)
                    VALUES(':uid',':fid', 'imgurl')");

                    $statement->bindPara(':uid', $uid);
                    $statement->bindParam(':fid', $data['id']);
                    $statement->bindParam(':imgurl', $keyname);

                }else{
                    return false;
                }
            }else{
                return $statement->error;
            }
        }
        
    }