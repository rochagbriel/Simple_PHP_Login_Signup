<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if the email field are empty
    if (empty($email)) {
        echo "Email should not be empty";
        exit;
    }

    // Check if the password field are empty
    if (empty($password)) {
        echo "Password should not be empty";
        exit;
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
    } else {
        // show an error popup message
        echo "<script>alert('Email or password are incorrect!');</script>";
        echo "<script>window.location.href='login.html';</script>";
    }
}
?>
