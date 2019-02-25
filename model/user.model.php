<?php

    require_once './config/DB.php';
    require_once './classes/__PASSWORD_HASH__.php';

    class user_model{

       public $conn;
       public $firstname;
       public $lastname;
       public $email;
       public $password;
       public $contact;

       function __construct()
       {
          $this->conn = new DB();
       }

       public function create(){

         $hashedPass = PASS_HASH::hash_pass($this->password);

          $statement = $this->conn->query("INSERT INTO users(firstname, lastname, user_password, email, contact) 
          VALUES(:firstname, :lastname, :pass, :email, :contact);");

          $statement->bindParam(':firstname', $this->firstname);
          $statement->bindParam(':lastname', $this->lastname);
          $statement->bindParam(':pass', $hashedPass);
          $statement->bindParam(':email', $this->email);
          $statement->bindParam(':contact', $this->contact);
          $statement->execute();

          return true;
       }

       public function read(){
         $statement = $this->conn->query("SELECT firstname, lastname, email, contact FROM users");
           if($statement->execute()){
               return $statement->fetchAll(PDO::FETCH_ASSOC);
           }
       }

       public function read_single($id){
         $statement = $this->conn->query("SELECT firstname, lastname, email, contact FROM users WHERE id=:id");
         $statement->bindParam(':id', $id);
           if($statement->execute()){
               if($statement->rowCount() > 0){
                  return $statement->fetchAll(PDO::FETCH_ASSOC);
               }else{
                  return http_response_code(204);;
                  
               }
           }else{
               printf("Error: %s. \n", $statement->error);
           }
       }

       public function update($id){
         $statement = $this->conn->query("UPDATE firstname, lastname, email, contact FROM users WHERE id=:id");
       }
      
    }