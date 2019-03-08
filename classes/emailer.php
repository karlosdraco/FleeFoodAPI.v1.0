<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require './config/PHPMailer-master/src/PHPMailer.php';
    require './config/PHPMailer-master/src/Exception.php';
    require_once './config/DB.php';

    class Mailer{

        public $vkey;

        public function verification_key($email){
            $conn = new DB();
            $str = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789';
            $this->vkey = md5(time().$str);

            $statement = $conn->query("SELECT id FROM users WHERE email=:email");
            $statement->bindParam(':email', $email);
            
            if($statement->execute()){
                if($statement->rowCount() > 0){
                    $row = $statement->fetch();
                    $statement = $conn->query("INSERT INTO verificationcode(verification, user_id) VALUES(:vkey, :id)");
                    $statement->bindParam(':vkey', $this->vkey);
                    $statement->bindParam(':id', $row['id']);
                    $statement->execute();
                }
            }else{
                $statement->error;
            }

        }

        public function emailer($email, $firstname){
          
            /*$mail = new PHPMailer(true);

            try{
                $mail->setFrom('fleefood.com');
                $mail->addAddress($email, $firstname);
                $mail->Subject = "FleeFood email verification";
                $mail->isHTML(true);
                $mail->body = "Hi ".$firstname." Welcome to <strong>FleeFood</strong> please click the button
                to verify your email <button><a href='http://localhost/fleefood.v1/verification.php?email_verified=$this->vkey'>Verify email</a></button>";
            }catch (Exception $e)
            {
               
               echo $e->errorMessage();
            }
            catch (\Exception $e)
            {
             
               echo $e->getMessage();
            }*/
            $from = 'FleeFood <fleefood@admin.com>';
            $to = $firstname." <".$email.">";
            $subject = 'Email Verification';
            $message = "Hi ".$firstname." Welcome to <strong>FleeFood</strong> please click the button
            to verify your email <button><a href='http://localhost/fleefood.v1/verification.php?email_verified=$this->vkey'>Verify email</a></button>";
            $headers = 'From: ' . $from;
 
                if (!mail($to, $subject, $message, $headers)){
                    echo "Error.";
                }
                else{
                    echo "Message sent.";
                }
         
        }


    }