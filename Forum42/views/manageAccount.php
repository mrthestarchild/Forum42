<?php

// creates a instance of the GetAccountInfoController class
// to send request and retrieve account information to display
// to the end user when the page is loaded
require_once( WEB_CTLS_GET_ACCOUNT_INFO );
require_once( WEB_CTLS_UPDATE_USER_INFO );

if(isset($_SESSION['userInfo'])){
    $userInfo = json_decode($_SESSION['userInfo'], true);
    if($userInfo['username'] != strval($_SERVER["QUERY_STRING"])){
        header("Location: /Forum42/404.php");
        exit;
    }
    $getAccountInfo = new GetAccountInfoController();
    $response = $getAccountInfo->getAccountInfo();
    $data = isset($response['data']) ? $response['data'] : [];
    $forumData = isset($data['forums']) ? $data['forums'] : [];
    $threadData = isset($data['threads']) ? $data['threads'] : [];
    $commentData = isset($data['comments']) ? $data['comments'] : [];
    $threadPointsTotal = 0;
    foreach($threadData as $threadPoints){
        $threadPointsTotal += $threadPoints['thread_points_total']; 
    }
    $commentPointsTotal = 0;
    foreach($commentData as $commentPoints){
        $commentPointsTotal += $commentPoints['comment_points_total']; 
    }
    $totalPoints = $threadPointsTotal + $commentPointsTotal;
}
else{
    header("Location: /Forum42/404.php");
    exit;
}
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateEmail'])){
    $create = new UpdateUserInfoController();
    $userInfo['email'] = $_POST['email'];
	$response = $create->updateEmail();
}
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updatePassword'])){
	$create = new UpdateUserInfoController();
	$response = $create->updatePassword();
}
$emailId = 'email';
$passwordId = 'password';
$forumsId = 'forums';
$threadsId = 'threads';
$commentsId = 'comments';

