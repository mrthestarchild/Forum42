<?php declare(strict_types = 1); 
require_once( API_CONFIG );
require_once( SVC_ACCOUNT_CTL );

class GetAccountInfoList
{
    private $token;
    private $userToken;
    private $userId;

    public function __construct(string $token, string $userToken, int $userId){
        $this->token = $token;
        $this->userToken = $userToken;
        $this->userId = $userId;
        $this->getAccount = new AccountController( $this->token, $this->userToken, $this->userId );
    }

    // this function acts as a handshake between the front end and dao passing the data from the front to the back
    // it creates an instance of the AccountController sets it's properties and prints the ResponseObject to the
    // page for the front end to pick up
    public function getAccountInfo()
    {
        $req_result = $this->getAccount->getAccountInfo();
        print(json_encode($req_result));
    }
}
?>