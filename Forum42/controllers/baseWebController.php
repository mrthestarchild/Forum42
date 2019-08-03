<?php

// The BaseWebController handles all base functionality that any web based controller may need to use.
class BaseWebController
{
    public $config;
    // get the config file to know what the app-token is, this is required for all requests.
    public function __construct(){
        $this->config = include( WEB_APP_CONFIG );
    }

    // checkFileUpload validates all file uploads for all forms that allow files. 
    public function checkFileUpload(string $fileValueName, string $targetUploadPath) : string
    {
        // check to see if there is a directory for this file path, if not create one.
        if(!file_exists($targetUploadPath)){
            mkdir($targetUploadPath);
        }
        // if the file name exsists in the upload we check some variables on the file 
        // otherwise we sent the return to an empty string
        if(basename($_FILES[$fileValueName]["name"]) != '' || basename($_FILES[$fileValueName]["name"]) != null){
            $target_dir = $targetUploadPath;
            // get the file path
            $target_file = $target_dir . basename($_FILES[$fileValueName]["name"]);
            // get the file type
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // get the file size
            $check = getimagesize($_FILES[$fileValueName]["tmp_name"]);
            // if size exsists check to make sure it isn't larger than 5mb
            if($check !== false) {
                if ($_FILES[$fileValueName]["size"] > 5000000) {
                    $uploadOk = 0;
                    return "Sorry, your file is too large.";
                }
            }
            // check file type and only allow jpg,jpeg,png and gif files.
            if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif"){
                $uploadOk = 0;
                return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
            // if the file name already exsists in the directory we give it a new name so there is no collision
            if(file_exists($target_file)){
                $temp = explode(".", $_FILES[$fileValueName]["name"]);
                $newfilename = round(microtime(true)) . '.' . end($temp);
                return $target_dir . $newfilename;
            }
            else{
                return $target_file;
            } 
        }
        else{
            $uploadOk = 0;
            return '';
        }
    }
}
?>