<?php
require_once( WEB_CTLS_BASE );
require_once( WEB_CTLS_GET_FORUM_LIST );

class NavController extends BaseWebController
{
    public function __construct(){
        parent::__construct();
    }

    // this function gets all valid forums for the site and returns them to be displayed in the 
    // nav search bar as a dropdown
    public function getAllForums() : array
    {
        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'getForumName'      => $this->getForumName
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
        $result = file_get_contents($this->config['endPoint'] .'GetAllForums', true, $context);


        $data = json_decode($result, true);
        if($data['statusCode'] == 'SUCCESS'){
            return $data;
        }
        else if($data['statusCode'] == 'INVALID_TOKEN'){
            return ["message" => "there was a problem with your request please contact system administrator."];
        }
        else{
            return ["message" => "there was a error processing your request please contact system administrator."];
        }
    }
}
?>