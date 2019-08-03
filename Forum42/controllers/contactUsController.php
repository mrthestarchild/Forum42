<?php

class ContactController extends BaseWebController
{
    private $firstName;
    private $lastName;
    private $email;
    private $typeId;
    private $comment;

    // check to see what is captured in the post and set it to local variables
    public function __construct(){
        parent::__construct();
        if(isset($_POST['firstName'])){
            $this->firstName = $_POST['firstName'];
        }
        if(isset($_POST['lastName'])){
            $this->lastName = $_POST['lastName'];
        }
        if(isset($_POST['email'])){
            $this->email = $_POST['email'];
        }
        if(isset($_POST['typeId'])){
            $this->typeId = $_POST['typeId'];
        }
        if(isset($_POST['comment'])){
            $this->comment = $_POST['comment'];
        }
    }
    // this function sends out a request to retrieve the dropdown menu contents for the
    // contact us page, it returns an array of the dropdown menu data.
    public function getContactUsDropdown() : array
    {
        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
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
        $result = file_get_contents($this->config['endPoint'] .'GetContactUsDropdown', true, $context);
        $data = json_decode($result, true);
        return $data;
    }
    // this function sends out a request to retrieve the contact us table contents for the
    // contact us page, it returns the rows from the contact_us table
    public function getAllContactUsInfo() : array
    {
        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
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
        $result = file_get_contents($this->config['endPoint'] .'GetAllContactUsInfo', true, $context);
        $data = json_decode($result, true);
        return $data;
    }
    // this function adds a row to the contact_us table wit contents submited by the end user
    // it returns a string value that is a message for the end user.
    public function updateContactUs() : string
    {
        $postdata = http_build_query(
            array(
                'token'             => $this->config['app-token'],
                'firstName'         => $this->firstName,
                'lastName'          => $this->lastName,
                'email'             => $this->email,
                'typeId'            => $this->typeId,
                'comment'           => $this->comment
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
        $result = file_get_contents($this->config['endPoint'] .'UpdateContactUs', true, $context);
        $data = json_decode($result, true);
        // check status code and return message accordingly
        if($data['statusCode'] == 'SUCCESS'){
            return "thank you for your submission";
        }
        else if($data['statusCode'] == 'INVALID_TOKEN'){
            return "there was a problem with your request please contact system administrator.";
        }
        else{
            return "there was a problem with your request please contact system administrator.";
        }
    }
}