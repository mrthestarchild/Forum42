<?php declare(strict_types = 1); 
require_once( API_CONFIG );
require_once( SVC_BASE_MODEL );

class AccountController extends BaseModel
{   
    public $token;
    public $userToken;
    public $userId;

    public function __construct( string $token, string $userToken, int $userId ){
        parent::__construct();
        $this->token = $token;
        $this->userToken = $userToken;
        $this->userId = $userId;
    }

    public function getAccountInfo() : ResponseModel
    {
        // check token to make sure it is valid before we run query.
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
        // set data to empty array, this is what we pass back 
        $data=[];
        // wrap dao call in a try catch
        try{
            $getForums = $this->dao->prepare("SELECT * FROM forum WHERE user_id = :userId");
            $getForums->bindParam(':userId', $this->userId);
            $getForums->execute();
            $forums = $getForums->fetchAll();
            // get all forums and set it to a key of forums and store it in the
            // data object
            $data['forums'] = $forums;
            $getThreads = $this->dao->prepare("SELECT * FROM thread WHERE user_id = :userId");
            $getThreads->bindParam(':userId', $this->userId);
            $getThreads->execute();
            $forumRows = $getThreads->fetchAll();
            $threadData = [];
            // get info for the forums
            foreach ($forumRows as $forumRow){
                $getPoints = $this->dao->prepare("SELECT SUM(thread_points_score) as thread_points_total FROM thread_points WHERE thread_id = :threadId");
                $getPoints->bindParam(':threadId', $forumRow['thread_id']);
                $getPoints->execute();
                $points = $getPoints->fetch();
                $forumRow['thread_points_total'] = $points['thread_points_total'];
                array_push($threadData, $forumRow);
            }
            // get all threads and set it to a key of threads and store it in the
            // data object
            $data['threads'] = $threadData;
            $getComments = $this->dao->prepare("SELECT * FROM comment WHERE user_id = :userId");
            $getComments->bindParam(':userId', $this->userId);
            $getComments->execute();
            $commentRows = $getComments->fetchAll();
            $commentData = [];
            // get info for the threads
            foreach ($commentRows as $commentRow){
                $getPoints = $this->dao->prepare("SELECT SUM(comment_points_score) as comment_points_total FROM comment_points WHERE comment_id = :commentId");
                $getPoints->bindParam(':commentId', $commentRow['comment_id']);
                $getPoints->execute();
                $points = $getPoints->fetch();
                $commentRow['comment_points_total'] = $points['comment_points_total'];
                array_push($commentData, $commentRow);
            }
            // get all comments and set it to a key of comments and store it in the
            // data object
            $data['comments'] = $commentData;
            $response = new ResponseModel('SUCCESS',"You successfully retrieved data from $this->userId.", 200, $data);
            return $response;
        }
        catch(PDOException $e){
            $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - threadController - starting at line 80',500);
            error_log($e->getMessage(), 0);
        }
    }
}

?>