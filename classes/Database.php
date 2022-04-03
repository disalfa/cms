<?php

/**
 * Database
 * 
 * A connection to the database
 */
class Database
{
    /**
     * Get the database conection
     * 
     * @return PDO object Connection to the database server
     */
    public function getConn()
    {
        $db_host = "localhost";
        $db_name = "cms";
        $db_user = "cms_www";
        $db_pass = "KCdsri3-9]56SZEl";

        // Create dsn (data source name) a string that specifies host name, db name, etc.
        $dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8';

        try {
            $db = new PDO($dsn, $db_user, $db_pass);

            // Catches any exceptions during database connection process
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $db;
            
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}