<?php

    require_once './model/logout.model.php';

    class logOut{

        public function logout(){
            $logout = new logoutModel();

                if($logout->logout()){
                    setcookie("auth_token", '', time() - 60 * 60 * 24 * 7, '/');
                    setcookie("auth_token_", '1', time() - 60 * 60 * 24 * 3, '/');
                    http_response_code(200);
                    return json_encode(
                        array(
                            "message" => 'Logged out',
                            "error" => false
                        )
                    );
                }else{
                    echo json_encode(
                        array(
                            'message' => 'Cannot execute operations',
                            'error' => true
                        ));
                        http_response_code(404);
                }
           
        }
    }