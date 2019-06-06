<?php

    require_once './config/AWS/aws-autoloader.php';
    use Aws\S3\S3Client;
	use Aws\S3\Exception\S3Exception;
    use function GuzzleHttp\json_encode;

class S3Upload{
        public $imgUrl;
        public $response;
        public $errorUploadResponse;

        public function __construct($foldername, $subfolder)
        {
            $config = require_once './config/S3-config.php';
            $bucketName = $config['s3']['bucket'];
            $key = $config['s3']['key'];
            $secret = $config['s3']['secret'];
            try {
                // You may need to change the region. It will say in the URL when the bucket is open
                // and on creation. us-east-2 is Ohio, us-east-1 is North Virgina
                $s3 = S3Client::factory(
                    array(
                        'credentials' => array(
                            'key' => $key,
                            'secret' => $secret
                        ),
                        'version' => 'latest',
                        'region'  => 'ap-southeast-1'
                    )
                );
            } catch (Exception $e) {
                // We use a die, so if this fails. It stops here. Typically this is a REST call so this would
                // return a json object.
                die("Error: " . $e->getMessage());
            }
               
                $image = basename($_FILES["file"]['name']);
                $keyName = $foldername.'/'.$subfolder.'/'. $image;
                $pathInS3 = 's3-ap-southeast-1.amazonaws.com/' . $bucketName . '/' . $keyName;

                $file_extension = pathinfo($_FILES["file"]['name'], PATHINFO_EXTENSION);

                $allowed_image_extension = array(
                    "png",
                    "jpg",
                    "jpeg"
                );

                if(!in_array($file_extension, $allowed_image_extension)){
                    $this->errorUploadResponse = 0;
                    $this->response = array(
                        'message' => "Error invalid file type",
                        'message_code' => $this->errorUploadResponse,
                        'msgColor' => '#ce2626'
                    );
                }else if($_FILES["file"]['size'] > 5000000){
                    $this->errorUploadResponse = 0;
                    $this->response = array(
                        'message' => "Maximum image size is 5mb",
                        'message_code' => $this->errorUploadResponse,
                        'msgColor' => '#ce2626'
                    );
                }else{
                    $this->errorUploadResponse = 1;
                    try {
                        // Uploaded:
                        $file = $_FILES["file"]['tmp_name'];
                        $s3->putObject(
                            array(
                                'Bucket'=>$bucketName,
                                'Key' =>  $keyName,
                                'SourceFile' => $file,
                                'StorageClass' => 'REDUCED_REDUNDANCY'
                            )
                        );
                    
                    } catch (S3Exception $e) {
                        die('Error:' . $e->getMessage());
                    } catch (Exception $e) {
                        die('Error:' . $e->getMessage());
                    }
                    try {
                        // Get the object.
                        $this->imgUrl = $s3->getObjectUrl($bucketName, $keyName);
                        // Display the object in the browser.
                        echo json_encode(
                            $this->response = array(
                                'message' => 'Finished. Post success image uploaded',
                                'message_code' => $this->errorUploadResponse,
                                'msgColor'=> '#44d809'
                            )
                        );
                       
                    } catch (S3Exception $e) {
                        echo $e->getMessage() . PHP_EOL;
                    }
        }
    }
}