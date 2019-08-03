<?php

class CreateThreadController extends BaseWebController
{
    private $threadTitle;
    private $threadBody;
    private $threadURL;
    private $userId;
    private $userToken;

    public function __construct(){
        parent::__construct();
        if(isset($_POST['threadTitle'])){
            $this->threadTitle = $_POST['threadTitle'];
        }
        if(isset($_POST['threadBody'])){
            $this->threadBody = $_POST['threadBody'];
        }
        if(isset($_POST['threadURL'])){
            $this->threadURL = $_POST['threadURL'];
        }
        else{
            $this->threadURL = '';
        }
        if(isset($_SESSION['userInfo'])){
            $userInfo = json_decode($_SESSION['userInfo'], true);
        }
        $this->userId = intval($userInfo['userId']);
        $this->userToken = $userInfo['authToken'];
    }

    // this function adds a row to the thread table with contents submited by the end user
    // then it returns a string value that is a message for the end user or on success refreshes
    // the page and moves the uploaded photo to a folder on the server.
    public function createThread(string $forumName, string $threadPhoto) : string
    {
        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'userId'            => $this->userId,
                'userToken'         => $this->userToken,
                'forumName'         => $forumName,
                'threadTitle'       => $this->threadTitle,
                'threadBody'        => $this->threadBody,
                'threadPhoto'       => $threadPhoto,
                'threadURL'         => $this->threadURL
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
        $result = file_get_contents($this->config['endPoint'] .'CreateThread', true, $context);
        $data = json_decode($result, true);
        if($data['statusCode'] == 'SUCCESS'){
            move_uploaded_file($_FILES['threadPhoto']['tmp_name'], $threadPhoto);
            return "<meta http-equiv='refresh' content='0'>";
        }
        else if($data['statusCode'] == 'INVALID_USER'){
            return "there was a problem getting your user info, please log in and try again.";
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