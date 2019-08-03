<?php
declare(strict_types = 1);
require_once( API_CONFIG );
require_once( SVC_UPDATE_COMMENT_POINTS_CTL );

class UpdateCommentPoints
{
    private $token;
    private $commentId;
    private $userId;
    private $points;
    private $userToken;

    public function __construct(string $token, string $userToken, int $commentId, int $userId, int $points){
        $this->token = $token;
        $this->commentId = $commentId;
        $this->userId = $userId;
        $this->points = $points;
        $this->userToken = $userToken;
    }

    // this function acts as a handshake between the front end and dao passing the data from the front to the back
    // it creates an instance of the UpdateCommentPointsController sets it's properties and prints the ResponseObject to the
    // page for the front end to pick up
    public function updateScore()
    {
        $getUpdatedPoints = new UpdateCommentPointsController($this->token, $this->userToken , $this->commentId, $this->userId, $this->points);
        $req_result = $getUpdatedPoints->updateScore();
        print(json_encode($req_result));
    }
}
?>