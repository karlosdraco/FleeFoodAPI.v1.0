<?php
    class DB{

        private $host = "localhost";
        private $username = "root";
        private $pass = "";
        private $db = "fleefood";
        private $conn;
        //private $query;
       
          public function connect(){

            try{
                $this->conn = new PDO('mysql:host='.$this->host.';dbname='.$this->db,$this->username,$this->pass);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
                echo "Connection Error".$e->getMessage();
            }
                return $this->conn;
          }

          public function query($query){
              return $this->connect()->prepare($query);
          }




    }