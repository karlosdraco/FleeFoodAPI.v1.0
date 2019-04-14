<?php

    require './config/AWS/aws-autoloader.php';
    
    use Aws\S3\S3Client;
	use Aws\S3\Exception\S3Exception;
    
    class S3Upload{

        public function __construct($filepath, $foldername, $subfolder)
        {
            $config = require_once './config/S3-config.php';
            try {
                // You may need to change the region. It will say in the URL when the bucket is open
                // and on creation. us-east-2 is Ohio, us-east-1 is North Virgina
                $s3 = S3Client::factory(
                    array(
                        'credentials' => array(
                            'key' => 'AKIAITRG65T333RI44RQ',
                            'secret' => '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'
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

            $fileURL = $filepath;
            $keyName = $foldername.'/'.$subfolder.'/'.basename($fileURL);
            $pathInS3 = 'https://s3.us-east-2.amazonaws.com/' . $config['s3']['bucket'] . '/' . $keyName;

            try{
                if(!file_exists('./tmps/tmpfile')){
                    mkdir('./tmps/tmpfile');
                }

                $tempFilePath = './tmps/tmpfile/'.basename($fileURL);
                $tempFile = fopen($tempFilePath, "w") or die("Error: Unable to open file");
                $fileContents = file_get_contents($fileURL);
                $tempFile = file_put_contents($tempFilePath, $fileContents);

                $s3->putObject(
                    array(
                        'Bucket' => 'fleefood-user-images',
                        'Key' => $keyName,
                        'SourceFile' => $tempFilePath,
                        'StorageClass' => 'REDUCED_REDUNDANCY'
                    )
                );

                

            }catch(S3Exception $e){
                die('Error:' . $e->getMessage());
            }catch(Exception $e){
                die('Error:' . $e->getMessage());
            }

          
        }
    }