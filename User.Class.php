<?php

class User {

    private $email;
    private $password;
    private $confirmPassword;
    private $passwordHash;

    public function __construct($email, $password, $confirmPassword) {
        $this->$email = $email;
        $this->$password = $password;
        $this->$confirmPassword = $confirmPassword;
    }

    public function signUser() {
        if ($this->emptyInput()) {
            throw new Exception("Please fill all the fields.");
        }
        if ($this->invalidEmail()) {
            throw new Exception("Please enter a valid email address.");
        }
        if ($this->passwordMatch()) {
            throw new Exception("The passwords do not match. Please try again.");
        }
        if ($this->passwordLength()) {
            throw new Exception("The password must have at least 8 characters.");
        }
        if ($this->userExists($this->$email)) {
            throw new Exception("This email is already registered!");
        }
        $this->hashPassword($this->$password);
        $data = $this->$email . " hash: " . $this->$passwordHash . "\n";

        if (file_exists("users_klassen.txt") && file_get_contents("users_klassen.txt") !== "") {
            fwrite(fopen("users_klassen.txt", "a"), $data);
        } else {
            throw new Exception("The storage file does not exist or is empty!");
        }
    }

    private function hashPassword($password) {
        $this->$passwordHash = password_hash($this->$password, PASSWORD_DEFAULT);
    }

    private function emptyInput() {
        if (empty($this->$email) || empty($this->$password) || empty($this->$confirmPassword)) {
            return true;
        } else {
            return false;
        }
    }

    private function invalidEmail() {
        if (!filter_var($this->$email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    private function passwordMatch() {
        if ($this->$password !== $this->$confirmPassword) {
            return true;
        } else {
            return false;
        }
    }

    private function passwordLength() {
        if (strlen($this->$password) < 8) {
            return true;
        } else {
            return false;
        }
    }

    private function userExists($email) {
        $usersList = file("users_klassen.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($usersList as $storedUser) {
            list($storedEmail, $hashedPassword) = explode(" hash: ", $storedUser);
            if ($this->$email === $storedEmail) {
                return true;
            }
        }
        return false;
    }



}
?>