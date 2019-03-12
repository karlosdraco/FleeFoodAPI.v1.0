<?php

    require_once './config/DB.php';
    require_once './classes/emailer.php';
    require_once './classes/__PASSWORD_HASH__.php';

    class signup_user_model{

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

       //**********CREATE**********//
       public function create(){

          $hashedPass = PASS_HASH::hash_pass($this->password);
          $emailer = new Mailer();
      
          $statement = $this->conn->query("SELECT email FROM users WHERE email=:email");
          $statement->bindParam(':email', $this->email);
          $statement->execute();

          if($statement->rowCount() > 0){
              return false;
          }

          else{
            $statement = $this->conn->query("INSERT INTO users(firstname, lastname, user_password, email, contact,verified) 
            VALUES(:firstname, :lastname, :pass, :email, :contact, :verified);");
            $defaultValue = 0;
  
            $statement->bindParam(':firstname', $this->firstname);
            $statement->bindParam(':lastname', $this->lastname);
            $statement->bindParam(':pass', $hashedPass);
            $statement->bindParam(':email', $this->email);
            $statement->bindParam(':contact', $this->contact);
            $statement->bindParam(':verified', $defaultValue);
            $statement->execute();
            
            //PASS EMAIL TO VERIFICATION KEY
            $emailer->verification_key($this->email);

            //PASS EMAIL AND FIRSTNAME TO EMAILER 
            $emailer->emailer($this->email, $this->firstname);

            return true;
          }

          
       }
        

        //**********READ ALL**********//
       public function read(){
         $statement = $this->conn->query("SELECT firstname, lastname, email, contact FROM users");
           if($statement->execute()){
               return $statement->fetchAll(PDO::FETCH_ASSOC);
           }
       }
       
       //**********READ SINGLE**********//
       public function read_single($id){
         $statement = $this->conn->query("SELECT firstname, lastname, email, contact FROM users WHERE id=:id");
         $statement->bindParam(':id', $id);
           if($statement->execute()){
               if($statement->rowCount() > 0){
                  return $statement->fetchAll(PDO::FETCH_ASSOC);
               }else{
                  return false;
                  
               }
           }else{
               printf("Error: %s. \n", $statement->error);
           }
       }
        //**********UPDATE**********//
       public function update($id){
         $statement = $this->conn->query("UPDATE users SET firstname=:fname,lastname=:lname,email=:email,contact=:contact WHERE id=:id");
       }
     
    }