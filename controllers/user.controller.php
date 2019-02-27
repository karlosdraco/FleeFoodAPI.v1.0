<?php
   require_once './model/user.model.php';

    class user{
        
        public function signup(){
            $signup = new user_model();

            $data = json_decode(file_get_contents("php://input"));
            $signup->firstname = $data->firstname;
            $signup->lastname = $data->lastname;
            $signup->email = $data->email;
            $signup->password = $data->password;
            $signup->contact = $data->contact;

            if($signup->create()){
                echo json_encode(array('message' => 'Account created'));
                http_response_code(201);
            }

            else{
                echo json_encode(array('message' => 'Account not created'));
                http_response_code(405);
            }
        }

        public function fetch_user_data(){
            $fetch = new user_model();
            echo json_encode($fetch->read());
        }

        public function fetch_single_data(){
            $fetch = new user_model();
            if(isset($_GET['id'])){
                echo json_encode($fetch->read_single($_GET['id']));
            }else{
                echo json_encode(array('message' => 'Page not found'));
                http_response_code(404);
            }
        }
    }