<?php
require_once( API_CONFIG );
require_once( SVC_DELETE_THREAD );

class DeleteThread
{
    private $token;
    private $threadId;
    private $userId;
    private $userToken;

    public function __construct(string $token, string $userToken, int $threadId, int $userId){
        $this->token = $token;
        $this->userToken = $userToken;
        $this->threadId = $threadId;
        $this->userId = $userId;
    }

    // this function acts as a handshake between the front end and dao passing the data from the front to the back
    // it creates an instance of the DeleteThreadController sets it's properties and prints the ResponseObject to the
    // page for the front end to pick up
    public function deleteThread()
    {
        $deleteThread = new DeleteThreadController($this->token, $this->userToken , $this->threadId, $this->userId );
        $req_result = $deleteThread->deleteThread();
        print(json_encode($req_result));
    }
}
?>