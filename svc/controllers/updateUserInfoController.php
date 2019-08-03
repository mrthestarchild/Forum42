<?php
require_once( SVC_BASE_MODEL );

class UserInfoUpdate extends BaseModel
{
    public $token;
    public $userToken;
    public $userId;
    public $oldPassword;
    public $newPassword;
    public $email;

    public function __construct(string $token, string $userToken, int $userId, string $oldPassword, string $newPassword, string $email){
        parent::__construct();
        $this->token = $token;
        $this->userToken = $userToken;
        $this->userId = $userId;
        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
        $this->email = $email;
    }

    public function updateUserEmail() : ResponseModel
    {
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }
        // if this user id is valid
        $check = $this->checkUserToken($this->userToken, $this->userId);
        if($check){
            $response = new ResponseModel('INVALID_USER','The person making the request is not the valid user.', 401);
            return $response;
        }
        if($this->dao->beginTransaction()){
            try{
                $updateEmail = $this->dao->prepare("UPDATE user SET user_email = :email WHERE user_id = :userId");
                $updateEmail->bindParam(':email', $this->email);
                $updateEmail->bindParam(':userId', $this->userId);
                $updateEmail->execute();
                if ($this->dao->inTransaction()){
                    $this->dao->commit();
                    $response = new ResponseModel('SUCCESS','You have successfully update your password',200);
                    return $response;
                }  
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                }  
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On update user - UserInfoUpdate - starting at line 37',500);
                error_log($e->getMessage(), 0);
                return $response;
            }
        }
    }
    public function updateUserPassword() : ResponseModel
    {
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }

        // if this user id is valid
        $check = $this->checkUserToken($this->userToken, $this->userId);
        if($check){
            $response = new ResponseModel('INVALID_USER','The person making the request is not the valid user.', 401);
            return $response;
        }
        if($this->dao->beginTransaction()){
            try{
                $checkPassword = $this->dao->prepare("SELECT * FROM user WHERE user_id = :userId");
                $checkPassword->bindParam(':userId', $this->userId);
                $checkPassword->execute();
                $oldPassword = $checkPassword->fetch();
                if(!password_verify($oldPassword, $this->oldPassword)){
                    if ($this->dao->inTransaction()){
                        $this->dao->rollBack();
                        $response = new ResponseModel('INVALID_PASSWORD','The password does not match the user password, can not log on', 409);
                        return $response;
                    }   
                }
                $updatePassword = $this->dao->prepare("UPDATE user SET user_password = :newPassword WHERE user_id = :userId");
                $updatePassword->bindParam(':newPassword', $this->newPassword);
                $updatePassword->bindParam(':userId', $this->userId);
                $updatePassword->execute();
                if ($this->dao->inTransaction()){
                    $this->dao->commit();
                    $response = new ResponseModel('SUCCESS','You have successfully update your password',200);
                    return $response;
                }  
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                }  
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On On update user - UserInfoUpdate - starting at line 73',500);
                error_log($e->getMessage(), 0);
                return $response;
            }
        }
    }
}
?>