<?php

// creates a instance of the GetForumInfoController class
// to send request and retrieve forum information from all forums
// to display the top threads to the end user when the page is loaded
require_once( WEB_CTLS_GET_FORUM_LIST );
$getTopThreads = new GetForumInfoController();
$forumResponse = $getTopThreads->getTopThreads();
if(!empty($forumResponse['data'])){
    $forumInfo = $forumResponse['data'];
}
else{
    header("Location: /Forum42/404.php");
    exit;
}
$threadInfo = $forumResponse['data'];
if(isset($_SESSION['userInfo'])){
    $userInfo = json_decode($_SESSION['userInfo'], true);
}
?>

<div class="forum-wrapper">
    <div class="forum-body">
        <?php
        if(!empty($threadInfo)){
            foreach ($threadInfo as $threads) {
                if($threads['is_active'] == 0) continue;
                $upDootParam = "updoot" .$threads['thread_id'];
                $downDootParam = "downdoot" .$threads['thread_id'];
                $pointsId = "points" .$threads['thread_id'];
                $threadId = "thread" .$threads['thread_id'];
                $modalId = "modal" .$threads['thread_id'];
                $modalImgId = "modalImg" .$threads['thread_id'];
                $imgId = "img" .$threads['thread_id'];
                $closeId = "close" .$threads['thread_id'];
                print("<div class='thread-wrapper'>");
                print("<div class='thread-votes'>");
                    if(isset($_SESSION['userInfo'])){
                        if($threads['user_points_id'] == $userInfo['userId'] && $threads['user_points'] == 1){
                            print("<i class='material-icons updoot upvote' id=".json_encode($upDootParam) ." onclick='higlightArrow(" .json_encode($upDootParam) .")' data-voted >expand_less</i>");
                        }
                        else{
                            print("<i class='material-icons updoot' id=".json_encode($upDootParam) ." onclick='higlightArrow(" .json_encode($upDootParam) .")'>expand_less</i>");
                        }
                    }
                    else{
                        print("<i class='material-icons updoot' id=".json_encode($upDootParam) .">expand_less</i>");
                    }
                    print("<p id= " .json_encode($pointsId) .">".$threads['thread_points_total'] ."</p>");
                    if(isset($_SESSION['userInfo'])){
                        if($threads['user_points_id'] == $userInfo['userId'] && $threads['user_points'] == -1){
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
                print("<div class='thread-body-wrapper'>");
                    print("<div class='thread-info unselectable' onclick='toggleFooter(" .json_encode($threadId) .")'>");
                        print("<div class='forum-name'>");
                        print("<a href='forum?" .$threads['forum_name'] ."'>F42/" .$threads['forum_name'] ."</a>");
                        print("</div>");
                        print("<div class='thread-title'>");
                            print("<h3>".$threads['thread_title'] ."</h3>");
                        print("</div>");
                        print("<div class='thread-body'>");
                            print("<p>".$threads['thread_body'] ."</p>");
                        print("</div>");
                    print("</div>");
                    if(strpos($threads['thread_image'], '../') !== false){
                    print("<div class='thread-image' onclick='openPhoto(" .json_encode($modalId) ."," .json_encode($imgId) ."," .json_encode($modalImgId) ."," .json_encode($closeId) .")'>");
                        print("<img alt='thread image' src='".$threads['thread_image'] ."' id=" .json_encode($imgId) .">");
                    print("</div>");
                        print("<div id=" .json_encode($modalId) ."class='modal'>");
                            print("<span class='close' id=" .json_encode($closeId) .">&times;</span>");
                            print("<img alt='user input image' src='./img/empty.png' class='modal-content' id=" .json_encode($modalImgId) .">");
                        print("</div>");
                    }
                    else if(strpos($threads['thread_link'], 'http') !== false || strpos($threads['thread_link'], 'www') !== false){
                        print("<div class='thread-image'>");
                        print("<a href='" .$threads['thread_link'] ."' target='_blank'>");
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
                print("<div class='thread-footer hideThread preload-animation' id='thread" .$threads['thread_id'] ."'>");
                    print("<div class='user-info'>");
                        if($threads['site_admin'] == $threads['user_id']){
                            print("<i class='material-icons admin-badge'>public</i>");
                            print("<p class='is-site-admin'>".$threads['user_name'] ."</p>");
                        }
                        else if($threads['forum_admin'] == $threads['user_id']){
                            print("<i class='material-icons admin-badge'>visibility</i>");
                            print("<p class='is-admin'>".$threads['user_name'] ."</p>");
                        }
                        else{
                            print("<p>".$threads['user_name'] ."</p>");
                        }    
                        print("<p>".$threads['thread_date_created'] ."</p>");
                    print("</div>");
                    print("<div class='thread-button-container'>");
                        if(isset($_SESSION['userInfo'])){
                            if($threads['user_id'] == $userInfo['userId'] || $threads['is_admin'] == 1){
                                print("<p class='delete-thread-button' onclick='deleteThread(".json_encode($threadId) .")'>Delete</p>");
                            }
                        }
                        print("<a href='view-thread?" .$threads['thread_id'] ."'>View Comments</a>");
                    print("</div>");
                print("</div>");
            }
        }
        ?>
    </div>
</div>