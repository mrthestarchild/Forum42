<?php declare(strict_types = 1); 
require_once( API_CONFIG );
require_once( SVC_FORUM_MODEL );

class CreateForum
{
    private $forum;
    private $token;
    private $userId;
    private $userToken;
    private $forumName;
    private $forumDesc;
    private $forumIcon;

    public function __construct(string $token, int $userId, string $userToken, string $forumName, string $forumDesc, string $forumIcon){
        $this->token = $token;
        $this->userId = $userId;
        $this->userToken = $userToken;
        $this->forumName = $forumName;
        $this->forumDesc = $forumDesc;
        $this->forumIcon = $forumIcon;
        $this->forum = new Forum($this->token, $this->userId, $this->userToken, $this->forumName, $this->forumDesc, $this->forumIcon);
    }

    // this function acts as a handshake between the front end and dao passing the data from the front to the back
    // it creates an instance of the Forum sets it's properties and prints the ResponseObject to the
    // page for the front end to pick up
    public function createForum()
    {
        $req_result = $this->forum->createNewForum();
        print(json_encode($req_result));
    }
}


?>