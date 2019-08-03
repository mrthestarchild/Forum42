<?php

class GetAccountInfoController extends BaseWebController
{
    private $userToken;
    private $userId;

    // check to see what is captured in the post and set it to local variables
    public function __construct(){
        parent::__construct();
        if(isset($_SESSION['userInfo'])){
            $data = json_decode($_SESSION['userInfo'], true);
        }
        $this->userToken = $data['authToken'];
        $this->userId = intval($data['userId']);
    }

    // this function retrieves a series of rows that have to do with the end users profile
    // including their forums, threads and comments that were created by the user
    // then it returns a array value that contains data for the end user to view on a success.
    // it returns a message to the end user on failure.
    public function getAccountInfo() : array
    {
    
        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'userToken'         => $this->userToken,
                'userId'            => $this->userId,
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
        $result = file_get_contents($this->config['endPoint'] .'GetAccountInfoList', true, $context);

        $data = json_decode($result, true);
        if($data['statusCode'] == 'SUCCESS'){
            //TODO create message or change page locations
            return $data;
        }
        else if($data['statusCode'] == 'INVALID_TOKEN'){
            return ["message" => "there was a problem with your request please contact system administrator."];
        }
        else{
            //TODO throw error with end user message
            return ["message" => "there was a error processing your request please contact system administrator."];
        }
    }
}
?>