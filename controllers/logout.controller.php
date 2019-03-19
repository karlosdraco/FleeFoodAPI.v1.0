<?php

    require_once './model/logout.model.php';

    class logOut{

        public function logout(){
            $logout = new logoutModel();

            if($logout->logout()){
                setcookie("SNID", '', time() - 60 * 60 * 24 * 7, '/');
            }else{
                echo json_encode(
                    array(
                        'message' => 'Cannot execute operations',
                        'error' => true
                    ));
            }
        }
    }