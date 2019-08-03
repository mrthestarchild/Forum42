<?php

// creates a instance of the GetForumInfoController class
// to send request and retrieve forum information to display
// to the end user when the page is loaded
require_once( WEB_CTLS_GET_FORUM_LIST );
$getForums = new GetForumInfoController();
$forumResponse = $getForums->getForumList();
$forumInfo = $forumResponse['data'];
if(empty($forumResponse['data']) || $forumInfo['is_active'] == 0){
    header("Location: /Forum42/404.php");
    exit;
}
$threadResponse = $getForums->getAllThreadsInForum();
$threadInfo = $threadResponse['data'];
if(isset($_SESSION['userInfo'])){
    $userInfo = json_decode($_SESSION['userInfo'], true);
}
?>

<div class="forum-wrapper">
    <div class="forum-header">
        <?php 
        print("<img alt='forum icon' class='forum-icon' src='" .$forumInfo["forum_icon"] ."'>");
        print("<h1>". $forumInfo['forum_name']."</h1>");
        print("<div class='forum-header-desc'>");
            print("<p>" .$forumInfo['forum_description'] ."</p>");
        print("</div>");
        print("<div class='create-thread-container'>"); 
             if(isset($_SESSION['userInfo'])){
                print("<p>Create Thread</p>");
                print("<h5 onclick='createThread()' class='material-icons'>add_circle</h5>");
            }
        print("</div>");
        if(isset($_SESSION['userInfo'])){
            if($userInfo['userId'] == $forumInfo['user_id']){
                print("<div class='delete-forum-button'>");
                    print("<p onclick='deleteForum(" .json_encode($forumInfo['forum_id']) .")' >Delete</p>");
                print("</div>");
            }
        }
        ?>
    </div>
    <div id="create-thread">
        <div class="create-thread-modal">
        <?php require_once(WEB_VIEW_CREATETHREAD); ?>
        </div>
    </div>
    <div id="overlayCreateThread"></div>
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