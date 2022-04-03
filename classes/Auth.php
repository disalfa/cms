<?php

/**
 * Authentication
 * 
 * Login and logout
 */
class Auth
{
    /**
    * Return the user authentication status
    * 
    * @return boolean True if a user is logged in, false otherwise
    */
    public static function isLoggedIn()
    {
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];
    }

    //---------------------------------------------------------------------------------

    /**
     * Require the user to be logged in, stopping with an 'unauthorised' message if not
     * 
     * @return void
     */
    public static function requireLogin()
    {
        // To call a method in the same class from within another method, 
        // you either need to prefix it with static:: (for static methods) 
        // or $this-> (for instance methods).
        if (! static::isLoggedIn())
        {
            die("unauthorised");
        }
    }

    //---------------------------------------------------------------------------------

    /**
     * Log in using the session and print out username of current logged in user
     * 
     * @return void
     */
    public static function login()
    {
        // Change session id after login to prevent hackers hack credentials
        // (known as session fixation attacks)
        session_regenerate_id(true);
        
        $_SESSION['is_logged_in'] = true;

        $_SESSION['current_user'] = "'". $_POST['username'] . "'";
    }

    //---------------------------------------------------------------------------------

    public static function logout()
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
    
            setcookie(
                session_name(),
                '', time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    }
}