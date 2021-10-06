<?php
class ConnectionManager
{
    public function getConnection($databaseName)
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = $databaseName;
        $port = "3306";
                
        // Create connection
        $pdo = new PDO("mysql:host=$servername; dbname=$dbname; port=$port", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}

?>