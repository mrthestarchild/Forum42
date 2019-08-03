<?php
require_once( SVC_BASE_MODEL );

class Forum extends BaseModel
{   
    public $token;
    public $userToken;
    public $userId;
    public $forumName;
    public $forumDesc;
    public $forumIcon;

    public function __construct(string $token, int $userId, string $userToken, string $forumName, string $forumDesc, string $forumIcon){
        parent::__construct();
        $this->token = $token;
        $this->userId = $userId;
        $this->userToken = $userToken;
        $this->forumName = $forumName;
        $this->forumDesc = $forumDesc;
        $this->forumIcon = $forumIcon;
    }

    public function createNewForum() : ResponseModel
    {
        // check token to make sure it is valid before we run query.
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }

        $check = $this->checkUserToken($this->userToken, $this->userId);
        if($check){
            $response = new ResponseModel('INVALID_USER','The person making the request is not the valid user.', 401);
            return $response;
        }
        
        // check to make sure the forum name isn't already in exsistance before we run the insert
        $check = $this->checkForDuplicateValue('forum', 'forum_name', $this->forumName);
        if($check){
            $response = new ResponseModel('DUPLICATE_FORUM_NAME','The forum name is already in use.',409);
            return $response;
        }
        if($this->dao->beginTransaction()){
        // if forum name is available we insert new username
            try{
                $bindCheck = $this->dao->prepare("INSERT INTO forum (user_id, forum_name, forum_description, forum_icon) VALUES(:userId, :forumName, :forumDesc, :forumIcon)");
                $bindCheck->bindParam(':userId', $this->userId);
                $bindCheck->bindParam(':forumName',$this->forumName);
                $bindCheck->bindParam(':forumDesc', $this->forumDesc);
                $bindCheck->bindParam(':forumIcon', $this->forumIcon);
                $bindCheck->execute();
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                }  
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - forumModel - starting at line 47',500);
                error_log($e->getMessage(), 0);
            }
            try{
                $getForumId = $this->dao->prepare("SELECT forum_id FROM forum WHERE user_id = :userId AND forum_name = :forumName");
                $getForumId->bindParam(':userId', $this->userId);
                $getForumId->bindParam(':forumName', $this->forumName);
                $getForumId->execute();
                $forumId = $getForumId->fetch();
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                }  
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On select forum_id forum - forumModel - starting at line 60',500);
                error_log($e->getMessage(), 0);
            }
            try{
                $createForumAdmin = $this->dao->prepare("INSERT INTO admin (user_type_id, user_id, forum_id) VALUES( 2, :userId, :forumId)");
                $createForumAdmin->bindParam(':userId', $this->userId);
                $createForumAdmin->bindParam(':forumId', $forumId['forum_id']);
                $createForumAdmin->execute();

                if ($this->dao->inTransaction()){
                    $this->dao->commit();
                    $response = new ResponseModel('SUCCESS','The forum has been added to the database and you have been added to the admin table.',200);
                    return $response;
                }  
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                }  
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into admin - forumModel - starting at line 79',500);
                error_log($e->getMessage(), 0);
            }
        }
    }
}
?>