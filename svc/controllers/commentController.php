<?php declare(strict_types = 1); 
require_once( API_CONFIG );
require_once( SVC_BASE_MODEL );

class CommentController extends BaseModel
{   
    public $token;
    public $commentId;

    public function __construct( string $token, int $commentId ){
        parent::__construct();
        $this->token = $token;
        $this->commentId = $commentId;
    }

    public function getCommentByUserId(int $userId) : ResponseModel
    {
        // check token to make sure it is valid before we run query.
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }
        try{
            $query = $this->dao->prepare("SELECT * FROM comment WHERE comment_id = :commentId");
            $query->bindParam(':commentId', $this->commentId);
            $query->execute();
            $commentData = $query->fetch();
            if($userId != 0){
                $getPoints = $this->dao->prepare("SELECT SUM(comment_points_score) as comment_points_total FROM comment_points WHERE comment_id = :commentId");
                $getPoints->bindParam(':commentId', $this->commentId);
                $getPoints->execute();
                $points = $getPoints->fetch();
                $getUserPoints = $this->dao->prepare("SELECT user_id as user_points_id, thread_points_score as user_points FROM thread_points WHERE thread_id = :threadId AND user_id = :userId");
                $getUserPoints->bindParam(':commentId', $this->commentId);
                $getUserPoints->bindParam(':userId', $userId);
                $getUserPoints->execute();
                $userPoints = $getUserPoints->fetch();
                $commentData['comment_points_total'] = $points['comment_points_total'];
                $commentData['user_points_id'] = $userPoints['user_points_id'];
                $commentData['user_points'] = $userPoints['user_points'];
            }
            else{
                $getPoints = $this->dao->prepare("SELECT SUM(comment_points_score) as comment_points_total FROM comment_points WHERE comment_id = :commentId");
                $getPoints->bindParam(':commentId', $this->commentId);
                $getPoints->execute();
                $points = $getPoints->fetch();
                $commentData['comment_points_total'] = $points['comment_points_total'];
            }
            $response = new ResponseModel('SUCCESS',"You successfully retrieved data from $this->threadId.", 200, $commentData);
            return $response;
        }
        catch(PDOException $e){
            $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - threadController - starting at line 26',500);
            error_log($e->getMessage(), 0);
        }
    }
    public function getAllCommentsInThread(int $userId)
    {
        // check token to make sure it is valid before we run query.
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }
        try{
            $query = $this->dao->prepare("SELECT * FROM comment WHERE thread_id = :threadId");
            $query->bindParam(':threadId', $this->threadId);
            $query->execute();
            $check_num = $query->rowCount();
            $rows = $query->fetchAll();
            $commentData = [];
            if($userId != 0){
                foreach ($rows as $row) {
                    $getCommentLink = $this->dao->prepare("SELECT * FROM comment_link WHERE comment_link_child = :commentId");
                    $getCommentLink->bindParam(':commentId', $row['comment_id']);
                    $getCommentLink->execute();
                    $commentLink = $getCommentLink->fetch();
                    $getPoints = $this->dao->prepare("SELECT SUM(comment_points_score) as comment_points_total FROM comment_points WHERE comment_id = :commentId");
                    $getPoints->bindParam(':commentId', $row['comment_id']);
                    $getPoints->execute();
                    $points = $getPoints->fetch();
                    $getUserPoints = $this->dao->prepare("SELECT user_id as user_points_id, comment_points_score as user_points FROM comment_points WHERE comment_id = :commentId AND user_id = :userId");
                    $getUserPoints->bindParam(':commentId', $row['comment_id']);
                    $getUserPoints->bindParam(':userId', $userId);
                    $getUserPoints->execute();
                    $userPoints = $getUserPoints->fetch();
                    $row['comment_link_root'] = $commentLink['comment_link_root'];
                    $row['comment_link_parent'] = $commentLink['comment_link_parent'];
                    $row['comment_points_total'] = $points['comment_points_total'];
                    $row['user_points_id'] = $userPoints['user_points_id'];
                    $row['user_points'] = $userPoints['user_points'];
                    array_push($commentData, $row);
                }
            }
            else{
                foreach ($rows as $row) {
                    $getCommentLink = $this->dao->prepare("SELECT * FROM comment_link WHERE comment_link_child = :commentId");
                    $getCommentLink->bindParam(':commentId', $row['comment_id']);
                    $getCommentLink->execute();
                    $commentLink = $getCommentLink->fetch();
                    $getPoints = $this->dao->prepare("SELECT SUM(comment_points_score) as comment_points_total FROM thread_points WHERE comment_id = :commentId");
                    $getPoints->bindParam(':commentId', $row['comment_id']);
                    $getPoints->execute();
                    $points = $getPoints->fetch();
                    $row['comment_link_root'] = $commentLink['comment_link_root'];
                    $row['comment_link_parent'] = $commentLink['comment_link_parent'];
                    $row['comment_points_total'] = $points['comment_points_total'];
                    array_push($commentData, $row);
                }
            }
            if($check_num > 0){
                $response = new ResponseModel('SUCCESS',"You successfully retrieved all comment from the thread", 200, $commentData);
                return $response;
            }
            if($check_num == 0){
                $response = new ResponseModel('SUCCESS',"You successfully retrieved data from $this->threadId but it was empty.", 200);
                return $response;
            }
        }
        catch(PDOException $e){
            $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - threadController - starting at line 80',500);
            error_log($e->getMessage(), 0);
        }
    }
}

?>