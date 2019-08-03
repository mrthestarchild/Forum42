<?php declare(strict_types = 1); 
require_once( API_CONFIG );
require_once( SVC_BASE_MODEL );

class DeleteForumController extends BaseModel
{
    public $token;
    public $userToken;
    public $forumId;
    public $userId;

    public function __construct(string $token, string $userToken ,int $forumId, int $userId){
        parent::__construct();
        $this->token = $token;
        $this->userToken = $userToken;
        $this->forumId = $forumId;
        $this->userId = $userId;
    }

    public function deleteForum() : ResponseModel
    {
        // we check the app token to ensure valid request
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }

        // if this user id is valid
        $check = $this->checkUserToken($this->userToken, $this->userId);
        if($check){
            $response = new ResponseModel('INVALID_USER','The person making the request is not the valid user.', 401);
            return $response;
        }

         // insert points into table comment_points table
         // begin tran
         if($this->dao->beginTransaction()){
            // select all from comment points that are from this user to see if we have an exsisting row for them.
            try{
                $updateForum = $this->dao->prepare("UPDATE forum SET is_active = 0 WHERE forum_id = :forumId AND user_id = :userId");
                $updateForum->bindParam(':forumId', $this->forumId);
                $updateForum->bindParam(':userId', $this->userId);
                $updateForum->execute();
                if ($this->dao->inTransaction()){
                    $this->dao->commit();
                    $response = new ResponseModel('SUCCESS','The forum has been updated in the database.',200);
                    return $response;
                }  
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                }  
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - updatepointsController - starting at line 60',500);
                error_log($e->getMessage(), 0);
                return $response;
            }
            
        }
        
    }
}


?>