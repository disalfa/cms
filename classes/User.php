<?php

/**
 * User
 * 
 * A person or entity that can log in to the site
 */
class User
{
    /**
     * Unique identifier
     * @var integer
     */
    public $id;
    
    /**
     * Unique username
     * @var string
     */
    public $username;
    
    /**
     * Password
     * @var string
     */
    public $password;

    /**
     * Authenticate a user by username and password
     * 
     * @param string $conn Connection to the database
     * @param string $username Username
     * @param string $password Password
     * 
     * @return boolean True if credentials are correct, null otherwise
     */
    public static function authenticate($conn, $username, $password)
    {
        $sql = "SELECT *
                FROM user
                WHERE username = :username";

        $stmt = $conn->prepare($sql);
        
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        // Converts & fetches data as User class object
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        $stmt->execute();

        if ($user = $stmt->fetch()) {
            // Use password_verify to check if given password is 
            // the same as the hashed one stored in the database
            return password_verify($password, $user->password);
        }
    }

    //---------------------------------------------------------------------------------

    /**
     * Create a new user
     * 
     * @param string $conn Connection to the database
     * 
     * @return boolean True if creation was successful, false otherwise
     */
    public function create($conn)
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);        

        $sql = "INSERT INTO user (username, password)
                VALUES (:username, :password)";

        $stmt = $conn->prepare($sql);
        
        $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->password, PDO::PARAM_STR);

        // Converts & fetches data as User class object
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        if ($stmt->execute()) {
            return true;
        }
    }
}