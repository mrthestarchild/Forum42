<?php declare(strict_types = 1); 
require_once( API_CONFIG );
require_once( SVC_THREAD_CTL );

class GetThreadInfoList
{
    private $token;
    private $threadId;
    private $getThread;

    // this series of functions act as a handshake between the front end and dao passing the data from the front to the back
    // it creates an instance of the ThreadController sets it's properties and prints the ResponseObject to the
    // page for the front end to pick up
    public function __construct(string $token, int $threadId){
        $this->token = $token;
        $this->threadId = $threadId;
        $this->getThread = new ThreadController( $this->token, $this->threadId );
    }
    public function getThread(int $userId)
    {
        $req_result = $this->getThread->getThreadById($userId);
        print(json_encode($req_result));
    }
    public function getAllCommentsInThread(int $userId)
    {
        $req_result = $this->getThread->getAllCommentsInThread($userId);
        print(json_encode($req_result));
    }
}
?>