<?php

class DB {
    /** @var PDO $connection */
    public $connection;
    public $error = [];
    private $username;
    private $password;
    private $dsn;

    public function __construct(){
//        $host='maspostwarehouseusers.com';
        $host='localhost';
        $db = 'maspost';
//        $this->username = 'appmasuser';
//        $this->password = 'Myapp11!';

        $this->username = 'root';
        $this->password = 'Socorro000!';

        $this->dsn= "mysql:host=$host;dbname=$db;charset=utf8";
        $this->connect();
    }

    private function connect(){
        try{
            $this->connection = new PDO($this->dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            if(isset($e)){
                print_r('Error Connecting: ' . $e->getMessage());
                array_push($this->error, $e->getCode());
                array_push($this->error, $e->getMessage());
                array_push($this->error, time());
            }
        }
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function close(){
        $this->connection = NULL;
    }
}
