<?php

class GetThreadInfoController extends BaseWebController
{
    public $threadId;
    public $response;

    public function __construct(){
        parent::__construct();
        $this->threadId = intval($_SERVER["QUERY_STRING"]);
        // $this->response = file_get_contents($this->config['baseURL'] ."getForumList");
    }

     // this function retrieves a series of rows that have to do with the thread the user is viewing
    // then it returns a array value that contains data for the end user to view on a success.
    // it returns a message to the end user on failure.
    public function getThreadInfo() : array
    {
        if(isset($_SESSION['userInfo'])){
            $data = json_decode($_SESSION['userInfo'], true);
            $userId = $data['userId'];
        }
        else{
            $userId = 0;
        }
        // $getAPI = new GetThreadInfoList($this->config['app-token'], $this->threadId);
        // $result = $getAPI->getThread($userId);

        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'threadId'          => $this->threadId,
                'userId'            => $userId
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
        $result = file_get_contents($this->config['endPoint'] .'GetThread', true, $context);

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
    public function getAllCommentsInThread() : array
    {
        if(isset($_SESSION['userInfo'])){
            $data = json_decode($_SESSION['userInfo'], true);
            $userId = $data['userId'];
        }
        else{
            $userId = 0;
        }
        // $getAPI = new GetThreadInfoList($this->config['app-token'], $this->threadId);
        // $result = $getAPI->getAllCommentsInThread($userId);

        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'threadId'          => $this->threadId,
                'userId'            => $userId
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
        $result = file_get_contents($this->config['endPoint'] .'GetAllCommentsInThread', true, $context);

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