<?php
    class DB{

        private $host = "localhost";
        private $username = "id8829941_fleefoodapi";
        private $pass = "128roote9OU";
        private $db = "id8829941_fleefood";
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