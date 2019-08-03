<?php declare(strict_types = 1);

class ResponseModel
{
    public $statusCode;
    public $statusMessage;
    public $data;

    public function __construct(string $statusCode, string $statusMessage, int $httpResponse, array $data = null)
    {
        $this->statusCode = $statusCode;
        $this->statusMessage = $statusMessage;
        http_response_code($httpResponse);
        $this->data = $data != null ? $data : null;
    }
}

?>