<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    require './config/PHPMailer-master/src/PHPMailer.php';
    require './config/PHPMailer-master/src/Exception.php';
    require './config/PHPMailer-master/src/SMTP.php';
    require_once './config/DB.php';

    class Mailer{

        private $vkey;

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
            $mail = new PHPMailer(true);
            $verificationLink = "http://localhost/fleefood_API/user_email_verification?email=$email&email_verification_key=$this->vkey";
            $emailContent = "
            <div style='width: 800px; background-color: #ffe228; padding: 5px; margin-bottom: 20px;'><h3 style='color: #ffffff; font-size: 30px; margin: 10px;'>FleeFood</h3></div>
            <div style='width: 800px; font-family: helvetica; font-size: 15px'>
            Hi ".$firstname." Welcome to <strong>FleeFood</strong> please click the link
            to verify your email <a style='color:  #57baf3;' 
            href='http://localhost/fleefood_API/user_email_verification?email=$email&email_verification_key=$this->vkey'>
            $verificationLink</a>
            </div>
            ";


            try{
                $mail->isSMTP();
                $mail->SMTPAuth = true;
                $mail->Host = 'smtp.gmail.com';
                $mail->Username = "ninjacowfilms@gmail.com";
                $mail->Password = "ghostphisher17";
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';
                $mail->setFrom('ninjacowfilms@gmail.com', 'FleeFood');
                $mail->addAddress($email, $firstname);
                $mail->Subject = "FleeFood email verification";
                $mail->isHTML(true);
                $mail->Body = $emailContent;
                $mail->send();
            }catch (Exception $e)
            {
               echo $e->errorMessage();
            }
            catch (\Exception $e)
            {
               echo $e->getMessage();
            }
        }


    }