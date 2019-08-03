<?php
require_once( SVC_BASE_MODEL );

class Authenticator extends BaseModel
{   
    public function __construct(){
        parent::__construct();
    }

    public function authenticate(User $user) : ResponseModel
    {
        $isSiteAdmin = 0;
        // check token to make sure it is valid before we run query.
        $check = $this->checkAppToken($user->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }
        // check to make sure the username is valid before we authenticate.
        $check = $this->checkForDuplicateValue('user', 'user_username', $user->username);
        if(!$check){
            $response = new ResponseModel('INVALID_USERNAME','The username does not exsist, can not log on', 409);
            return $response;
        }
        // get user info from the db
        $userInfo = $user->getUserInfo($user->username, $user->password);
        // check password to make sure they match
        if(!password_verify($user->password, $userInfo->password)){
            $response = new ResponseModel('INVALID_PASSWORD','The password does not match the user password, can not log on', 409);
            return $response;
        }

        // create token and insert it into table
        try{
            // Create token header as a JSON string
            $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
            // Create token payload as a JSON string
            $payload = json_encode(['user_id' => $userInfo->userId, 'user_name' => $userInfo->username]);
            // Encode Header to Base64Url String
            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
            // Encode Payload to Base64Url String
            $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
            // Create date time of now to pass into the JWT
            $getDateTime = new DateTime();
            $dateResult = $getDateTime->format('Y-m-d H:i:s');
            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $dateResult, true);
            // Encode Signature to Base64Url String
            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
            // Create JWT
            $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
            
            $updateJWT = $this->dao->prepare("UPDATE user SET user_auth_token = :jwt WHERE user_id = :userId");
            $updateJWT->bindParam(':jwt', $jwt);
            $updateJWT->bindParam(':userId', $userInfo->userId);
            $updateJWT->execute();
            $getUser = $this->dao->prepare("SELECT user_create_time FROM user WHERE user_id = :userId");
            $getUser->bindParam(':userId', $userInfo->userId);
            $getUser->execute();
            $checkUser = $getUser->fetch();
            $checkIfAdmin = $this->dao->prepare("SELECT * FROM admin WHERE user_id = :userId AND user_type_id = 1");
            $checkIfAdmin->bindParam(':userId', $userInfo->userId);
            $checkIfAdmin->execute();
            $adminRows = $checkIfAdmin->rowCount();
            if($adminRows > 0){
                $isSiteAdmin = 1;
            }

            $user = [];
            $parsedDate = $this->formatDateTime($checkUser['user_create_time']);
            $user['userId'] = $userInfo->userId;
            $user['username'] = $userInfo->username;
            $user['email'] = $userInfo->email;
            $user['authToken'] = $userInfo->authToken;
            $user['is-site-admin'] = $isSiteAdmin;
            $user['dateCreated'] = $checkUser['user_create_time'];
            $user['parsedDate'] = $parsedDate;


            header("JWT: $jwt");
            //$_SESSION['userInfo'] = json_encode(get_object_vars($userInfo));

            $response = new ResponseModel('SUCCESS','The user has been succesfully logged in.', 200, $user);
            return $response;
        }
        catch(PDOException $e){
            error_log($e->getMessage(), 0);
            $response = new ResponseModel('ERROR','There was an error logging in the user.', 401);
            return $response;
        }
    }
    
}
?>