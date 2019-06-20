<?php
require_once "DB.php";

    class apiAccessToken{

        public $conn;

        public function __construct()
        {
            $this->conn = new DB();
        }

        public function verify_api_token($header_token){
            $pass = "atari26001977";
            $id = 1;
            
            $statement = $this->conn->query("SELECT access_token, password FROM access_token_api WHERE id=:id");
            $statement->bindParam(':id', $id);
            
            if($statement->execute()){
                if($statement->rowCount() > 0){
                    $data = $statement->fetch();
                    $passVerify = password_verify($pass, $data['password']);
                    $statement = $this->conn->query("SELECT access_token, password FROM access_token_api WHERE password=:pass AND access_token=:acc");
                    $statement->bindParam(':pass', $passVerify);
                    $statement->bindParam(':acc', $header_token);

                    if($statement->execute()){
                        if($statement->rowCount()){
                            return true;
                        }else{
                            return false;
                        }
                    }

                }
            }

        }

    }