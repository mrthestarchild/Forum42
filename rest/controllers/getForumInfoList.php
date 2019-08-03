<?php declare(strict_types = 1); 
require_once( API_CONFIG );
require_once( SVC_FORUM_CTL );

class GetForumInfoList
{
    private $token;
    private $forumName;
    private $getForums;

    // this series of functions act as a handshake between the front end and dao passing the data from the front to the back
    // it creates an instance of the ForumController sets it's properties and prints the ResponseObject to the
    // page for the front end to pick up
    public function __construct(string $token, string $forumName = ""){
        $this->token = $token;
        $this->forumName = $forumName;
        $this->getForums = new ForumController($this->token, $this->forumName);
    }
    public function getForumList()
    {
        $req_result = $this->getForums->getForumByName();
        print(json_encode($req_result));
    }
    public function getAllForums()
    {
        $req_result = $this->getForums->getAllForumsByName();
        print(json_encode($req_result));
    }
    public function getAllThreadsInForum(int &$userId)
    {
        $req_result = $this->getForums->getAllThreadsInForum($userId);
        print(json_encode($req_result));
    }
    public function getTopThreadsForSite(int &$userId)
    {
        $req_result = $this->getForums->getTopThreadsForSite($userId);
        print(json_encode($req_result));
    }
}
?>