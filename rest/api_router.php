<?php
// get the request URI from URL
$request = $_SERVER['REQUEST_URI'];

require_once('./config.php');

$config = require_once('./app_config.php');

// this router works similar to the front end router but instead creates an instance of the object 
// and then calls the appropriate method to build the response that was called. The response
// is then printed to the page

switch ($request) {
    case '/rest/CreateComment' :
        require_once(CREATE_COMMENT_API);
        $page_to_load  = API_CTLS .'createComment.php';
        $getAPI = new CreateComment($_POST['token'], $_POST['userToken'], $_POST['threadId'], $_POST['commentLinkRoot'], $_POST['commentLinkParent'], $_POST['commentLinkDepth'], $_POST['userId'], $_POST['commentBody']);
        $getAPI->createComment();
        break;
    case '/rest/CreateForum' :
        require_once(CREATE_FORUM_API);
        $page_to_load  = API_CTLS .'createForum.php';
        $getAPI = new CreateForum($_POST['token'], $_POST['userId'], $_POST['userToken'], $_POST['forumName'], $_POST['forumDesc'], $_POST['forumIcon']);
        $getAPI->createForum();
        break;
    case '/rest/CreateThread' :
        require_once(CREATE_THREAD_API);
        $page_to_load  = API_CTLS .'createThread.php';
        $getAPI = new CreateThread($_POST['token'], $_POST['userId'], $_POST['userToken'], $_POST['forumName'], $_POST['threadTitle'], $_POST['threadBody'], $_POST['threadPhoto'], $_POST['threadURL']);
        $getAPI->createThread();
        break;
    case '/rest/CreateUser' :
        require_once(CREATE_USER_API);
        $page_to_load  = API_CTLS .'createUser.php';
        $getAPI = new CreateUser($_POST['token'], $_POST['username'], $_POST['password'], $_POST['email']);
        $getAPI->createUser();
        break;
    case '/rest/GetAccountInfoList' :
        require_once(GET_ACCOUNT_API);
        $page_to_load  = API_CTLS .'getAccountInfoList.php';
        $getAPI = new GetAccountInfoList($_POST['token'], $_POST['userToken'], $_POST['userId']);
        $getAPI->getAccountInfo();
        break;
    case '/rest/GetForumList' :
        require_once(GET_FORUM_API);
        $page_to_load  = API_CTLS .'getForumInfoList.php';
        $getAPI = new GetForumInfoList($_POST['token'], $_POST['getForumName']);
        $getAPI->getForumList();
        break;
    case '/rest/GetAllForums' :
        require_once(GET_FORUM_API);
        $page_to_load  = API_CTLS .'getForumInfoList.php';
        $getAPI = new GetForumInfoList($_POST['token'], $_POST['getForumName']);
        $getAPI->getAllForums();
        break;
    case '/rest/GetAllThreadsInForum' :
        require_once(GET_FORUM_API);
        $page_to_load  = API_CTLS .'getForumInfoList.php';
        $getAPI = new GetForumInfoList($_POST['token'], $_POST['getForumName']);
        $getAPI->getAllThreadsInForum($_POST['userId']); 
        break;
    case '/rest/GetTopThreadsForSite' :
        require_once(GET_FORUM_API);
        $page_to_load  = API_CTLS .'getForumInfoList.php';
        $getAPI = new GetForumInfoList($_POST['token'], "");
        $getAPI->getTopThreadsForSite($_POST['userId']); 
        break;
    case '/rest/GetThread' :
        require_once(GET_THREAD_API);
        $page_to_load  = API_CTLS .'getThreadInfoList.php';
        $getAPI = new GetThreadInfoList($_POST['token'], $_POST['threadId']);
        $getAPI->getThread($_POST['userId']); 
        break;
    case '/rest/GetAllCommentsInThread' :
        require_once(GET_THREAD_API);
        $page_to_load  = API_CTLS .'getThreadInfoList.php';
        $getAPI = new GetThreadInfoList($_POST['token'], $_POST['threadId']);
        $getAPI->getAllCommentsInThread($_POST['userId']); 
        break;
    case '/rest/LoginUser' :
        require_once(LOGIN_API);
        $page_to_load  = API_CTLS .'loginUser.php';
        $getAPI = new LoginUser($_POST['token'], $_POST['username'], $_POST['password'], $_POST['email']);
        $getAPI->loginUser();
        break;
    case '/rest/GetAllContactUsInfo' :
        require_once(CONTACT_US_API);
        $page_to_load  = API_CTLS .'contactUs.php';
        $contactUs = new ContactUs($_POST['token']);
        $contactUs->getAllContactUsInfo();
        break;
    case '/rest/GetContactUsDropdown' :
        require_once(CONTACT_US_API);
        $page_to_load  = API_CTLS .'contactUs.php';
        $contactUs = new ContactUs($_POST['token']);
        $contactUs->getContactUsDropdown();
        break;
    case '/rest/UpdateContactUs' :
        require_once(CONTACT_US_API);
        $page_to_load  = API_CTLS .'contactUs.php';
        $contactUs = new ContactUs($_POST['token']);
        $contactUs->updateContactUs($_POST['firstName'], $_POST['lastName'], $_POST['email'], intval($_POST['typeId']), $_POST['comment']);
        break;
    case '/rest/UpdateUserEmail' :
        require_once(UPDATE_USER_INFO_API);
        $page_to_load  = API_CTLS .'updateUserInfo.php';
        $updateEmail = new UpdateUserInfo($_POST['token'], $_POST['authToken'], $_POST['userId'], $_POST['oldPassword'], $_POST['newPassword'], $_POST['email']);
        $updateEmail->updateUserEmail();
        break;
    case '/rest/UpdateUserPassword' :
        require_once(UPDATE_USER_INFO_API);
        $page_to_load  = API_CTLS .'updateUserInfo.php';
        $updatePassword = new UpdateUserInfo($_POST['token'], $_POST['authToken'], $_POST['userId'], $_POST['oldPassword'], $_POST['newPassword'], $_POST['email']);
        $updatePassword->updateUserPassword();
        break;
    case '/rest/UpdateCommentPoints' :
        require_once(UPDATE_COMMENT_POINTS);
        $page_to_load  = API_AJAX .'updateCommentPoints.php';
        $updateCommentPoints = new UpdateCommentPoints($_POST['token'], $_POST['userToken'], $_POST['commentId'], $_POST['userId'], $_POST['points']);
        $updateCommentPoints->updateScore();
        break;
    case '/rest/UpdateThreadPoints' :
        require_once(UPDATE_THREAD_POINTS);
        $page_to_load  = API_AJAX .'updateThreadPoints.php';
        $updatePoints = new UpdateThreadPoints($_POST['token'], $_POST['userToken'], $_POST['threadId'], $_POST['userId'], $_POST['points']);
        $updatePoints->updateScore();
        break;
    case '/rest/DeleteComment' :
        require_once(DELETE_COMMENT);
        $page_to_load  = API_AJAX .'deleteComment.php';
        $deleteComment = new DeleteComment($_POST['token'], $_POST['userToken'], $_POST['commentId'], $_POST['userId']);
        $deleteComment->deleteComment();
        break;
    case '/rest/DeleteThread' :
        require_once(DELETE_THREAD);
        $page_to_load  = API_AJAX .'deleteThread.php';
        $deleteThread = new DeleteThread($_POST['token'], $_POST['userToken'], $_POST['threadId'], $_POST['userId']);
        $deleteThread->deleteThread();
        break;
    case '/rest/DeleteForum' :
        require_once(DELETE_FORUM);
        $page_to_load  = API_AJAX .'deleteForum.php';
        $deleteForum = new DeleteForum($_POST['token'], $_POST['userToken'], $_POST['forumId'], $_POST['userId']);
        $deleteForum->deleteForum();
        break;
    default: 
        $page_to_load  = "";
        break;
}
?>