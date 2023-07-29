<?php

class User {

    private $email;
    private $password;
    private $confirmPassword;
    private $passwordHash;
    public $isLoggedIn = false;

    public function __construct($email) {
        $this->email = $email;
    }

    public function registerUser($password, $confirmPassword) {
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;

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
        if ($this->userExists($this->email)) {
            throw new Exception("This email is already registered!");
        }
        $this->hashPassword($this->password);

        $data = $this->email . " hash: " . $this->passwordHash . "\n";
        fwrite(fopen("users_klassen.txt", "a"), $data);

        if (!file_exists("users_klassen.txt")) {
            throw new Exception("The storage file does not exist!");
        }
    }

    private function hashPassword($password) {
        $this->passwordHash = password_hash($this->password, PASSWORD_DEFAULT);
    }

    private function emptyInput() {
        if (empty($this->email) || empty($this->password) || empty($this->confirmPassword)) {
            return true;
        } else {
            return false;
        }
    }

    private function emptyLoginInput() {
        if (empty($this->email) || empty($this->password)) {
            return true;
        } else {
            return false;
        }
    }

    private function invalidEmail() {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    private function passwordMatch() {
        if ($this->password !== $this->confirmPassword) {
            return true;
        } else {
            return false;
        }
    }

    private function passwordLength() {
        if (strlen($this->password) < 8) {
            return true;
        } else {
            return false;
        }
    }

    private function userExists($email) {
        $usersList = file("users_klassen.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($usersList as $storedUser) {
            list($storedEmail, $hashedPassword) = explode(" hash: ", $storedUser);
            if ($this->email === $storedEmail) {
                return true;
            }
        }
        return false;
    }

    private function authPassword() {
        $usersList = file("users_klassen.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($usersList as $storedUser) {
            list($storedEmail, $hashedPassword) = explode(" hash: ", $storedUser);
            if ($this->email === $storedEmail && password_verify($this->password, $hashedPassword)) {
                return true;
            }
        }
        return false;
    }

    public function userLogin($password) {
        $this->password = $password;

        if ($this->emptyLoginInput()) {
            throw new Exception("Please fill all the fields.");
        }
        if ($this->invalidEmail()) {
            throw new Exception("Please enter a valid email address.");
        }
        if ($this->passwordLength()) {
            throw new Exception("The password must have at least 8 characters.");
        }
        if (!$this->userExists($this->email)) {
            throw new Exception("This email is not registered!");
        }
        if (!$this->authPassword($this->password)) {
            throw new Exception("Email or password are incorrect!");
        } else {
            $this->isLoggedIn = true;
        }
    }

}
?>