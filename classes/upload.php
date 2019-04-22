<?php
    class uploadImg{

        public function uploadIMG(){

            if(isset($_FILES["file"]['name'])){
                echo $_FILES["file"]['name'];
            }
            
            

        }
    }
