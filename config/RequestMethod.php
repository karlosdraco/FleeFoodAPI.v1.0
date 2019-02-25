<?php

    class RequestMethod{

        public function get($url, $function){
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                if($_GET['url'] == $url){
                    $function->__invoke();
                }
            }
        }

        public function post($url, $function){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if($_GET['url'] == $url){
                    $function->__invoke();
                }
            }
        }
    }