<?php 

    require_once './config/DB.php';

    class login_model{

        public $conn;

        function __construct()
        {
           $this->conn = new DB();
        }

        public function login($email, $password){
            
            $statement = $this->conn->query("SELECT id, user_password FROM users WHERE email='$email'");
            
            if($statement->execute()){
                if($statement->rowCount() > 0){
                    $data = $statement->fetch();
                    if(password_verify($password, $data['user_password'])){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    echo json_encode(array('message' => 'No records'));
                }
            }
        }
    }