<?php

class CreateAccountController extends BaseWebController
{
    private $username;
    private $password;
    private $hash;
    private $email;

    // check to see what is captured in the post and set it to local variables
    public function __construct(){
        parent::__construct();
        if(isset($_POST['username'])){
            $this->username = $_POST['username'];
        }
        if(isset($_POST['password'])){
            $this->password = $_POST['password'];
            $this->hash = password_hash($this->password, PASSWORD_DEFAULT);
        }
        if(isset($_POST['email'])){
            $this->email = $_POST['email'];
        }
    }
    // this function adds a row to the user table with contents submited by the end user
    // and creates an account then it returns a string value that is a message for the end user.
    public function createAccount() : string
    {
        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'username'          => $this->username,
                'password'          => $this->hash,
                'email'             => $this->email
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $result = file_get_contents($this->config['endPoint'] .'CreateUser', true, $context);
        $data = json_decode($result, true);
        // check status code and return message accordingly
        if($data['statusCode'] == 'SUCCESS'){
            return "you have created an account.";
        }
        else if($data['statusCode'] == 'DUPLICATE_USERNAME'){
            return "that username is already in use.";
        }
        else if($data['statusCode'] == 'DUPLICATE_EMAIL'){
            return "that email is already in use.";
        }
        else if($data['statusCode'] == 'INVALID_TOKEN'){
            return "there was a problem with your request please contact system administrator.";
        }
        else{
            return "there was a problem with your request please contact system administrator.";
        }
    }
}
?>