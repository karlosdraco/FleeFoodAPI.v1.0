<?php
    class PASS_HASH{
        public static function hash_pass($password){
            return password_hash($password, PASSWORD_DEFAULT);
        }
    }