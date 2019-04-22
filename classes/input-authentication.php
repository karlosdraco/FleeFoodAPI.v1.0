<?php

    class inputAuthentication{

        public function isEmpty($input){
            if($input == ""){
                return false;
            }else{
                return true;
            }
        }

        public function allowedInput($input){

            if(!preg_match("/^[a-zA-Z_-]*$/", $input)){
                return false;
            }else{
                return true;
            }
        }

        public function sanitize($input){
            if($newInput = filter_var($input, FILTER_SANITIZE_STRING)){
                return $newInput;
            }else{
                return false;
            }
        }

        public function sanitizeEmail($email){
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                return false;
            }else{
                return true;
            }
        }

    }