<?php 
// include get forum list controller
require_once( WEB_CTLS_BASE );
require_once( WEB_CTLS_GET_FORUM_LIST );
// start the session for all pages
session_start();
// create instance of GetForumListController
$getForums = new GetForumInfoController();
// get the list of forums from the controller
$getResponse = $getForums->getAllForums();
// retreave the data from the response
$response = $getResponse['data'];

?>
<div class="nav-wrapper wrapper">
    <nav>
        <div class="logo-wrapper">
            <a href="/Forum42/">
                <img alt="site logo" src="./img/forum_42_logo.png">
            </a>
        </div>
        <div class="dropdown-wrapper">
            <div class="dropdown-menu" onclick="toggleMenu()">
                <i class="material-icons search-icon" >search</i>
                <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                <div id="myDropdown">
                    <?php
                    // create nested foreach to retreave list of values for forum list
                    foreach($response as $value) {
                        foreach($value as $forumName){
                            print("<a href='forum?" .trim($forumName) ."'>" .trim($forumName) ."</a>");
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        if(!isset($_SESSION['userInfo'])){
            print("<div class='user-logged-out-wrapper'>");
                print("<p onclick='loginDropdown()'>Login</p>");
                print("<a href='create-account'>Create Account</a>");
            print("</div>");
        }  
        else{
            print("<div class='user-info-wrapper'>");
                print("<i class='material-icons settings-icon' onclick='toggleSettings()'>settings</i>");
                print("<div class='user-info-dropdown' id='infoDropdown'>");
                    $userInfo = json_decode($_SESSION['userInfo'], true);
                    print("<a href='manage-account?" .$userInfo['username'] ."'><i class='material-icons nav-icon'>person</i><p>" .$userInfo['username'] ."</p></a>");
                    print("<a href='create-forum'><i class='material-icons nav-icon'>add</i><p>Create Forum</p></a>");
                    if($userInfo['is-site-admin']){
                        print("<a href='view-contact-us'><i class='material-icons nav-icon'>pageview</i><p>Contact Us Submissions</p></a>");
                    }
                    print("<a onclick='logout()'><i class='material-icons nav-icon'>hotel</i><p>Logout</p></a>");
                print("</div>");
            print("</div>");
        }
        ?>
    </nav>
</div>
<div class='login-dropdown-wrapper' id='loginDropdown'>
   <?php require_once( WEB_VIEW_LOGIN ); ?>
</div>
<div class="overlay " id="overlayMenu"></div>
<div class="overlay " id="overlaySettings"></div>
<div class="overlay " id="overlayLogin"></div>