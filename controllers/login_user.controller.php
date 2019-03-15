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
                setcookie("SNID", $login->token, time() + 60 * 60 * 24 * 7, '/');
                setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/');
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
    }