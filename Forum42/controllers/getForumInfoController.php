<?php

require_once( WEB_CTLS_BASE );

class GetForumInfoController extends BaseWebController
{
    public $getForumName;
    public $response;
    public $userId;

    public function __construct(){
        parent::__construct();
        $this->getForumName = strval($_SERVER["QUERY_STRING"]) == null ? " " : strval($_SERVER["QUERY_STRING"]);
    }

    // this function retrieves a series of rows that have to do with the forum the user is viewing
    // then it returns a array value that contains data for the end user to view on a success.
    // it returns a message to the end user on failure.
    public function getForumList() : array
    {
        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'getForumName'      => $this->getForumName
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
        $result = file_get_contents($this->config['endPoint'] .'GetForumList', true, $context);

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
    public function getAllForums() : array
    {
        // $getAPI = new GetForumInfoList($this->config['app-token'], $this->getForumName);
        // $result = $getAPI->getAllForums();

        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'getForumName'      => $this->getForumName
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
        $result = file_get_contents($this->config['endPoint'] .'GetAllForums', true, $context);

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
    public function getAllThreadsInForum() : array
    {
        if(isset($_SESSION['userInfo'])){
            $data = json_decode($_SESSION['userInfo'], true);
            $userId = $data['userId'];
        }
        else{
            $userId = 0;
        }
        // $getAPI = new GetForumInfoList($this->config['app-token'], $this->getForumName);
        // $result = $getAPI->getAllThreadsInForum($userId);

        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'getForumName'      => $this->getForumName,
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
        $result = file_get_contents($this->config['endPoint'] .'GetAllThreadsInForum', true, $context);

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
    public function getTopThreads()
    {
        if(isset($_SESSION['userInfo'])){
            $data = json_decode($_SESSION['userInfo'], true);
            $userId = $data['userId'];
        }
        else{
            $userId = 0;
        }

        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'getForumName'      => "",
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
        $result = file_get_contents($this->config['endPoint'] .'GetTopThreadsForSite', true, $context);

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