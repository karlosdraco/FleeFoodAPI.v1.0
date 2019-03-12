<?php
    require_once '../config/DB.php';

    $conn = new DB();

    if(isset($_GET['email_verification_key'])){
        //echo $_GET['email_verification_key'];
      $statement = $conn->query("SELECT verification FROM verificationcode WHERE verification=:vkey");
      $statement->bindParam(':vkey', $_GET['email_verification_key']);
      
      if($statement->execute()){
          if($statement->rowCount() > 0){
            if(isset($_GET['email'])){
                $statement = $conn->query("UPDATE users SET verified=:verified WHERE email=:email");
                $isVerified = 1;
                $statement->bindParam(':verified', $isVerified);
                $statement->bindParam(':email', $_GET['email']);
                
                if($statement->execute()){
                  $statement = $conn->query("DELETE FROM verificationcode WHERE verification=:vkey");
                  $statement->bindParam(':vkey', $_GET['email_verification_key']);
                  $statement->execute();
                }
            }
           
          }else{
            header('location: http://localhost/fleefood.v1');
          }
      }
    }else{
        echo "Invalid Verification key";
    }
    include 'verification.php';
?>

