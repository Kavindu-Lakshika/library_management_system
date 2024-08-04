<?php
class Database
{
    private $host = 'localhost';
    private $port = '3306  ';
    private $username = 'root';
    private $password = '1234';
    private $database = 'library';
    
    public $conn;
    
    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);
        
        if ($this->conn->connect_error) {
            die('Connection failed: ' . $this->conn->connect_error);
        }
    }
    
    public function query($query)
    {
        $result = $this->conn->query($query);
        
        if (!$result) {
            die('Error: ' . $this->conn->error);
        }
        
        return $result;
    }
    
    public function fetchAll($result)
    {
        $rows = array();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        
        return $rows;
    }
}
