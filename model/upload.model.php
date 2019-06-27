<?php
   require_once './config/DB.php';

    class Upload{

        public $conn;
        private $FoodName;

        public function __construct()
        {
            $this->conn = new DB();    
        }

        public function profileImageLink($uid, $keyname){

            $statement = $this->conn->query("SELECT id FROM users WHERE id=:id");
            $statement->bindParam(':id', $uid);

            if($statement->execute()){
                if($statement->rowCount() > 0){
                  
                    $statement = $this->conn->query("SELECT user_id FROM user_info WHERE user_id=:uid");
                    $statement->bindParam(':uid', $uid);

                    if($statement->execute()){
                        if($statement->rowCount() > 0){
                            $statement = $this->conn->query("UPDATE users, user_info 
                            SET users.profile_image=:imgPath, user_info.profile_image=:imgPath
                            WHERE users.id = user_info.user_id 
                            AND users.id=:uid");

                            $statement->bindParam(':uid', $uid);
                            $statement->bindParam(':imgPath', $keyname);
                            $statement->execute();
                               
                        }else{
                            $statement = $this->conn->query("INSERT INTO user_info(user_id, profile_image) 
                            VALUES(:uid, :img);");
                            $statement->bindParam(':uid', $uid);
                            $statement->bindParam(':img', $keyname);

                            if($statement->execute()){
                                $statement = $this->conn->query("UPDATE users
                                SET profile_image=:imgPath
                                WHERE id = :uid");

                                $statement->bindParam(':uid', $uid);
                                $statement->bindParam(':imgPath', $keyname);
                                $statement->execute();
                            }
                        }
                    }

                    $statement = $this->conn->query("INSERT INTO profile_image(image_link, user_id) 
                    VALUES(:imgPath, :uid);");
                    $statement->bindParam(':uid', $uid);
                    $statement->bindParam(':imgPath', $keyname);
                    $statement->execute();
                    }
                }
            }
        

        public function uploadFoodPostGallery($uid, $keyname){

            $this->FoodName = $_SESSION['food_name'];

            $statement = $this->conn->query("SELECT id FROM food_post WHERE user_id=:uid AND food_name=:fname AND post_completed=0");
            $statement->bindParam(':uid', $uid);
            $statement->bindParam(':fname', $this->FoodName);

            if($statement->execute()){
                if($statement->rowCount() > 0){

                    $data = $statement->fetch();
                    $statement = $this->conn->query("INSERT INTO food_image_gallery(user_id, food_id, image_link)
                    VALUES(:uid,:fid,:imgurl)");

                    $statement->bindParam(':uid', $uid);
                    $statement->bindParam(':fid', $data['id']);
                    $statement->bindParam(':imgurl', $keyname);
                    
                    if($statement->execute()){
                        $statement = $this->conn->query("UPDATE food_post SET post_completed=1 
                        WHERE user_id=:uid AND food_name=:fname AND post_completed=0");

                        $statement->bindParam(':uid', $uid);
                        $statement->bindParam(':fname', $this->FoodName);
                        $statement->execute();
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return $statement->error;
            }
        }

    }