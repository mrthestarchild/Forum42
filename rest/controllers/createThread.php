<?php declare(strict_types = 1); 
require_once( API_CONFIG );
require_once( SVC_THREAD_MODEL );

class CreateThread
{
    private $thread;
    private $token;
    private $userId;
    private $userToken;
    private $forumName;
    private $threadTitle;
    private $threadBody;
    private $threadPhoto;
    private $threadLink;

    public function __construct(string $token, int $userId, string $userToken, string $forumName,  string $threadTitle, string $threadBody, string &$threadPhoto, string &$threadLink){
        $this->token = $token;
        $this->userId = $userId;
        $this->userToken = $userToken;
        $this->forumName = $forumName;
        $this->threadTitle = $threadTitle;
        $this->threadBody = $threadBody;
        $this->threadPhoto = $threadPhoto;
        $this->threadLink = $threadLink;
        $this->thread = new Thread($this->token, $this->userId, $this->userToken, $this->forumName, $this->threadTitle, $this->threadBody, $this->threadPhoto, $this->threadLink);
    }

    // this function acts as a handshake between the front end and dao passing the data from the front to the back
    // it creates an instance of the Thread sets it's properties and prints the ResponseObject to the
    // page for the front end to pick up
    public function createThread()
    {
        $req_result = $this->thread->createThread();
        print(json_encode($req_result));
    }
}


?>