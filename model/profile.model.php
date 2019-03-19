<?php

    require_once './config/DB.php';

    class Profile{

        public $conn;

        public function __construct(){
            $this->conn = new DB();
        }

        public function create(){

        }

        public function read($id){
            $statement = $this->conn->query("SELECT firstname,lastname,email,contact FROM users WHERE id=:id");
            $statement->bindParam(':id', $id);
            if($statement->execute()){
                if($statement->rowCount() > 0){
                    return $statement->fetchAll(PDO::FETCH_ASSOC);
                }
                else{
                    return false;
                }
            }else{
                return false;
            }
        }


    }