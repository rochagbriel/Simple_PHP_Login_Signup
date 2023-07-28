<?php
class User {
    private $email;
    private $passwordHash;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPasswordHash() {
        return $this->passwordHash;
    }

    public function checkPassword($password) {
        return password_verify($password, $this->passwordHash);
    }
}
?>