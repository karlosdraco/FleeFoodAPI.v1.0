<?php
   require_once './model/signup_user.model.php';

    class signup_user{
        
        //**************************************************/
        public function signup(){
            $signup = new signup_user_model();

            $data = json_decode(file_get_contents("php://input"));
            $signup->firstname = $data->firstname;
            $signup->lastname = $data->lastname;
            $signup->email = $data->email;
            $signup->password = $data->password;
            $signup->contact = $data->contact;

            if($signup->firstname == "" || $signup->lastname == "" || $signup->email == "" || $signup->password == "" || $signup->contact == ""){
                echo json_encode(
                    array(
                        'error' => 'Cannot leave form empty',
                        'errorFlag' => true
                    ));
            }else if(!preg_match("/^[a-zA-Z]*$/", $signup->firstname) || !preg_match("/^[a-zA-Z]*$/", $signup->lastname)){
                echo json_encode(
                    array(
                        'error' => 'Invalid name',
                        'errorFlag' => true
                    ));
            }else if(!preg_match("/^[0-9]*$/", $signup->contact)){
                echo json_encode(
                    array(
                        'error' => 'Invalid contact',
                        'errorFlag' => true
                    ));
            }else if (!filter_var($signup->email, FILTER_VALIDATE_EMAIL)){
                echo json_encode(
                    array(
                        'error' => 'Invalid email',
                        'errorFlag' => true
                    ));
            }

            else{
                if($signup->create()){
                    echo json_encode(array('message' => 'Account created'));
                    http_response_code(201);
                }else if(!$signup->create()){
                    echo json_encode(array(
                        'error' => 'Email already exist',
                        'errorFlag' => true
                    ));
                    return false;
                }
    
                else{
                    echo json_encode(array('message' => 'Account not created'));
                    http_response_code(405);
                }
            }
        }
        //*************************************************/


        /*public function fetch_user_data(){
            $fetch = new user_model();
            echo json_encode($fetch->read());
        }

        public function fetch_single_data(){
            $fetch = new user_model();
            if(isset($_GET['id'])){
                if($fetch->read_single($_GET['id'])){
                    echo json_encode($fetch->read_single($_GET['id']));
                }

                else{
                    echo json_encode(array('message' => 'Invalid User'));
                }
               
            }else{
                echo json_encode(array('message' => 'Page not found'));
                http_response_code(404);
            }
        }*/
    }