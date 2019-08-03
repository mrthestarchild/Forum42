<?php

class CreateForumModel
{
    private $forum;
    private $token;
    private $userId;
    private $userToken;
    private $forumName;
    private $forumDesc;
    private $forumIcon;

    public function __construct(string $token, int $userId, string $userToken,string $forumName, string $forumDesc, string $forumIcon){
        if(isset($_SESSION['userInfo'])){
            $userInfo = json_decode($_SESSION['userInfo'], true);
        }   
        $this->token = $token;
        $this->userId = intval($userInfo['userId']);
        $this->userToken = $userInfo['authToken'];
        $this->forumName = $forumName;
        $this->forumDesc = $forumDesc;
        $this->forumIcon = $forumIcon;
        $this->forum = new Forum($this->token, $this->userId, $this->userToken, $this->forumName, $this->forumDesc, $this->forumIcon);
    }
}

?>