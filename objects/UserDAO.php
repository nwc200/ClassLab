<?php
class UserDAO{

    // // Add a new user to the database
    // public function add($username, $hashedPassword){
    //     $conn_manager = new ConnectionManager();
    //     $pdo = $conn_manager->getConnection();
        
    //     $sql = "insert into user (username, password)
    //             values (:username, :hashed_password)";
    //     $stmt = $pdo->prepare($sql);
    //     $stmt->bindParam(":username", $username);
    //     $stmt->bindParam(":hashed_password", $hashedPassword);
    //     $status = $stmt->execute();

    //     $stmt = null;
    //     $pdo = null;
    //     return $status;
    // }

    // Retrieve a user with a given username
    // Return null if no such user exists
    public function retrieve($username)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("user");
        
        $sql = "select * from user where userName=:username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        
        $user = null;
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($row = $stmt->fetch()) {
            $user = new User($row["userName"], $row["name"], $row["emailAddr"], $row["department"], $row["designation"], $row["roles"]);
        }
        
        $stmt = null;
        $pdo = null;
        return $user;
    }

    public function getTrainer()
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("user");
        
        $sql = "select username from user where roles='Trainer'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        $user = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $user[] = $row["username"];
        }
        
        $stmt = null;
        $pdo = null;
        return $user;
    }
}
?>