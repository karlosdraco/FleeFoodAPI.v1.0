<?php 

    require_once './config/DB.php';

    class login_model{

        public $conn;
        public $token;

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
                        $cstrong = true;
                        $this->token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));

                        $statement = $this->conn->query("INSERT INTO token(token, user_id) VALUES(:token, :user_id)");
                        $statement->bindParam(':token', $this->token);
                        $statement->bindParam(':user_id', $data['id']);
                        $statement->execute();
                        
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