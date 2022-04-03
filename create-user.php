<?php

require 'includes/init.php';

$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = require 'includes/db.php';

    $user->username = $_POST['username'];
    $user->password = $_POST['password'];

    if ($user->create($conn)) {
        Url::redirect('/cms');
    }
}


?>
<?php require 'includes/header.php'; ?>

<h2>Create New User</h2>

<form method="post">

    <div>
        <label for="username">Username:</label>
        <input name="username" id="username">
    </div>

    <div>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
    </div>

    <button>Register</button>

</form>

<?php require 'includes/footer.php'; ?>