<?php

class ContactUsModel 
{
    public $firstName;
    public $lastName;
    public $email;
    public $typeId;
    public $comment;

     public function __construct(string $firstName = "", string $lastName= "", string $email = "", int $typeId, string $comment){
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->typeId = $typeId;
        $this->comment = $comment;
     }
}