<?php
require_once( SVC_BASE_MODEL );

class Thread extends BaseModel
{   
    public $token;
    public $userToken;
    public $userId;
    public $forumName;
    public $threadTitle;
    public $threadBody;
    public $threadPhoto;
    public $threadLink;

    public function __construct(string $token, int $userId, string $userToken, string $forumName, string $threadTitle, string $threadBody, string $threadPhoto, string $threadLink){
        parent::__construct();
        $this->token = $token;
        $this->userId = $userId;
        $this->userToken = $userToken;
        $this->forumName = $forumName;
        $this->threadTitle = $threadTitle;
        $this->threadBody = $threadBody;
        $this->threadPhoto = $threadPhoto;
        $this->threadLink = $threadLink;
    }

    public function createThread() : ResponseModel
    {
        // check token to make sure it is valid before we run query.
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }

        // check user token to make sure they are logged in before we run query.
        $check = $this->checkUserToken($this->userToken, $this->userId);
        if($check){
            $response = new ResponseModel('INVALID_USER','The person making the request is not the valid user.', 401);
            return $response;
        }
        // get forum id by name
        $forumId = $this->getIdByValue('forum', 'forum_name', $this->forumName, 'forum_id');
        if($forumId == 0){
            $response = new ResponseModel('INVALID_FORUM','There was a problem getting the forum id value', 401);
            return $response;
        }

        // insert forum thread into thread table
        if($this->dao->beginTransaction()){
            try{  
                $insertThread = $this->dao->prepare("INSERT INTO thread (forum_id, user_id, thread_title, thread_body, thread_image, thread_link) VALUES(:forumId, :userId, :threadTitle, :threadBody, :threadPhoto, :threadLink)");
                $insertThread->bindParam(':forumId', $forumId);
                $insertThread->bindParam(':userId', $this->userId);
                $insertThread->bindParam(':threadTitle',$this->threadTitle);
                $insertThread->bindParam(':threadBody', $this->threadBody);
                $insertThread->bindParam(':threadPhoto', $this->threadPhoto);
                $insertThread->bindParam(':threadLink', $this->threadLink);
                $insertThread->execute();
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                }  
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - threadModel - starting at line 50',500);
                error_log($e->getMessage(), 0);
                return $response;
            }
            try{
                $getThreadId = $this->dao->prepare("SELECT * FROM thread WHERE forum_id = :forumId AND user_id = :userId and thread_title = :threadTitle AND thread_body = :threadBody");
                $getThreadId->bindParam(':forumId', $forumId);
                $getThreadId->bindParam(':userId', $this->userId);
                $getThreadId->bindParam(':threadTitle',$this->threadTitle);
                $getThreadId->bindParam(':threadBody', $this->threadBody);
                $getThreadId->execute();
                $threadId = $getThreadId->fetch();
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                } 
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - threadModel - starting at line 67',500);
                error_log($e->getMessage(), 0);
                return $response;
            }
            try{
                $insertPoints = $this->dao->prepare("INSERT INTO thread_points (thread_id, user_id, thread_points_score) VALUES(:threadId, :userId, 1)");
                $insertPoints->bindParam(':threadId', $threadId['thread_id']);
                $insertPoints->bindParam(':userId', $this->userId);
                $insertPoints->execute();
                if ($this->dao->inTransaction()){
                    $this->dao->commit();
                    $response = new ResponseModel('SUCCESS','The thread has been added to the database.',200);
                    return $response;
                }  
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                } 
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - forumModel - starting at line 69',500);
                error_log($e->getMessage(), 0);
                return $response;
            }
        }
    }
}
?>