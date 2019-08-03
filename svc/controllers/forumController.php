<?php declare(strict_types = 1); 
require_once( API_CONFIG );
require_once( SVC_BASE_MODEL );

class ForumController extends BaseModel
{   
    public $token;
    public $forumName;

    public function __construct( string $token, string &$forumName ){
        parent::__construct();
        $this->token = $token;
        $this->forumName = $forumName;
    }

    public function getForumByName() : ResponseModel
    {
        // check token to make sure it is valid before we run query.
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }
        try{
            $query = $this->dao->prepare("SELECT * FROM forum WHERE forum_name = :forumName");
            $query->bindParam(':forumName', $this->forumName);
            $query->execute();
            $check_num = $query->rowCount();
            $forumData = $query->fetch();
            if($check_num > 0){
                $response = new ResponseModel('SUCCESS',"You successfully retrieved data from $this->forumName.", 200, $forumData);
                return $response;
            }
            if($check_num == 0){
                $response = new ResponseModel('SUCCESS',"You successfully retrieved data from $this->forumName but it was empty.", 200);
                return $response;
            }
        }
        catch(PDOException $e){
            $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - forumController - starting at line 26',500);
            error_log($e->getMessage(), 0);
        }
    }
    public function getAllForumsByName()
    {
        // check token to make sure it is valid before we run query.
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }
        try{
            $query = $this->dao->prepare("SELECT forum_name FROM forum WHERE is_active = 1");
            $query->execute();
            $check_num = $query->rowCount();
            $forumData = $query->fetchAll();
            if($check_num > 0){
                $response = new ResponseModel('SUCCESS',"You successfully retrieved all forums", 200, $forumData);
                return $response;
            }
            if($check_num == 0){
                $response = new ResponseModel('SUCCESS',"You successfully retrieved data from $this->forumName but it was empty.", 200);
                return $response;
            }
        }
        catch(PDOException $e){
            $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - forumController - starting at line 54',500);
            error_log($e->getMessage(), 0);
        }
    }
    public function getAllThreadsInForum(int &$userId)
    {
        // check token to make sure it is valid before we run query.
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }
        // get forum id by name
        $forumId = $this->getIdByValue('forum', 'forum_name', $this->forumName, 'forum_id');
        if($forumId == 0){
            $response = new ResponseModel('INVALID_FORUM','There was a problem getting the forum id value', 401);
            return $response;
        }
        try{
            $query = $this->dao->prepare("SELECT * FROM thread WHERE forum_id = :forumId");
            $query->bindParam(':forumId', $forumId);
            $query->execute();
            $check_num = $query->rowCount();
            $rows = $query->fetchAll();
            $checkAdmin = $this->dao->prepare("SELECT * FROM admin WHERE forum_id = :forumId AND user_id = :userId");
            $checkAdmin->bindParam(':forumId', $forumId);
            $checkAdmin->bindParam(':userId', $userId);
            $checkAdmin->execute();
            $isAdmin = $checkAdmin->rowCount();
            $forumData = [];
            if($userId != 0){
                foreach ($rows as $row) {
                    $getPoints = $this->dao->prepare("SELECT SUM(thread_points_score) as thread_points_total FROM thread_points WHERE thread_id = :threadId");
                    $getPoints->bindParam(':threadId', $row['thread_id']);
                    $getPoints->execute();
                    $points = $getPoints->fetch();
                    $getUserPoints = $this->dao->prepare("SELECT user_id as user_points_id, thread_points_score as user_points FROM thread_points WHERE thread_id = :threadId AND user_id = :userId");
                    $getUserPoints->bindParam(':threadId', $row['thread_id']);
                    $getUserPoints->bindParam(':userId', $userId);
                    $getUserPoints->execute();
                    $userPoints = $getUserPoints->fetch();
                    $getUserName = $this->dao->prepare("SELECT user_username FROM user WHERE user_id = :userId");
                    $getUserName->bindParam(':userId', $row['user_id']);
                    $getUserName->execute();
                    $userName = $getUserName->fetch();
                    $getForumAdmin = $this->dao->prepare("SELECT user_id FROM admin WHERE forum_id = :forumId");
                    $getForumAdmin->bindParam(':forumId', $forumId);
                    $getForumAdmin->execute();
                    $forumAdmin = $getForumAdmin->fetch();
                    $getSiteAdmin = $this->dao->prepare("SELECT user_id FROM admin WHERE user_type_id = 1");
                    $getSiteAdmin->execute();
                    $siteAdmin = $getSiteAdmin->fetch();
                    $date = $this->formatDateTime($row['thread_date_created']);
                    $row['thread_date_created'] = $date;
                    $row['is_admin'] = $isAdmin > 0 ? 1 : 0;
                    $row['thread_points_total'] = $points['thread_points_total'];
                    $row['user_points_id'] = $userPoints['user_points_id'];
                    $row['user_points'] = $userPoints['user_points'];
                    $row['forum_admin'] = $forumAdmin['user_id'];
                    $row['user_name'] = $userName['user_username'];
                    $row['site_admin'] = $siteAdmin['user_id'];
                    array_push($forumData, $row);
                }
            }
            else{
                foreach ($rows as $row) {
                    $getPoints = $this->dao->prepare("SELECT SUM(thread_points_score) as thread_points_total FROM thread_points WHERE thread_id = :threadId");
                    $getPoints->bindParam(':threadId', $row['thread_id']);
                    $getPoints->execute();
                    $points = $getPoints->fetch();
                    $getForumAdmin = $this->dao->prepare("SELECT user_id FROM admin WHERE forum_id = :forumId");
                    $getForumAdmin->bindParam(':forumId', $forumId);
                    $getForumAdmin->execute();
                    $forumAdmin = $getForumAdmin->fetch();
                    $getUserName = $this->dao->prepare("SELECT user_username FROM user WHERE user_id = :userId");
                    $getUserName->bindParam(':userId', $row['user_id']);
                    $getUserName->execute();
                    $userName = $getUserName->fetch();
                    $getSiteAdmin = $this->dao->prepare("SELECT user_id FROM admin WHERE user_type_id = 1");
                    $getSiteAdmin->execute();
                    $siteAdmin = $getSiteAdmin->fetch();
                    $date = $this->formatDateTime($row['thread_date_created']);
                    $row['thread_date_created'] = $date;
                    $row['thread_points_total'] = $points['thread_points_total'];
                    $row['forum_admin'] = $forumAdmin['user_id'];
                    $row['user_name'] = $userName['user_username'];
                    $row['site_admin'] = $siteAdmin['user_id'];
                    array_push($forumData, $row);
                }
            }
            if($check_num > 0){
                $response = new ResponseModel('SUCCESS',"You successfully retrieved all threads from the forum", 200, $forumData);
                return $response;
            }
            if($check_num == 0){
                $response = new ResponseModel('SUCCESS',"You successfully retrieved data from $this->forumName but it was empty.", 200);
                return $response;
            }
        }
        catch(PDOException $e){
            $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - forumController - starting at line 87',500);
            error_log($e->getMessage(), 0);
        }
    }
    public function getTopThreadsForSite(int &$userId)
    {
        // check token to make sure it is valid before we run query.
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }
        try{
            $query = $this->dao->prepare(
            "select t.thread_id, t.thread_title, t.thread_body, t.thread_image, t.thread_link, t.thread_date_created, t.is_active, t.user_id, SUM(tp.thread_points_score) as thread_points_total, f.forum_id, f.forum_name from thread t 
            join forum f on t.forum_id = f.forum_id
            join thread_points tp on t.thread_id = tp.thread_id
            GROUP BY t.thread_id
            ORDER BY thread_points_total DESC");
            $query->execute();
            $check_num = $query->rowCount();
            $rows = $query->fetchAll(); 
            $checkAdmin = $this->dao->prepare("SELECT * FROM admin WHERE forum_id = :forumId AND user_id = :userId");
            $checkAdmin->bindParam(':forumId', $forumId);
            $checkAdmin->bindParam(':userId', $userId);
            $checkAdmin->execute();
            $isAdmin = $checkAdmin->rowCount();
            $frontPage = [];
            if($userId != 0){
                foreach ($rows as $row) {
                    $getUserPoints = $this->dao->prepare("SELECT user_id as user_points_id, thread_points_score as user_points FROM thread_points WHERE thread_id = :threadId AND user_id = :userId");
                    $getUserPoints->bindParam(':threadId', $row['thread_id']);
                    $getUserPoints->bindParam(':userId', $userId);
                    $getUserPoints->execute();
                    $userPoints = $getUserPoints->fetch();
                    $getUserName = $this->dao->prepare("SELECT user_username FROM user WHERE user_id = :userId");
                    $getUserName->bindParam(':userId', $row['user_id']);
                    $getUserName->execute();
                    $userName = $getUserName->fetch();
                    $getForumAdmin = $this->dao->prepare("SELECT user_id FROM admin WHERE forum_id = :forumId");
                    $getForumAdmin->bindParam(':forumId', $row['forum_id']);
                    $getForumAdmin->execute();
                    $forumAdmin = $getForumAdmin->fetch();
                    $getSiteAdmin = $this->dao->prepare("SELECT user_id FROM admin WHERE user_type_id = 1");
                    $getSiteAdmin->execute();
                    $siteAdmin = $getSiteAdmin->fetch();
                    $date = $this->formatDateTime($row['thread_date_created']);
                    $row['thread_date_created'] = $date;
                    $row['is_admin'] = $isAdmin > 0 ? 1 : 0;
                    $row['user_points_id'] = $userPoints['user_points_id'];
                    $row['user_points'] = $userPoints['user_points'];
                    $row['forum_admin'] = $forumAdmin['user_id'];
                    $row['user_name'] = $userName['user_username'];
                    $row['site_admin'] = $siteAdmin['user_id'];
                    array_push($frontPage, $row);
                }
            }
            else{
                foreach ($rows as $row) {
                    $getForumAdmin = $this->dao->prepare("SELECT user_id FROM admin WHERE forum_id = :forumId");
                    $getForumAdmin->bindParam(':forumId', $row['forum_id']);
                    $getForumAdmin->execute();
                    $forumAdmin = $getForumAdmin->fetch();
                    $getUserName = $this->dao->prepare("SELECT user_username FROM user WHERE user_id = :userId");
                    $getUserName->bindParam(':userId', $row['user_id']);
                    $getUserName->execute();
                    $userName = $getUserName->fetch();
                    $getSiteAdmin = $this->dao->prepare("SELECT user_id FROM admin WHERE user_type_id = 1");
                    $getSiteAdmin->execute();
                    $siteAdmin = $getSiteAdmin->fetch();
                    $date = $this->formatDateTime($row['thread_date_created']);
                    $row['thread_date_created'] = $date;
                    $row['forum_admin'] = $forumAdmin['user_id'];
                    $row['user_name'] = $userName['user_username'];
                    $row['site_admin'] = $siteAdmin['user_id'];
                    array_push($frontPage, $row);
                }
            }
            if($check_num > 0){
                $response = new ResponseModel('SUCCESS',"You successfully retrieved all top threads from the site", 200, $frontPage);
                return $response;
            }
            if($check_num == 0){
                $response = new ResponseModel('SUCCESS',"You successfully retrieved data from the query but it was empty.", 200);
                return $response;
            }
        }
        catch(PDOException $e){
            $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - forumController - starting at line 87',500);
            error_log($e->getMessage(), 0);
        }
    }
}

?>