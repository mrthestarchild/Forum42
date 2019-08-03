<?php declare(strict_types = 1);
require_once( SVC_RESPONSE_MODEL );
require_once( SVC_DB );

class BaseModel
{
    public $db;
    public $dao;
    public $config;
    
    public function __construct(){
        $this->config = include( SVC_CONFIG );
        $this->db = new DbWrapper();
        $this->dao = $this->db->getConnection();
    }

    public function checkAppToken(string $token) : bool
    {
        // check token to make sure it is valid before we run query.
        if($token !== $this->config['token']){
            return true;
        }
        return false;
    }
    public function checkForDuplicateValue(string $table, string $column, string &$insertVal) : bool
    {
        // check to make sure the username isn't already in exsistance before we run the insert
        try{
            if($insertVal != null){
                $query = $this->dao->prepare("SELECT * FROM $table WHERE $column = :insertVal");
                $query->bindParam(':insertVal', $insertVal);
                $query->execute();
                $check_num = $query->rowCount();
                return $check_num > 0 ? true : false;
            }
            return false;
        }
        catch(PDOException $e){
            error_log($e->getMessage(), 0);
        }
    }
    public function checkUserToken(string $userToken, int $userId) : bool
    {
        try{
            if($userToken != '' && $userId != null){
                $query = $this->dao->prepare("SELECT * FROM user WHERE user_auth_token = :userToken AND user_id = :userId");
                $query->bindParam(':userToken', $userToken);
                $query->bindParam(':userId', $userId);
                $query->execute();
                $check_num = $query->rowCount();
                return $check_num > 0 ? true : false;
            }
            return false;
        }
        catch(PDOException $e){
            error_log($e->getMessage(), 0);
        }
    }
    public function getIdByValue(string $table, string $column, string $insertVal, string $columnResultValue) : int
    {
        try{
            $query = $this->dao->prepare("SELECT * FROM $table WHERE $column = :insertVal");
            $query->bindParam(':insertVal', $insertVal);
            $query->execute();
            $check_num = $query->rowCount();
            $result = $query->fetch();
            return $check_num > 0 ? $result[$columnResultValue] : 0;
        }
        catch(PDOException $e){
            error_log($e->getMessage(), 0);
        }
    }
    public function formatDateTime( string $compareDate ) : string
    {
        $currentTime = new DateTime('now');
        $createdTime = new DateTime($compareDate);
        $diffTime = $createdTime->diff($currentTime, true);
        $year = $diffTime->format('%y');
        $month = $diffTime->format('%m');
        $day = $diffTime->format('%d');
        $hour = $diffTime->format('%h');
        $minute = $diffTime->format('%i');
        $second = $diffTime->format('%s');
        if($year < 1){
            if($month < 1){
                if($day < 1){
                    if($hour < 1){
                        if($minute < 1){
                            if($second <= 1){
                                return "$second second ago";
                            }
                            else{
                                return "$second seconds ago";
                            }
                        }
                        else if($minute == 1){
                            return "$minute minute ago";
                        }
                        else{
                            return "$minute minutes ago";
                        }
                    }
                    else{
                        if($hour == 1){
                            return "$hour hour ago";
                        }
                        else{
                            return "$hour hours ago";
                        }
                    }
                }
                else if($day == 1){
                    return "$day day ago";
                }
                else if($day < 7){
                    return "$day days ago";
                }
                else if($day > 6 && $day < 15){
                    return "1 week ago";
                }
                else if($day > 14 && $day < 22){
                    return "2 weeks ago";
                }
                else if($day > 21 && $day < 28){
                    return "3 weeks ago";
                }
                else{
                    return "4 weeks ago";
                }
            }
            else if($month == 1){
                return "$month month ago";
            }
            else{
                return "$month months ago";
            }
        }
        else if($year == 1){
            return "$year year ago";
        }
        else{
            return "$year years ago";
        }
    }
}

?>