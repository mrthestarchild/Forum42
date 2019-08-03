<?php declare(strict_types = 1); 
require_once( API_CONFIG );
require_once( SVC_BASE_MODEL );
require_once( SVC_CONTACT_US_MODEL );

class ContactUsController extends BaseModel
{
    public $token;

    public function __construct( string $token ){
        parent::__construct();
        $this->token = $token;
    }

    public function getAllContactUsInfo(): ResponseModel
    {
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }
        try{
            $getContactUsInfo = $this->dao->prepare("SELECT cu.contact_us_id, cu.contact_us_fname, cu.contact_us_lname, cu.contact_us_email, cut.contact_us_type_desc, cu.contact_us_comment FROM contact_us cu JOIN contact_us_type cut ON cu.contact_us_type_id = cut.contact_us_type_id");
            $getContactUsInfo->execute();
            $data = $getContactUsInfo->fetchAll();
            $response = new ResponseModel('SUCCESS',"You successfully retrieved data from the contact_us table.", 200, $data);
            return $response;
        }
        catch(PDOException $e){
            $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into contact_us - contactUsController - starting at line 22',500);
            return $response;
        }
    }

    public function getContactUsDropdown(): ResponseModel
    {
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }
        try{
            $getDropdownInfo = $this->dao->prepare("SELECT * FROM contact_us_type ORDER BY contact_us_type_id");
            $getDropdownInfo->execute();
            $data = $getDropdownInfo->fetchAll();
            $response = new ResponseModel('SUCCESS',"You successfully retrieved data from the contact_us_type table.", 200, $data);
            return $response;
        }
        catch(PDOException $e){
            $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert into forum - threadController - starting at line 26',500);
            return $response;
        }
        
    }

    public function updateContactUs(ContactUsModel $model) : ResponseModel
    {
        $check = $this->checkAppToken($this->token);
        if($check){
            $response = new ResponseModel('INVALID_TOKEN','The token is not valid.', 401);
            return $response;
        }
        // this is how I should have built out my models and controllers
        if($this->dao->beginTransaction()){
            try{
                $updateContact = $this->dao->prepare("INSERT INTO contact_us (contact_us_fname, contact_us_lname, contact_us_email, contact_us_type_id, contact_us_comment) VALUES(:fName, :lName, :email, :typeId, :comment)");
                $updateContact->bindParam(':fName', $model->firstName);
                $updateContact->bindParam(':lName', $model->lastName);
                $updateContact->bindParam(':email', $model->email);
                $updateContact->bindParam(':typeId', $model->typeId);
                $updateContact->bindParam(':comment', $model->comment);
                $updateContact->execute();
                if ($this->dao->inTransaction()){
                    $this->dao->commit();
                    $response = new ResponseModel('SUCCESS','The contact us has been updated in the database.',200);
                    return $response;
                }  
            }
            catch(PDOException $e){
                if ($this->dao->inTransaction()){
                    $this->dao->rollBack();
                }  
                $response = new ResponseModel('ERROR','There was a problem proccessing you request. On insert update contact us - contactUsController - starting at line 26',500);
                error_log($e->getMessage(), 0);
                return $response;
            }
            
        }
    }
}