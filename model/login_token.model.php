<?php 

    class login_token{

        public function token($conn,$user_id, $token){
            
            $cstrong = true;
            $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));

            $statement = $conn->query("INSERT INTO token(token, user_id) VALUES(:token, :user_id)");
            $hashed = sha1($token);
                $statement->bindParam(':token', $hashed);
                $statement->bindParam(':user_id', $user_id);
                $statement->execute();
            return $token;
        }

    }