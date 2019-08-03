<?php declare(strict_types = 1); 
require_once( SVC_UPDATE_USER_CTL );


class UpdateUserInfo
{
    private $token;
    private $userId;
    private $oldPassword;
    private $newPassword;
    private $email;
    private $updateUser;

    // this series of functions act as a handshake between the front end and dao passing the data from the front to the back
    // it creates an instance of the UserInfoUpdate sets it's properties and prints the ResponseObject to the
    // page for the front end to pick up
    public function __construct(string $token, string $userToken, int $userId, string $oldPassword = "", string $newPassword = "", string $email = "")
    {
        $this->token = $token;
        $this->userToken = $userToken;
        $this->userId = $userId;
        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
        $this->email = $email;
        $this->updateUser = new UserInfoUpdate($this->token, $this->userToken, $this->userId, $this->oldPassword, $this->newPassword, $this->email);
    }

    public function updateUserPassword()
    {
        $req = $this->updateUser->updateUserPassword();
        $req_result = get_object_vars($req);
        print(json_encode($req_result));
    }
    public function updateUserEmail()
    {
        $req = $this->updateUser->updateUserEmail();
        $req_result = get_object_vars($req);
        print(json_encode($req_result));
    }
}


?>