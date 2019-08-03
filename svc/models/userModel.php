<?php
require_once( SVC_BASE_MODEL );
require_once( SVC_USERINFO_MODEL );

class User extends BaseModel
{
    public $token;
    public $username;
    public $password;
    public $email;

    public function __construct(string $token, string $username, string $password, string $email = ""){
        parent::__construct();
        $this->token = $token;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    public function createUser() : ResponseModel
    {
        // check token to make sure it is valid before we run query.
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }
        
        // check to make sure the username isn't already in exsistance before we run the insert
        $check = $this->checkForDuplicateValue('user', 'user_username', $this->username);
        if($check){
            $response = $response = new ResponseModel('DUPLICATE_USERNAME','The username is already in use.',409);
            return $response;
        }
        
        // check to make sure the email isn't already in exsistance before we run the insert
        $check = $this->checkForDuplicateValue('user', 'user_email', $this->email);
        if($check){
            $response = $response = new ResponseModel('DUPLICATE_EMAIL','The email is already in use.',409);
            return $response;
        }

        // if username is available we insert new username
        try{
            $bindCheck = $this->dao->prepare("INSERT INTO user (user_username, user_password, user_email) VALUES(:username,:password,:email)");
            $bindCheck->bindParam(':username', $this->username);
            $bindCheck->bindParam(':password', $this->password);
            if($this->email != null){
                $bindCheck->bindParam(':email', $this->email);
            }
            else{
                $bindCheck->bindValue(':email', null, PDO::PARAM_NULL);
            }
            $bindCheck->execute();

            $response = $response = new ResponseModel('SUCCESS','The user has been added to the database.',200);
            return $response;
        }
        catch(PDOException $e){
            error_log($e->getMessage(), 0);
        }
    }
    public function getUserInfo(string $username) : UserInfo
    {
        // select from 
        try{
            $query = $this->dao->prepare("SELECT * FROM user WHERE user_username = :username");
            $query->bindParam(':username', $username);
            $query->execute();
            $getResponse = $query->fetch();

            if($getResponse['user_auth_token'] == null){
                $getResponse['user_auth_token'] = "";
            }
            if($getResponse['user_email'] == null){
                $getResponse['user_email'] = "";
            }

            $userInfo = new UserInfo($getResponse['user_id'], $getResponse['user_username'], $getResponse['user_password'], $getResponse['user_email'], $getResponse['user_auth_token'],$getResponse['is_banned']);
            return $userInfo;
        }
        catch(PDOException $e){
            error_log($e->getMessage(), 0);
        }
    }
}
?>