<?php

class CreateCommentController extends BaseWebController
{
    private $commentBody;
    private $commentLinkRoot;
    private $commentLinkParent;
    private $commentLinkDepth;
    private $userToken;
    private $userId;

    // check to see what is captured in the post and set it to local variables
    public function __construct(){
        parent::__construct();
        if(isset($_POST['commentBody'])){
            $this->commentBody = $_POST['commentBody'];
        }
        if(isset($_POST['commentLinkRoot'])){
            $this->commentLinkRoot = $_POST['commentLinkRoot'];
        }
        if(isset($_POST['commentLinkParent'])){
            $this->commentLinkParent = $_POST['commentLinkParent'];
        }
        if(isset($_POST['commentLinkDepth'])){
            $this->commentLinkDepth = $_POST['commentLinkDepth'];
        }
        if(isset($_SESSION['userInfo'])){
            $userInfo = json_decode($_SESSION['userInfo'], true);
        }
        $this->userToken = $userInfo['authToken'];
        $this->userId = intval($userInfo['userId']);
    }
    // this function adds a row to the comment table with contents submited by the end user
    // then it returns a string value that is a message for the end user or on success refreshes
    // the page.
    public function createComment(int $threadId) : string
    {
        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'userToken'         => $this->userToken,
                'threadId'          => $threadId,
                'commentLinkRoot'   => $this->commentLinkRoot,
                'commentLinkParent' => $this->commentLinkParent,
                'commentLinkDepth'  => $this->commentLinkDepth,
                'userId'            => $this->userId,
                'commentBody'       => $this->commentBody
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
        $result = file_get_contents($this->config['endPoint'] .'CreateComment', true, $context);

        // get ResponseObject back from the SVC level and check the return
        $data = json_decode($result, true);
        // check status code and return message accordingly, on seccess we reload the page to show enduser there comment.
        if($data['statusCode'] == 'SUCCESS'){
            return"<meta http-equiv='refresh' content='0'>";
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