<?php

class CreateThreadModel
{
    public $token;
    public $userId;
    public $userToken;
    public $forumName;
    public $threadTitle;
    public $threadBody;
    public $threadPhoto;
    public $threadLink;

    public function __construct(string $token, int $userId, string $userToken, string $forumName,  string $threadTitle, string $threadBody, string $threadPhoto, string $threadLink){
        
        $this->token = $token;
        $this->userId = $userId;
        $this->userToken = $userToken;
        $this->forumName = $forumName;
        $this->threadTitle = $threadTitle;
        $this->threadBody = $threadBody;
        $this->threadPhoto = $threadPhoto;
        $this->threadLink = $threadLink;
    }  
}
?>