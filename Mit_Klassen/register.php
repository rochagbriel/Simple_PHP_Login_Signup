<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    // Server-side validation
    // Check if the fields are empty
    if (empty($email) || empty($password) || empty($confirmPassword)) {
        echo "Please fill all the fields.";
    // Check if the email is valid
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Please enter a valid email address.";
    // Check if the password is valid
    } elseif (strlen($password) < 8 || preg_match('/\s/', $password)) {
        echo "The password must have at least 8 characters and no spaces.";
    } elseif ($password !== $confirmPassword) {
        echo "The passwords do not match. Please try again.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Store the email and hashed password in a file
        $data = $email . " hash: " . $hashedPassword . "\n";
        fwrite(fopen("users_klassen.txt", "a"), $data);

        // Display a success message and redirect to index.php
        if (file_exists("users_klassen.txt") && file_get_contents("users_klassen.txt") !== "") {
            echo 
            "<script>
                alert('You are registered!');
                window.location.href='index.php';
            </script>";
        } else {
            echo "<script>alert('Something went wrong, please try again!');</script>";
        }
    }
}
