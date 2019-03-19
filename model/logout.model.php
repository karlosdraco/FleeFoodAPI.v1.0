<?php
    require_once './config/DB.php';
    require_once 'model/login_user.model.php';

    class logoutModel{

        public $conn;

        public function __construct()
        {
            $this->conn = new DB();
        }

        public function logout(){
            $verifiedID = new login_model();
            $uid = $verifiedID->verify_token();

            $statement = $this->conn->query("DELETE FROM token WHERE user_id=:user_id");
            $statement->bindParam(':user_id', $uid);
            
            if($statement->execute()){
                return true;
            }else{
                return false;
            }
        }

    }