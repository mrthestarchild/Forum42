<?php
require_once( API_CONFIG );
require_once( SVC_DELETE_COMMENT );

class DeleteComment
{
    private $token;
    private $commentId;
    private $userId;
    private $userToken;

    public function __construct(string $token, string $userToken, int $commentId, int $userId){
        $this->token = $token;
        $this->userToken = $userToken;
        $this->commentId = $commentId;
        $this->userId = $userId;
    }

    // this function acts as a handshake between the front end and dao passing the data from the front to the back
    // it creates an instance of the DeleteCommentController sets it's properties and prints the ResponseObject to the
    // page for the front end to pick up
    public function deleteComment()
    {
        $deleteComment = new DeleteCommentController($this->token, $this->userToken , $this->commentId, $this->userId );
        $req_result = $deleteComment->deleteComment();
        print(json_encode($req_result));
    }
}
?>