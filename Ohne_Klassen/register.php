<?php

$errors = [];       // Array to store all the errors

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    // Server-side validation
    // Check if the fields are empty
    if (empty($email) || empty($password) || empty($confirmPassword)) {
        $errors[] = "Please fill all the fields.";
    // Check if the email is valid
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    // Check if the password is valid
    } elseif (strlen($password) < 8) {
        $errors[] = "The password must have at least 8 characters.";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "The passwords do not match. Please try again.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Store the email and hashed password in a file
        $data = $email . " hash: " . $hashedPassword . "\n";
        fwrite(fopen("users.txt", "a"), $data);

        // Display a success message and redirect to index.php
        if (file_exists("users.txt") && file_get_contents("users.txt") !== "") {
            echo 
            "<script>
                alert('You are registered!');
                window.location.href='index.php';
            </script>";
        } else {
            $errors[] = "Email or password are incorrect!";
        }
    }
}
?>
<html>
<head>
    <title>Signup-Page</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body> 
    <section class="container">
        <h1>Create your account:</h1>
        <?php if (!empty($errors)) {
            echo "<ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
        } ?>
        <form id="registrationForm" onsubmit="return validateForm()" action="register.php" method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br>
            
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br>
            
            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" name="confirmPassword" id="confirmPassword" required><br>
            
            <input class="btn btn-input" type="submit" value="Submit">
            <p id="errorText" class="error-text"></p>
            <a class="link" href="login.php">Already have an account? Login here.</a>
        </form>
        <a class="btn" href="index.php">Back to main menu</a>
    </section>
    
    <script>
        function validateForm() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorText = document.getElementById('errorText');

            // Check if all fields are filled
            if (email.trim() === '' || password.trim() === '' || confirmPassword.trim() === '') {
                errorText.textContent = 'Please fill all the fields';
                return false;
            }

            // Check if the passwords match
            if (password !== confirmPassword) {
                errorText.textContent = 'The passwords do not match. Please try again.';
                return false;
            }
            
            // Check if the password has at least 8 characters and does not contain spaces
            if (password.length < 8) {
                errorText.textContent = 'The password must have at least 8 characters.';
                return false;
            }

            // If everything is ok, return true and submit the form
            return true;
        }
    </script>
</body>
</html>
