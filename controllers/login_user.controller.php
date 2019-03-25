<?php 

    require_once "./model/login_user.model.php";

    class login_user{
        
        public static function login(){
            
            $login = new login_model();
            $data = json_decode(file_get_contents("php://input"));

            if($login->login($data->email,  $data->password)){

                echo json_encode(array(
                    'message' => 'Logged in',
                    'authenticated' => true
                ));
              
                //echo '{"Token":'.$login->token.' }';
                setcookie("auth_token", $login->token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, FALSE);
                setcookie("auth_token_", '1', time() + 60 * 60 * 24 * 3, '/');
            }

            else if($login->notValidated){
                echo json_encode(array(
                    'message' => 'Please verify your email to login',
                    'authenticated' => false
                ));

            }else{
                echo json_encode(array(
                    'message' => 'Invalid email or password',
                    'authenticated' => false
                ));
            }
        }

        public function isLoggedIn(){
            $isVerified = new login_model();
            $uid = $isVerified->verify_token();
            
            if($uid){
                return $uid;
            }else{
                http_response_code(401);
            }
        }
    }