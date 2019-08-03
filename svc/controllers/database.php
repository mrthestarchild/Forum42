<?php declare(strict_types = 1);

class DbWrapper
{
    private $db; 
    private $config;

    //DB Connect
    public function getConnection() : PDO
    {
        // build the PDO and it's options 
        $this->config = include(SVC_CONFIG);
        try{
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->db = new PDO($this->config['host'] .";". $this->config['dbname'], $this->config['username'], $this->config['password'], $options);
            return $this->db;
        }
        catch(PDOException $e){
            throw new PDOException($e->getMessage(), (int)$e->getCode());
            return $this->db;
        }
    }
}

?>