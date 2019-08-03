<?php
require_once( API_CONFIG );
require_once( SVC_DELETE_FORUM );

class DeleteForum
{
    private $token;
    private $forumId;
    private $userId;
    private $userToken;

    public function __construct(string $token, string $userToken, int $forumId, int $userId){
        $this->token = $token;
        $this->userToken = $userToken;
        $this->forumId = $forumId;
        $this->userId = $userId;
    }

    // this function acts as a handshake between the front end and dao passing the data from the front to the back
    // it creates an instance of the DeleteForumController sets it's properties and prints the ResponseObject to the
    // page for the front end to pick up
    public function deleteForum()
    {
        $deleteForum = new DeleteForumController($this->token, $this->userToken , $this->forumId, $this->userId );
        $req_result = $deleteForum->deleteForum();
        print(json_encode($req_result));
    }
}
?>