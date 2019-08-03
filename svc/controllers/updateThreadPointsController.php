<?php declare(strict_types = 1); 
require_once( API_CONFIG );
require_once( SVC_BASE_MODEL );

class UpdateThreadPointsController extends BaseModel
{
    public $token;
    public $userToken;
    public $threadId;
    public $userId;
    public $points;
    public $newpoints;

    public function __construct(string $token, string $userToken ,int $threadId, int $userId, int $points){
        parent::__construct();
        $this->token = $token;
        $this->threadId = $threadId;
        $this->userId = $userId;
        $this->userToken = $userToken;
        $this->points = $points; 
    }

    public function updateScore() : ResponseModel
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

         // insert points into table thread_points table
         // begin tran
         if($this->dao->beginTransaction()){
            // select all from thread points that are from this user to see if we have an exsisting row for them.
            try{
                $checkPoints = $this->dao->prepare("SELECT * FROM thread_points WHERE thread_id = :threadId AND user_id =:userId");
                $checkPoints->bindParam(':threadId', $this->threadId);
                $checkPoints->bindParam(':userId', $this->userId);
                $checkPoints->execute();
                $check_num = $checkPoints->rowCount();
                $pointsQuery = $checkPoints->fetch();
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                }  
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - updatePointsController - starting at line 42',500);
                error_log($e->getMessage(), 0);
                return $response;
            }
            // if the user already has points then we update the row.
            if($check_num > 0){
                $newPoints = $this->points + intval($pointsQuery['thread_points_score']);
                try{
                    $updatePoints = $this->dao->prepare("UPDATE thread_points SET thread_points_score = :threadPoints WHERE thread_id = :threadId AND user_id = :userId");
                    $updatePoints->bindParam(':threadPoints', $newPoints);
                    $updatePoints->bindParam(':threadId', $this->threadId);
                    $updatePoints->bindParam(':userId', $this->userId);
                    $updatePoints->execute();
                    if ($this->dao->inTransaction()){
                        $this->dao->commit();
                        $response = new ResponseModel('SUCCESS','The points have been updated in the database.',200);
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
            // if there is nothing for this user then we insert a new row into the database
            else if($check_num == 0){
                try{
                    $insertPoints = $this->dao->prepare("INSERT INTO thread_points (thread_id, user_id, thread_points_score) VALUES(:threadId, :userId, :points)");
                    $insertPoints->bindParam(':points', $this->points);
                    $insertPoints->bindParam(':threadId', $this->threadId);
                    $insertPoints->bindParam(':userId', $this->userId);
                    $insertPoints->execute();
                    if ($this->dao->inTransaction()){
                        $this->dao->commit();
                        $response = new ResponseModel('SUCCESS','The points have been added to the database.',200);
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
            else{
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                } 
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - UpdatePointsController - starting at line 58',500);
                error_log($e->getMessage(), 0);
                return $response;
            }
            
        }
        
    }
}


?>