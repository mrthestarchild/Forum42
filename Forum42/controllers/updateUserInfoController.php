<?php
require_once( WEB_CTLS_BASE );

class UpdateUserInfoController extends BaseWebController
{
    private $userId;
    private $oldPassword;
    private $newPassword;
    private $email;
    private $userToken;

    public function __construct(){
        parent::__construct();
        if(isset($_POST['oldPassword'])){
            $this->oldPassword = password_hash($_POST['oldPassword'], PASSWORD_DEFAULT);        
        }
        if(isset($_POST['newPassword'])){
            $this->newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);;
        }
        if(isset($_POST['email'])){
            $this->email = $_POST['email'];
        }
        if(isset($_SESSION['userInfo'])){
            $userInfo = json_decode($_SESSION['userInfo'], true);
        }
        $this->userToken = $userInfo['authToken'];
        $this->userId = intval($userInfo['userId']);
    }

    // DOESN'T WORK AS EXPECTED
    // this function sends out a request to check the passwords passed in and then 
    // returns a message and resets the end users password and session data with new information

    public function updatePassword() : string
    {
        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'authToken'         => $this->userToken,
                'userId'            => $this->userId,
                'oldPassword'       => $this->oldPassword,
                'newPassword'       => $this->newPassword,
                'email'             => ''
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
        $result = file_get_contents($this->config['endPoint'] .'UpdateUserPassword', true, $context);

        $data = json_decode($result, true);
        if($data['statusCode'] == 'SUCCESS'){
            $_SESSION['userInfo'] = json_encode($data['data']);
            return"You have succesfully updated your password";
        }
        else if($data['statusCode'] == 'INVALID_USERNAME' || $data['statusCode'] == 'INVALID_PASSWORD'){
            return "either the username or password is incorrect, please try logging on again.";
        }
        else if($data['statusCode'] == 'INVALID_TOKEN'){
            return "there was a problem with your request please contact system administrator.";
        }
        else{
            return "there was a problem with your request please contact system administrator.";
        }
    }

    // DOESN'T WORK AS EXPECTED
    // this function sends out a request to update the users email passed in and then 
    // returns a message and resets the end users password and session data with new information
    public function updateEmail() : string
    {
        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'authToken'         => $this->userToken,
                'userId'            => $this->userId,
                'oldPassword'       => '',
                'newPassword'       => '',
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
        $result = file_get_contents($this->config['endPoint'] .'UpdateUserEmail', true, $context);

        $data = json_decode($result, true);
        if($data['statusCode'] == 'SUCCESS'){
            $_SESSION['userInfo'] = json_encode($data['data']);
            return"You have succesfully updated your email";
        }
        else if($data['statusCode'] == 'INVALID_USERNAME' || $data['statusCode'] == 'INVALID_PASSWORD'){
            return "either the username or password is incorrect, please try logging on again.";
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