print("<div class='manage-account-wrapper'>");
    print("<div class='birthday-container'>");
        print("<p>Joined: " .$userInfo['parsedDate'] ."</p>");
    print("</div>");
    print("<div class='manage-account-header'>");
        print("<h1>" .$userInfo['username'] ."</h1>");
    print("</div>");
    print("<div class='manage-account-body'>");
        print("<div class='email-container'>");
            print("<div class='show-email show' id='show-email' onclick='showInput(" .json_encode($emailId) .")'>");
                print("<p>Email: " .$userInfo['email'] ."</p>");
                print("<i class='material-icons'>edit</i>");
            print("</div>");
            print("<div class='update-email-container' id='update-email'>");
                print("<i class='material-icons exit-email-update' onclick='showInput(" .json_encode($emailId) .")'>clear</i>");
                print("<form method='post'>");
                    print("<input type='hidden' name='updateEmail'>");
                    print("<i class='material-icons email-icon'>email</i>");
                    print("<input type='email' id='email' name='email' value=" .json_encode($userInfo['email'])  ." required>");
                    print("<input type=submit value='Update Email'>");
                print("</form>");
            print("</div>");
        print("</div>");
        print("<div class='total-points-container'>");
            print("<p>Points Total: $totalPoints</p>");
        print("</div>");
        print("<div class='password-container'>");
            print("<div class='show-password show' id='show-password' onclick='showInput(" .json_encode($passwordId) .")'>");
                print("<p>Update Password</p>");
                print("<i class='material-icons'>edit</i>");
            print("</div>");
            print("<div class='update-password-container' id='update-password'>");
                print("<i class='material-icons exit-password-update' onclick='showInput(" .json_encode($passwordId) .")'>clear</i>");
                print("<form method='post'>");
                    print("<input type='hidden' name='updatePassword'>");
                    print("<i class='material-icons password-icon'>lock</i>");
                    print("<input type='password' id='old-password' name='oldPassword' placeholder='Old Password' required>");
                    print("<i class='material-icons password-icon'>lock</i>");
                    print("<input type='password' id='new-password' name='newPassword' placeholder='New Password' required>");
                    print("<input type=submit value='Update Password'>");
                print("</form>");
            print("</div>");
        print("</div>");
    print("</div>");
    print("<div class='show-posts-body'>");
        print("<div class='user-tabs'>");
            print("<div class='tab' id='tab-forums' onclick='showContent(" .json_encode($forumsId) .")' >");
                print("<h2>Forums</h2>");
            print("</div>");
            print("<div class='tab selected' id='tab-threads' onclick='showContent(" .json_encode($threadsId) .")'>");
                print("<h2>Threads</h2>");
            print("</div>");
            print("<div class='tab' id='tab-comments' onclick='showContent(" .json_encode($commentsId) .")'>");
                print("<h2>Comments</h2>");
            print("</div>");
        print("</div>");
        print("<div class='user-forums-container hidden' id='forums'>");
            foreach ($forumData as $data) {
                print("<div class='user-forum'>");
                    print("<img alt='forum icon' class='forum-icon' src='" .$data["forum_icon"] ."'>");
                    print("<h1>". $data['forum_name']."</h1>");
                    print("<p>" .$data['forum_description'] ."</p>");
                print("</div>");
            }
            print("</div>");
            print("<div class='user-threads-container' id='threads'>");
                foreach($threadData as $data){
                    if($data['is_active'] == 0) continue;
                    $pointsId = "points" .$data['thread_id'];
                    $threadId = "thread" .$data['thread_id'];
                    $modalId = "modal" .$data['thread_id'];
                    $modalImgId = "modalImg" .$data['thread_id'];
                    $imgId = "img" .$data['thread_id'];
                    $closeId = "close" .$data['thread_id'];
                    print("<div class='thread-wrapper'>");
                    
                    print("<div class='thread-body-wrapper-user'>");
                        print("<div class='thread-votes'>");
                            print("<p id= " .json_encode($pointsId) .">".$data['thread_points_total'] ."</p>");    
                        print("</div>");
                        print("<div class='thread-info-user unselectable'>");
                            print("<div class='thread-title'>");
                                print("<h3>".$data['thread_title'] ."</h3>");
                            print("</div>");
                            print("<div class='thread-body'>");
                                print("<p>".$data['thread_body'] ."</p>");
                            print("</div>");
                        print("</div>");
                        if(strpos($data['thread_image'], '../') !== false){
                        print("<div class='thread-image' onclick='openPhoto(" .json_encode($modalId) ."," .json_encode($imgId) ."," .json_encode($modalImgId) ."," .json_encode($closeId) .")'>");
                            print("<img alt='user added image' src='".$data['thread_image'] ."' id=" .json_encode($imgId) .">");
                        print("</div>");
                            print("<div id=" .json_encode($modalId) ."class='modal'>");
                                print("<span class='close' id=" .json_encode($closeId) .">&times;</span>");
                                print("<img alt='enlarged user added image' class='modal-content' id=" .json_encode($modalImgId) .">");
                            print("</div>");
                        }
                        else if(strpos($data['thread_link'], 'http') !== false || strpos($data['thread_link'], 'www') !== false){
                            print("<div class='thread-image'>");
                            print("<a href='" .$data['thread_link'] ."' target='_blank'>");
                                print("<img alt='default image' src='../ThreadPhotos/default.png'>");
                            print("</a>");
                            print("</div>");
                        }
                        else{
                            print("<div class='thread-image'>");
                            print("</div>");
                        }
                    print("</div>");
                    print("</div>");
                }
            print("</div>");
        print("<div class='user-comments-container hidden' id='comments'>");
        foreach ($commentData as $comments) {
            $upDootParam = "updoot" .$comments['comment_id'];
            $downDootParam = "downdoot" .$comments['comment_id'];
            $pointsId = "points" .$comments['comment_id'];
            $commentId = "comment" .$comments['comment_id'];
            $commentBodyId = "commentBody" .$comments['comment_id'];
            $commentForumId = "createCommentForm" .$comments['comment_id'];
            $imgId = "img" .$comments['comment_id'];
            $closeId = "close" .$comments['comment_id'];
            print("<div class='comment-content-wrapper-manage'>");
                print("<div class='comment-content-container'>");
                    if($comments['comment_points_total'] == 1){
                        print("<h5 id =" .json_encode($pointsId) .">" .$comments['comment_points_total'] ." point</h5>");
                    }
                    else{
                        print("<h5 id =" .json_encode($pointsId) .">" .$comments['comment_points_total'] ." points</h5>");
                    }
                    print("<h5 class='date'>" .$comments['comment_date_created'] ."</h5>");
                    if($comments['is_active'] == 0){
                        print("<p>[ removed ]</p>");
                    }
                    else{
                        print("<p>" .$comments['comment_body'] ."</p>");
                    }
                    
                print("</div>");
            
                print("<div class='comment-footer'>");
                print("<div class='comment-votes'>");
                print("</div>");
                print("<div class='comment-button-container'>");
                    print("</div>");
                print("</div>");
                print("<div class='child-comment-container hideComment' id= " .json_encode($commentId) .">");
                    include( WEB_VIEW_CREATECOMMENT );
                print("</div>");
            print("</div>");
        }
        print("</div>");
    print("</div>");
print("</div>");

?>