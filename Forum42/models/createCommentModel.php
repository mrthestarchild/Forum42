<?php
class CreateCommentModel
{
    private $comment;
    private $token;
    private $userId;
    private $userToken;
    private $threadId;
    private $commentLinkRoot;
    private $commentLinkParent;
    private $commentBody;

    public function __construct(string $token, int $userId, string $userToken, int $threadId,  int $commentLinkRoot, int $commentLinkParent, string $commentBody){
        $this->token = $token;
        $this->userToken = $userToken;
        $this->userId = $userId;
        $this->threadId = $threadId;
        $this->commentLinkRoot = $commentLinkRoot;
        $this->commentLinkParent = $commentLinkParent;
        $this->commentBody = $commentBody;
        $this->comment = new Comment($this->token, $this->userToken, $this->threadId, $this->commentLinkRoot, $this->commentLinkParent, $this->userId, $this->commentBody);
    }
}
?>