<?php

// creates a instance of the GetThreadInfoController class
// to send request and retrieve thread information to display
// to the end user when the page is loaded
require_once( WEB_CTLS_GET_THREAD_INFO );
$getThread = new GetThreadInfoController();
$threadResponse = $getThread->getThreadInfo();
$threadInfo = $threadResponse['data'];
if(empty($threadInfo['forum_id']) || $threadInfo['is_active'] == 0){
    header("Location: /Forum42/404.php");
    exit;
}
$commentResponse = $getThread->getAllCommentsInThread();
$commentInfo = $commentResponse['data'];


?>
<div class="thread-wrapper">
    <div class="thread-header">
        <?php
        if(isset($_SESSION['userInfo'])){
            $userInfo = json_decode($_SESSION['userInfo'], true);
        }
        $upDootParam = "updoot" .$threadInfo['thread_id'];
        $downDootParam = "downdoot" .$threadInfo['thread_id'];
        $pointsId = "points" .$threadInfo['thread_id'];
        $threadId = "thread" .$threadInfo['thread_id'];
        $modalId = "modal" .$threadInfo['thread_id'];
        $modalImgId = "modalImg" .$threadInfo['thread_id'];
        $imgId = "img" .$threadInfo['thread_id'];
        $closeId = "close" .$threadInfo['thread_id'];
        $rootCommentId = "rootComment";
        print("<div class='thread-votes'>");
        if(isset($_SESSION['userInfo'])){
            if($threadInfo['user_points_id'] == $userInfo['userId'] && $threadInfo['user_points'] == 1){
                print("<i class='material-icons updoot upvote' id=".json_encode($upDootParam) ." onclick='higlightArrow(" .json_encode($upDootParam) .")' data-voted >expand_less</i>");
            }
            else{
                print("<i class='material-icons updoot' id=".json_encode($upDootParam) ." onclick='higlightArrow(" .json_encode($upDootParam) .")'>expand_less</i>");
            }
        }
        else{
            print("<i class='material-icons updoot' id=".json_encode($upDootParam) .">expand_less</i>");
        }
        print("<p id= " .json_encode($pointsId) .">".$threadInfo['thread_points_total'] ."</p>");
        if(isset($_SESSION['userInfo'])){
            if($threadInfo['user_points_id'] == $userInfo['userId'] && $threadInfo['user_points'] == -1){
                print("<i class='material-icons downdoot downvote' id=".json_encode($downDootParam) ." onclick='higlightArrow(" .json_encode($downDootParam) .")' data-voted >expand_more</i>");
            }
            else{
                print("<i class='material-icons downdoot' id=".json_encode($downDootParam) ." onclick='higlightArrow(" .json_encode($downDootParam) .")'>expand_more</i>");
            }
        }
        else{
            print("<i class='material-icons downdoot' id=".json_encode($downDootParam) .">expand_more</i>");
        }
        print("</div>");
        ?>
        <div class="thread-info-wrapper">
            <h1><?php print($threadInfo['thread_title']);?></h1>
            <p><?php print($threadInfo['thread_body']);?></p>
        </div>
        <div class="thread-image-wrapper">
            <?php
             if(strpos($threadInfo['thread_image'], '../') !== false){
                print("<div class='thread-image' onclick='openPhoto(" .json_encode($modalId) ."," .json_encode($imgId) ."," .json_encode($modalImgId) ."," .json_encode($closeId) .")'>");
                    print("<img alt='thread image' src='".$threadInfo['thread_image'] ."' id=" .json_encode($imgId) .">");
                print("</div>");
                print("<div id=" .json_encode($modalId) ."class='modal'>");
                    print("<span class='close' id=" .json_encode($closeId) .">&times;</span>");
                    print("<img alt='user input image' src='./img/empty.png' class='modal-content' id=" .json_encode($modalImgId) .">");
                print("</div>");
            }
            else if(strpos($threadInfo['thread_link'], 'http') !== false || strpos($threadInfo['thread_link'], 'www') !== false){
                print("<div class='thread-image'>");
                print("<a href='" .$threadInfo['thread_link'] ."' target='_blank'>");
                    print("<img alt='default image' src='../ThreadPhotos/default.png'>");
                print("</a>");
                print("</div>");
            }
            else{
                print("<div class='thread-image'>");
                print("</div>");
            }
            ?>
        </div>
    </div>
    <?php
        print("<div class='thread-header-footer'>");
            print("<div class='button-container'>");
                if(isset($_SESSION['userInfo'])){
                    print("<div class='show-comment-block' id='root' onclick='toggleComment(" .json_encode($rootCommentId) .")'>");
                        print("<p>Comment</p>");
                    print("</div>");
                    if($threadInfo['user_id'] == $userInfo['userId']|| $threadInfo['is_admin'] == 1){
                        print("<div class='show-comment-block' onclick='deleteThread(" .json_encode($threadId) .")'>");
                            print("<p>Delete</p>");
                        print("</div>");
                    }
                }
            print("</div>");
        print("</div>");
        print("<div class='root-comment-container hideComment' id='rootComment'>");
                require_once( WEB_VIEW_CREATEROOTCOMMENT );
        print("</div>");
    ?>
    <div class="thread-comments-body">
        <?php
        if(!empty($commentInfo)){
            
            foreach ($commentInfo as $comments) {
                $upDootParam = "updoot" .$comments['comment_id'];
                $downDootParam = "downdoot" .$comments['comment_id'];
                $pointsId = "points" .$comments['comment_id'];
                $commentId = "comment" .$comments['comment_id'];
                $commentBodyId = "commentBody" .$comments['comment_id'];
                $commentForumId = "createCommentForm" .$comments['comment_id'];
                $imgId = "img" .$comments['comment_id'];
                $closeId = "close" .$comments['comment_id'];
                $wrapper = "comment-content-wrapper depth-" .$comments['comment_link_depth'];
                print("<div class=" .json_encode($wrapper) .">");
                    print("<div class='comment-content-container'>");
                        if($comments['is_active'] == 0){
                            print("<h5>[ removed ]</h5>");
                        }
                        else if($comments['site_admin'] == $comments['user_id']){
                            print("<i class='material-icons admin-badge'>public</i>");
                            print("<h5 class='is-site-admin'>" .$comments['user_name'] ."</h5>");
                        }
                        else if($comments['forum_admin'] == $comments['user_id']){
                            print("<i class='material-icons admin-badge'>visibility</i>");
                            print("<h5 class='is-admin'>" .$comments['user_name'] ."</h5>");
                        }
                        else{
                            print("<h5>" .$comments['user_name'] ."</h5>");
                        }
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
                    if(isset($_SESSION['userInfo']) && $comments['is_active'] == 1){
                        if($comments['user_points_id'] == $userInfo['userId'] && $comments['user_points'] == 1){
                            print("<i class='material-icons updoot upvote' id=".json_encode($upDootParam) ." onclick='higlightCommentArrow(" .json_encode($upDootParam) .")' data-voted >expand_less</i>");
                        }
                        else{
                            print("<i class='material-icons updoot' id=".json_encode($upDootParam) ." onclick='higlightCommentArrow(" .json_encode($upDootParam) .")'>expand_less</i>");
                        }
                    }
                    else if($comments['is_active'] == 0){
                    }
                    else{
                        print("<i class='material-icons updoot' id=".json_encode($upDootParam) .">expand_less</i>");
                    }
                    if(isset($_SESSION['userInfo']) && $comments['is_active'] == 1){
                        if($comments['user_points_id'] == $userInfo['userId'] && $comments['user_points'] == -1){
                            print("<i class='material-icons downdoot downvote' id=".json_encode($downDootParam) ." onclick='higlightCommentArrow(" .json_encode($downDootParam) .")' data-voted >expand_more</i>");
                        }
                        else{
                            print("<i class='material-icons downdoot' id=".json_encode($downDootParam) ." onclick='higlightCommentArrow(" .json_encode($downDootParam) .")'>expand_more</i>");
                        }
                    }
                    else if($comments['is_active'] == 0){
                    }
                    else{
                        print("<i class='material-icons downdoot' id=".json_encode($downDootParam) .">expand_more</i>");
                    }
                    print("</div>");
                    print("<div class='comment-button-container'>");
                    if(isset($_SESSION['userInfo'])){
                        if($comments['comment_link_depth'] < 5){
                            print("<div class='show-comment-block' id=" .json_encode($closeId)  ." onclick='toggleComment(" .json_encode($commentId) .")'>");
                                print("<p>Comment</p>");
                            print("</div>");
                        }
                        // TODO make the ability to make the thread inactive.
                        if($comments['user_id'] == $userInfo['userId'] && $comments['is_active'] == 1 ||
                         $comments['forum_admin'] == $userInfo['userId'] && $comments['is_active'] == 1 ){
                            print("<div class='show-comment-block' onclick='deleteComment(" .json_encode($commentId) .")'>");
                                print("<p>Delete</p>");
                            print("</div>");
                        }
                    }
                        print("</div>");
                    print("</div>");
                    print("<div class='child-comment-container hideComment' id= " .json_encode($commentId) .">");
                        include( WEB_VIEW_CREATECOMMENT );
                    print("</div>");
                print("</div>");
            }
        }
        ?>
    </div>
</div>