<?php 

    require_once "./model/login_user.model.php";

    class login_user{
        
        public static function login(){
            $login = new login_model();

            $data = json_decode(file_get_contents("php://input"));
            
            if($login->login($data->email,  $data->password)){
                //echo json_encode(array('message' => 'Logged in'));
                echo '{"Token":'.$login->token.' }';
                setcookie("SNID", $login->token, time() + 60 * 60 * 24 * 7, '/', NULL,NULL, true);
                setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/', NULL,NULL, true);
            }

            else{
                echo json_encode(array('message' => 'Incorrect email or password'));
                http_response_code(401);
            }
        }

        public static function isLoggedIn(){
            $login = new login_model();
            
            if($login->verify_token()){
                return true;
            }

            else{
                return false;
            }
            
        }

       
    
    }