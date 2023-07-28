<?php

$errors = [];       // Array to store all the errors

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if the email field are empty
    if (empty($email)) {
        $errors[] = "Email should not be empty";
    }

    // Check if the password field are empty
    if (empty($password)) {
        $errors[] = "Password should not be empty";
    }

    // Read the file and store the content in an array
    $users = file("users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Interate over the array and check if the user exists
    $userFound = false;
    foreach ($users as $user) {
        list($storedEmail, $hashedPassword) = explode(" hash: ", $user);
        if ($email === $storedEmail && password_verify($password, $hashedPassword)) {
            $userFound = true;
            break;
        }
    }

    if ($userFound) {
        // show a success popup message and redirect to index.php
        echo "<script>alert('You are logged in!');</script>";
        echo "<script>window.location.href='index.php';</script>";
        exit;
    } else {
        $errors[] = "Email or password are incorrect!";
    }
}
?>
<html>
<head>
    <title>Login-Page</title>
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
