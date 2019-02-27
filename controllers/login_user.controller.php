<?php 

    require_once "./model/login_user.model.php";

    class login_user{
        
        public function login(){
            $login = new login_model();

            $data = json_decode(file_get_contents("php://input"));
            
            if($login->login($data->email,  $data->password)){
                echo json_encode(array('message' => 'Logged in'));
            }

            else{
                echo json_encode(array('message' => 'Error logging in'));
                http_response_code(401);
            }
        }
    }