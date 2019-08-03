<?php declare(strict_types = 1); 
require_once( API_CONFIG );
require_once( SVC_COMMENT_MODEL );

class CreateComment
{
    private $comment;
    private $token;
    private $userId;
    private $userToken;
    private $threadId;
    private $commentLinkRoot;
    private $commentLinkParent;
    private $commentLinkDepth;
    private $commentBody;

    public function __construct(string $token, string $userToken, int $threadId,  int $commentLinkRoot, int $commentLinkParent, int $commentLinkDepth, int $userId, string $commentBody){
        $this->token = $token;
        $this->userToken = $userToken;
        $this->userId = $userId;
        $this->threadId = $threadId;
        $this->commentLinkRoot = $commentLinkRoot;
        $this->commentLinkParent = $commentLinkParent;
        $this->commentLinkDepth = $commentLinkDepth;
        $this->commentBody = $commentBody;
        $this->comment = new CommentModel($this->token, $this->userToken, $this->threadId, $this->commentLinkRoot, $this->commentLinkParent, $this->commentLinkDepth, $this->userId, $this->commentBody);
    }

    // this function acts as a handshake between the front end and dao passing the data from the front to the back
    // it creates an instance of the CommentModel sets it's properties and prints the ResponseObject to the
    // page for the front end to pick up
    public function createComment()
    {
        $req_result = $this->comment->createComment();
        print(json_encode($req_result));
    }
}


?>