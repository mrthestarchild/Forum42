<?php declare(strict_types = 1); 
require_once( API_CONFIG );
require_once( SVC_BASE_MODEL );

class ThreadController extends BaseModel
{   
    public $token;
    public $threadId;

    public function __construct( string $token, int $threadId ){
        parent::__construct();
        $this->token = $token;
        $this->threadId = $threadId;
    }

    public function getThreadById(int $userId) : ResponseModel
    {
        // check token to make sure it is valid before we run query.
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }
        try{
            $query = $this->dao->prepare("SELECT * FROM thread WHERE thread_id = :threadId");
            $query->bindParam(':threadId', $this->threadId);
            $query->execute();
            $threadData = $query->fetch();
            if($userId != 0){
                $getPoints = $this->dao->prepare("SELECT SUM(thread_points_score) as thread_points_total FROM thread_points WHERE thread_id = :threadId");
                $getPoints->bindParam(':threadId', $this->threadId);
                $getPoints->execute();
                $points = $getPoints->fetch();
                $getUserPoints = $this->dao->prepare("SELECT user_id as user_points_id, thread_points_score as user_points FROM thread_points WHERE thread_id = :threadId AND user_id = :userId");
                $getUserPoints->bindParam(':threadId', $this->threadId);
                $getUserPoints->bindParam(':userId', $userId);
                $getUserPoints->execute();
                $userPoints = $getUserPoints->fetch();
                $checkAdmin = $this->dao->prepare("SELECT * FROM admin WHERE forum_id = :forumId AND user_id = :userId");
                $checkAdmin->bindParam(':forumId', $threadData['forum_id']);
                $checkAdmin->bindParam(':userId', $userId);
                $checkAdmin->execute();
                $isAdmin = $checkAdmin->rowCount();
                $threadData['is_admin'] = $isAdmin > 0 ? 1 : 0;
                $threadData['thread_points_total'] = $points['thread_points_total'];
                $threadData['user_points_id'] = $userPoints['user_points_id'];
                $threadData['user_points'] = $userPoints['user_points'];
            }
            else{
                $getPoints = $this->dao->prepare("SELECT SUM(thread_points_score) as thread_points_total FROM thread_points WHERE thread_id = :threadId");
                $getPoints->bindParam(':threadId', $this->threadId);
                $getPoints->execute();
                $points = $getPoints->fetch();
                $threadData['thread_points_total'] = $points['thread_points_total'];
                array_push($threadData, $points);
            }
            $response = new ResponseModel('SUCCESS',"You successfully retrieved data from $this->threadId.", 200, $threadData);
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
            // $query = $this->dao->prepare("SELECT * FROM thread WHERE forum_id = :forumId");
            $query = $this->dao->prepare("SELECT * FROM comment WHERE thread_id = :threadId");
            $query->bindParam(':threadId', $this->threadId);
            $query->execute();
            $check_num = $query->rowCount();
            $rows = $query->fetchAll();
            $threadData = [];
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
                    $getUserName = $this->dao->prepare("SELECT user_username FROM user WHERE user_id = :userId");
                    $getUserName->bindParam(':userId', $row['user_id']);
                    $getUserName->execute();
                    $userName = $getUserName->fetch();
                    $getforumAdmin = $this->dao->prepare("SELECT a.user_id FROM thread t JOiN admin a ON a.forum_id = t.forum_id WHERE t.thread_id = :threadId");
                    $getforumAdmin->bindParam(':threadId', $this->threadId);
                    $getforumAdmin->execute();
                    $forumAdmin = $getforumAdmin->fetch();
                    $getSiteAdmin = $this->dao->prepare("SELECT user_id FROM admin WHERE user_type_id = 1");
                    $getSiteAdmin->execute();
                    $siteAdmin = $getSiteAdmin->fetch();
                    $date = $this->formatDateTime($row['comment_date_created']);
                    $row['comment_date_created'] = $date;
                    $row['comment_link_root'] = $commentLink['comment_link_root'];
                    $row['comment_link_parent'] = $commentLink['comment_link_parent'];
                    $row['comment_link_depth'] = $commentLink['comment_link_depth'];
                    $row['comment_points_total'] = $points['comment_points_total'];
                    $row['user_points_id'] = $userPoints['user_points_id'];
                    $row['user_points'] = $userPoints['user_points'];
                    $row['user_name'] = $userName['user_username'];
                    $row['forum_admin'] = $forumAdmin['user_id'];
                    $row['site_admin'] = $siteAdmin['user_id'];
                    array_push($threadData, $row);
                }
            }
            else{
                foreach ($rows as $row) {
                    $getCommentLink = $this->dao->prepare("SELECT * FROM comment_link WHERE comment_link_child = :commentId");
                    $getCommentLink->bindParam(':commentId', $row['comment_id']);
                    $getCommentLink->execute();
                    $commentLink = $getCommentLink->fetch();
                    $getPoints = $this->dao->prepare("SELECT SUM(comment_points_score) as comment_points_total FROM comment_points WHERE comment_id = :commentId");
                    $getPoints->bindParam(':commentId', $row['comment_id']);
                    $getPoints->execute();
                    $points = $getPoints->fetch();
                    $getUserName = $this->dao->prepare("SELECT user_username FROM user WHERE user_id = :userId");
                    $getUserName->bindParam(':userId', $row['user_id']);
                    $getUserName->execute();
                    $userName = $getUserName->fetch();
                    $getforumAdmin = $this->dao->prepare("SELECT a.user_id FROM thread t JOiN admin a ON a.forum_id = t.forum_id WHERE t.thread_id = :threadId");
                    $getforumAdmin->bindParam(':threadId', $this->threadId);
                    $getforumAdmin->execute();
                    $forumAdmin = $getforumAdmin->fetch();
                    $getSiteAdmin = $this->dao->prepare("SELECT user_id FROM admin WHERE user_type_id = 1");
                    $getSiteAdmin->execute();
                    $siteAdmin = $getSiteAdmin->fetch();
                    $date = $this->formatDateTime($row['comment_date_created']);
                    $row['comment_date_created'] = $date;
                    $row['comment_link_root'] = $commentLink['comment_link_root'];
                    $row['comment_link_parent'] = $commentLink['comment_link_parent'];
                    $row['comment_link_depth'] = $commentLink['comment_link_depth'];
                    $row['comment_points_total'] = $points['comment_points_total'];
                    $row['user_name'] = $userName['user_username'];
                    $row['forum_admin'] = $forumAdmin['user_id'];
                    $row['site_admin'] = $siteAdmin['user_id'];
                    array_push($threadData, $row);
                }
            }
            
            if($check_num > 0){
                $response = new ResponseModel('SUCCESS',"You successfully retrieved all comment from $this->threadId", 200, $threadData);
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