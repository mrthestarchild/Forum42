<?php declare(strict_types = 1); 
require_once( API_CONFIG );
require_once( SVC_CONTACT_US_CTL );
require_once( SVC_CONTACT_US_MODEL );

class ContactUs
{
    private $token;
    private $firstName;
    private $lastName;
    private $email;
    private $typeId;
    private $comment;
    private $getContactUs;


    // this series of functions act as a handshake between the front end and dao passing the data from the front to the back
    // it creates an instance of the ContactUsController sets it's properties and prints the ResponseObject to the
    // page for the front end to pick up
    public function __construct(string $token){
        $this->token = $token;
        $this->getContactUs = new ContactUsController($this->token);
    }
    public function getAllContactUsInfo()
    {
        $req_result = $this->getContactUs->getAllContactUsInfo();
        print(json_encode($req_result));
    }
    public function getContactUsDropdown()
    {
        $req_result = $this->getContactUs->getContactUsDropdown();
        print(json_encode($req_result));
    }
    public function updateContactUs(string $firstName, string $lastName, string $email, int $typeId, string $comment)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->typeId = $typeId;
        $this->comment = $comment;
        $model = new ContactUsModel($this->firstName, $this->lastName, $this->email, $this->typeId, $this->comment);
        $req_result = $this->getContactUs->updateContactUs($model);
        print(json_encode($req_result));
    }
}
?>