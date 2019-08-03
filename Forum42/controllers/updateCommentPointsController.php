<?php

// This is initiatied by an ajax call from Javascript which is why it isn't wrapped in a class
session_start();
require_once('../config.php');

$commentId;
$userId;
$points;
$config;
$userToken;
$userInfo;

// grab session data and make a call out to the UpdateCommentPoints call in the API
if(isset($_SESSION['userInfo'])){
    $userInfo = json_decode($_SESSION['userInfo'], true);
    $config = include_once( WEB_APP_CONFIG );
    $commentId = intval($_POST['id']);
    $points = intval($_POST['points']);
    $userToken = $userInfo['authToken'];
    $userId = intval($userInfo['userId']);

    $postdata = http_build_query(
        array(
            'token'             => $config['app-token'],
            'userToken'         => $userToken,
            'commentId'         => $commentId,
            'userId'            => $userId,
            'points'            => $points
        )
    );
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context  = stream_context_create($opts);
    $result = file_get_contents($config['endPoint'] .'UpdateCommentPoints', true, $context);

    $data = json_decode($result, true);
    if($data['statusCode'] == 'SUCCESS'){
        print('SUCCESS');
    }
    else{
        print($data['statusCode']);
    }
}
else{
    print("fail");
}

?>