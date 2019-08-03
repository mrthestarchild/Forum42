<?php
require_once( SVC_BASE_MODEL );

class CommentModel extends BaseModel
{   
    public $token;
    public $userToken;
    public $threadId;
    public $commentLinkRoot;
    public $commentLinkParent;
    public $commentLinkDepth;
    public $userId;
    public $commentBody;

    public function __construct(string $token, string $userToken, int $threadId, int $commentLinkRoot, int $commentLinkParent, int $commentLinkDepth, int $userId, string $commentBody){
        parent::__construct();
        $this->token = $token;
        $this->userToken = $userToken;
        $this->threadId = $threadId;
        $this->commentLinkRoot = $commentLinkRoot;
        $this->commentLinkParent = $commentLinkParent;
        $this->commentLinkDepth = $commentLinkDepth;
        $this->userId = $userId;
        $this->commentBody = $commentBody;
    }

    public function createComment() : ResponseModel
    {
        // check token to make sure it is valid before we run query.
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 403);
            return $response;
        }

        // check user token to make sure they are logged in before we run query.
        $check = $this->checkUserToken($this->userToken, $this->userId);
        if($check){
            $response = new ResponseModel('INVALID_USER','The person making the request is not the valid user.', 403);
            return $response;
        }

        // insert forum comment into comment table
        if($this->dao->beginTransaction()){
            try{
                
                $insertComment = $this->dao->prepare("INSERT INTO comment (thread_id, user_id, comment_body) VALUES(:threadId, :userId, :commentBody)");
                $insertComment->bindParam(':threadId', $this->threadId);
                $insertComment->bindParam(':userId', $this->userId);
                $insertComment->bindParam(':commentBody', $this->commentBody);
                $insertComment->execute();
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                }  
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into comment - comment - starting at line 41',500);
                error_log($e->getMessage(), 0);
                return $response;
            }
            try{
                $getCommentId = $this->dao->prepare("SELECT * FROM comment WHERE thread_id = :threadId AND user_id = :userId AND comment_body = :commentBody");
                $getCommentId->bindParam(':threadId', $this->threadId);
                $getCommentId->bindParam(':userId', $this->userId);
                $getCommentId->bindParam(':commentBody', $this->commentBody);
                $getCommentId->execute();
                $commentId = $getCommentId->fetch();
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                }
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into comment - comment - starting at line 56',500);
                error_log($e->getMessage(), 0);
                return $response;
            }
            try{
                $insertCommentPoints = $this->dao->prepare("INSERT INTO comment_points (comment_id, user_id, comment_points_score) VALUES(:commentId, :userId, 1)");
                $insertCommentPoints->bindParam(':commentId', $commentId['comment_id']);
                $insertCommentPoints->bindParam(':userId', $this->userId);
                $insertCommentPoints->execute();
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                }  
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into comment - comment - starting at line 41',500);
                error_log($e->getMessage(), 0);
                return $response;
            }
            if($this->commentLinkRoot == 0){
                $this->commentLinkRoot = $commentId['comment_id'];
            }
            if($this->commentLinkParent == 0){
                $this->commentLinkParent = $commentId['comment_id'];
            }
            try{
                $insertLink = $this->dao->prepare("INSERT INTO comment_link (comment_link_root, comment_link_parent, comment_link_child, comment_link_depth) VALUES(:commentLinkRoot, :commentLinkParent, :commentId, :commentLinkDepth)");
                $insertLink->bindParam(':commentLinkRoot', $this->commentLinkRoot);
                $insertLink->bindParam(':commentLinkParent', $this->commentLinkParent);
                $insertLink->bindParam(':commentLinkDepth', $this->commentLinkDepth);
                $insertLink->bindParam(':commentId', $commentId['comment_id']);
                $insertLink->execute();
                if ($this->dao->inTransaction()){
                    $this->dao->commit();
                    $response = new ResponseModel('SUCCESS','The comment has been added to the database.',200);
                    return $response;
                }  
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                } 
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - forumModel - starting at line 83',500);
                error_log($e->getMessage(), 0);
                return $response;
            }
        }
    }
}
?>