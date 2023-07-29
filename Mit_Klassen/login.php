<?php

require_once "classes/User.php";

$errors = [];

try {
    $user = new User($_POST["email"], $_POST["password"]);
    $user->login();
} catch (Exception $e) {
    $errors[] = $e;
}      

?>
<html>
<head>
    <title>Login-Page(mit Klassen)</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <section>
        <h1>Login</h1>
        <?php if (!empty($errors)) {
            echo "<ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
        } ?>
            
        <form id="loginForm" method="post" action="login.php" onsubmit="return validateForm()">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required><br>

            <input class="btn btn-input" type="submit" value="Submit">
        </form>
        <a class="link" href="register.php">Don't have an account? Register here.</a>
        <a class="btn" href="index.php">Back to main menu</a>
    </section>
</body>
<script>
    function validateForm() {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        if (email.trim() === '' || password.trim() === '') {
            alert('Please fill all the fields.');
            return false;
        }

        return true;
    }
</script>
</html>
