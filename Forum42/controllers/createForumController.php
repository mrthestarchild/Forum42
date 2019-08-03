<?php

class CreateForumController extends BaseWebController
{
    private $forumName;
    private $forumDesc;
    private $userId;
    private $userToken;

    // check to see what is captured in the post and set it to local variables
    public function __construct(){
        parent::__construct();
        if(isset($_POST['forumName'])){
            $this->forumName = $_POST['forumName'];
        }
        if(isset($_POST['forumDesc'])){
            $this->forumDesc = $_POST['forumDesc'];
        }
        if(isset($_SESSION['userInfo'])){
            $userInfo = json_decode($_SESSION['userInfo'], true);
        }
        $this->userId = intval($userInfo['userId']);
        $this->userToken = $userInfo['authToken'];
    }
    // this function adds a row to the forum table with contents submited by the end user
    // then it returns a string value that is a message for the end user or on success refreshes
    // the page and moves the uploaded photo to a folder on the server.
    public function createForum(string $forumIcon) : string
    {
        $postdata = http_build_query(
            array(
                'token'     => $this->config['app-token'],
                'userId'    => $this->userId,
                'userToken' => $this->userToken,
                'forumName' => $this->forumName,
                'forumDesc' => $this->forumDesc,
                'forumIcon' => $forumIcon
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
        $result = file_get_contents($this->config['endPoint'] .'CreateForum', true, $context);
        $data = json_decode($result, true);

        // check status code and return message accordingly.
        if($data['statusCode'] == 'SUCCESS'){
            // upload file to directory on server.
            move_uploaded_file($_FILES['forumIcon']['tmp_name'], $forumIcon);
            return "<meta http-equiv='refresh' content='0'>";
        }
        else if($data['statusCode'] == 'DUPLICATE_FORUM_NAME'){
            return "that forum name is already in use please try another.";
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