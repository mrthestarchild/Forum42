<?php declare(strict_types = 1); 
require_once( SVC_USER_MODEL );


class CreateUser
{
    private $user;
    private $token;
    private $username;
    private $password;
    private $email;

    public function __construct(string $token, string $username, string $password,string &$email)
    {
        $this->token = $token;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->user = new User($this->token, $this->username, $this->password, $this->email);
    }

    // this function acts as a handshake between the front end and dao passing the data from the front to the back
    // it creates an instance of the User sets it's properties and prints the ResponseObject to the
    // page for the front end to pick up
    public function createUser()
    {
        $req = $this->user->createUser();
        $req_result = get_object_vars($req);
        print(json_encode($req_result));
    }
}


?>