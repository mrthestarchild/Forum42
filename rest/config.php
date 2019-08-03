<?php  declare(strict_types = 1);
//this would be obvuscated in a normal set up using an endpoint
define('DIR_BASE',      dirname( dirname( __FILE__ ) ) . '/');

//API Directories
define('DIR_API',                       DIR_BASE .  'rest/');
define('API_CTLS',                      DIR_API  .  'controllers/');
define('API_AJAX',                      DIR_API  .  'ajax/');
define('API_MDLS',                      DIR_API  .  'models/');

//API config
define('API_CONFIG',                    DIR_API  .  'config.php');

//API Controllers
define('BASE_CTLR_API',                 API_CTLS .  'baseController.php');
define('CREATE_FORUM_API',              API_CTLS .  'createForum.php');
define('CREATE_THREAD_API',             API_CTLS .  'createThread.php');
define('CREATE_COMMENT_API',            API_CTLS .  'createComment.php');
define('CREATE_USER_API',               API_CTLS .  'createUser.php');
define('LOGIN_API',                     API_CTLS .  'loginUser.php');
define('GET_FORUM_API',                 API_CTLS .  'getForumInfoList.php');
define('GET_THREAD_API',                API_CTLS .  'getThreadInfoList.php');
define('GET_COMMENT_API',               API_CTLS .  'getCommentInfoList.php');
define('GET_ACCOUNT_API',               API_CTLS .  'getAccountInfoList.php');
define('CONTACT_US_API',                API_CTLS .  'contactUs.php');
define('UPDATE_USER_INFO_API',           API_CTLS .  'updateUserInfo.php');

//API AJAX requests
define('DELETE_COMMENT',                API_AJAX .  'deleteComment.php');
define('DELETE_FORUM',                  API_AJAX .  'deleteForum.php');
define('DELETE_THREAD',                 API_AJAX .  'deleteThread.php');
define('UPDATE_COMMENT_POINTS',         API_AJAX .  'updateCommentPoints.php');
define('UPDATE_THREAD_POINTS',          API_AJAX .  'updateThreadPoints.php');

//SVC Directories
define('DIR_SVC',                       DIR_BASE .  'svc/');
define('SVC_CTLS',                      DIR_SVC  .  'controllers/');
define('SVC_MDLS',                      DIR_SVC  .  'models/');

//SVC config
define('SVC_CONFIG',                    DIR_SVC  .  'config.php');

//SVC Controllers
define('SVC_DB',                        SVC_CTLS . 'database.php');
define('SVC_FORUM_CTL',                 SVC_CTLS . 'forumController.php');
define('SVC_THREAD_CTL',                SVC_CTLS . 'threadController.php');
define('SVC_COMMENT_CTL',               SVC_CTLS . 'commentController.php');
define('SVC_ACCOUNT_CTL',               SVC_CTLS . 'accountController.php');
define('SVC_DELETE_COMMENT',            SVC_CTLS . 'deleteCommentController.php');
define('SVC_DELETE_FORUM',              SVC_CTLS . 'deleteForumController.php');
define('SVC_DELETE_THREAD',             SVC_CTLS . 'deleteThreadController.php');
define('SVC_UPDATE_COMMENT_POINTS_CTL', SVC_CTLS . 'updateCommentPointsController.php');
define('SVC_UPDATE_THREAD_POINTS_CTL',  SVC_CTLS . 'updateThreadPointsController.php');
define('SVC_CONTACT_US_CTL',            SVC_CTLS . 'contactUsController.php');
define('SVC_UPDATE_USER_CTL',           SVC_CTLS . 'updateUserInfoController.php');
//SVC Models
define('SVC_BASE_MODEL',                SVC_MDLS . 'baseModel.php');
define('SVC_RESPONSE_MODEL',            SVC_MDLS . 'responseModel.php');
define('SVC_USER_MODEL',                SVC_MDLS . 'userModel.php');
define('SVC_FORUM_MODEL',               SVC_MDLS . 'forumModel.php');
define('SVC_THREAD_MODEL',              SVC_MDLS . 'threadModel.php');
define('SVC_COMMENT_MODEL',             SVC_MDLS . 'commentModel.php');
define('SVC_USERINFO_MODEL',            SVC_MDLS . 'userInfoModel.php');
define('SVC_AUTHENTICATOR_MODEL',       SVC_MDLS . 'authenticationModel.php');
define('SVC_CONTACT_US_MODEL',          SVC_MDLS . 'contactUsModel.php');

?>