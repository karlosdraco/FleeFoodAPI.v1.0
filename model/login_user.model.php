<?php 

    require_once './config/DB.php';
    require 'login_token.model.php';

    class login_model{

        public $conn;
        public $token;
        public $notValidated;
        public $noRecords;
        public $user_id;

        function __construct()
        {
           $this->conn = new DB();
        }

        //************LOGIN AUTHENTICATION WITH TOKEN**************/
        public function login($email, $password){

            $login_token = new login_token();

            $statement = $this->conn->query("SELECT id, user_password FROM users WHERE email='$email'");
          
            if($statement->execute()){
                if($statement->rowCount() > 0){
                    $data = $statement->fetch();
                    if(password_verify($password, $data['user_password'])){

                        $statement = $this->conn->query("SELECT verified FROM users WHERE email='$email'");
                        
                        if($statement->execute()){
                            $row = $statement->fetch();
                            
                            if($row['verified'] == 1){
                                $this->token = $login_token->token($this->conn, $data['id'], $this->token);
                                return true;
                            }else{
                                $this->notValidated = false;
                            }
                        }
                    }else{
                        return false;
                    }
                }else{
                   $this->noRecords = false;
                   return false;
                }
            }
        }
        //************LOGIN AUTHENTICATION WITH TOKEN**************/

        public function verify_token(){
            if(isset($_COOKIE['SNID'])){
        
                $statement = $this->conn->query("SELECT user_id FROM token WHERE token=:token");
                $hashed = sha1($_COOKIE['SNID']);
                $statement->bindParam(":token", $hashed);
                
                if($statement->execute()){
                    if($statement->rowCount() > 0){
                        $data = $statement->fetch();
                        $this->user_id = $data['user_id'];
                        return true;
                    }else{
                        return false;
                    }
                }
            }else{
                return false;
            }
        }


    
    }