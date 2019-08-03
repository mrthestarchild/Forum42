<?php declare(strict_types = 1);

// this config defines all locations used by the website portion of the 
// application to use as requires throughout it's controllers and views

//GLOBAL Directories
define('DIR_BASE',      dirname( dirname( __FILE__ ) ) . '/');

//Web Directories
define('DIR_WEB',                       DIR_BASE . 'Forum42/');
define('WEB_CTLS',                      DIR_WEB . 'controllers/');
define('WEB_MDLS',                      DIR_WEB . 'models/');
define('WEB_SVC',                       DIR_WEB . 'services/');
define('WEB_SCRIPTS',                   DIR_WEB . 'scripts/');
define('WEB_STYLE',                     DIR_WEB . 'styles/');
define('WEB_VIEWS',                     DIR_WEB . 'views/');

//Web Base Files
define('WEB_APP_CONFIG',                DIR_WEB . 'app_config.php');
define('WEB_ROUTER',                    DIR_WEB . 'router.php');

//Web Views
define('WEB_VIEW_404',                  WEB_VIEWS . '404.php');
define('WEB_VIEW_ACCOUNTCREATED',       WEB_VIEWS . 'accountCreated.php');
define('WEB_VIEW_CREATEACCOUNT',        WEB_VIEWS . 'createAccount.php');
define('WEB_VIEW_CREATEFORUM',          WEB_VIEWS . 'createForum.php');
define('WEB_VIEW_CREATETHREAD',         WEB_VIEWS . 'createThread.php');
define('WEB_VIEW_CREATECOMMENT',        WEB_VIEWS . 'createComment.php');
define('WEB_VIEW_CREATEROOTCOMMENT',    WEB_VIEWS . 'createRootComment.php');
define('WEB_VIEW_FOOTER',               WEB_VIEWS . 'footer.php');
define('WEB_VIEW_HEADER',               WEB_VIEWS . 'header.php');
define('WEB_VIEW_HOME',                 WEB_VIEWS . 'home.php');
define('WEV_VIEW_NAV',                  WEB_VIEWS . 'nav.php');
define('WEB_VIEW_LOGIN',                WEB_VIEWS . 'login.php');
define('WEB_VIEW_CONTACT_US',           WEB_VIEWS . 'contactUs.php');

//Web Controllers
define('WEB_CTLS_BASE',                 WEB_CTLS . 'baseWebController.php');
define('WEB_CTLS_CREATE_ACCOUNT',       WEB_CTLS . 'createAccountController.php');
define('WEB_CTLS_CREATE_FORUM',         WEB_CTLS . 'createForumController.php');
define('WEB_CTLS_CREATE_THREAD',        WEB_CTLS . 'createThreadController.php');
define('WEB_CTLS_CREATE_COMMENT',       WEB_CTLS . 'createCommentController.php');
define('WEB_CTLS_GET_FORUM_LIST',       WEB_CTLS . 'getForumInfoController.php');
define('WEB_CTLS_GET_THREAD_INFO',      WEB_CTLS . 'getThreadInfoController.php');
define('WEB_CTLS_GET_ACCOUNT_INFO',     WEB_CTLS . 'getAccountInfoController.php');
define('WEB_CTLS_NAV',                  WEB_CTLS . 'navController.php');
define('WEB_CTLS_LOGIN_USER',           WEB_CTLS . 'loginController.php');
define('WEB_CTLS_UPDATE_POINTS',        WEB_CTLS . 'updateCommentPointsController.php');
define('WEB_CTLS_CONTACT_US',           WEB_CTLS . 'contactUsController.php');
define('WEB_CTLS_UPDATE_USER_INFO',     WEB_CTLS . 'updateUserInfoController.php');


//Web Models
define('WEB_MODEL_RESPONSE',            WEB_MDLS . '');


?>