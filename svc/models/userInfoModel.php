<?php declare(strict_types = 1);

class UserInfo
{
    public $userId;
    public $username;
    public $password;
    public $email;
    public $authToken;
    public $isBanned;

    public function __construct(int $userId, string $username, string $password ,string $email, string &$authToken, bool $isBanned)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->authToken = $authToken != null ? $authToken : null;
        $this->isBanned = $isBanned;
    }
}

?>