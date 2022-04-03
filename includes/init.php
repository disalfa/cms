<?php

/**
 * Initialisations
 * 
 * Register an autoloader, start or resume the session etc
 */

spl_autoload_register(function ($class)
{
    // Returns the parent folder of the file calling init.php
    require dirname(__DIR__) . "/classes/{$class}.php";
});

session_start();