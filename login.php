<?php

require 'includes/init.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $conn = require 'includes/db.php';

    if (User::authenticate($conn, $_POST['username'], $_POST['password'])) {       
        
        Auth::login();       
        
        Url::redirect('/cms');
    
    } else {
        
        $error = "Login incorrect";
    }
}

?>

<?php require 'includes/header.php'; ?>

<h2>Login</h2>

<?php if (! empty($error)) : ?>
    <p> <?= $error ?> </p>
<?php endif ?>

<form method="post">

    <div>
        <label for="username">Username:</label>
        <input name="username" id="username">
    </div>

    <div>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
    </div>

    <button>Log in</button>
    
    <a href="create-user.php">New User</a>

</form>

<?php require 'includes/footer.php'; ?>