<?php

class Database
{
    var $servername;
    var $database;
    var $username;

    function __construct($username)
    {
        $this->servername = "easy-park.ctysqjeui0cm.sa-east-1.rds.amazonaws.com";
        $this->database = "easypark";
        $this->username = $username;
    }


    public function getConnection()
    {
        $this->connection = mysqli_connect($this->servername, $this->username, "", $this->database);
        return $this->connection;
    }

    public function closeConnection()
    {
        $this->connection->close();
    }

}
