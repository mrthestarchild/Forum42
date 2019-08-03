<?php

require_once( WEB_CTLS_BASE );

class LoginController extends BaseWebController
{
    private $username;
    private $password;
    private $email;

    public function __construct(){
        parent::__construct();
        if(isset($_POST['username'])){
            $this->username = $_POST['username'];
        }
        if(isset($_POST['password'])){
            $this->password = $_POST['password'];
        }
        $this->email = '';
    }

    // this function wraps a users login info into a request and passes it to be authorized. 
    // then it returns a string value that is a success message for the end user and refreshes the page
    // it returns a message to the end user on failure.
    public function login() : string
    {

        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'username'          => $this->username,
                'password'          => $this->password,
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
        $result = file_get_contents($this->config['endPoint'] .'LoginUser', true, $context);

        $data = json_decode($result, true);
        if($data['statusCode'] == 'SUCCESS'){
            $_SESSION['userInfo'] = json_encode($data['data']);
            return"<meta http-equiv='refresh' content='0'>";
        }
        else if($data['statusCode'] == 'INVALID_USERNAME' || $data['statusCode'] == 'INVALID_PASSWORD'){
            return "either the username or password is incorrect, please try logging on again.";
        }
        else if($data['statusCode'] == 'INVALID_TOKEN'){
            return "there was a problem with your request please contact system administrator.";
        }
        else{
            //TODO throw error with end user message
            return "fail";
        }
    }
}
?>