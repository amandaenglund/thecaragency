<?php

    class Admin {
        
        private $admin;
        
        public function __construct() {
            if(isset($_SESSION['USER'])) {
                $DB = Database::getDB();
                $DB->addParam('s', $_SESSION['USER']);
                $result = $DB->query("SELECT name, email FROM Admins WHERE (email = ?) AND (approved = 1) LIMIT 1");
                if($result) $this->admin = reset($result);
            }
        }
        
        public function isSignedIn() {
            return isset($this->admin);
        }
        
        public function getEmail() {
            return $this->admin['email'];
        }
        
        public function getName() {
            return $this->admin['name'];
        }
        
        public function signOut() {
            unset($_SESSION['USER']);
        }
        
        public function signIn($email, $password) {
            $DB = Database::getDB();
            $DB->addParam('s', $email);
            $result = $DB->query("SELECT password FROM Admins WHERE (email = ?) AND (approved = 1) LIMIT 1");
            if(!$result || !count($result)) return false;
            $result = reset($result)['password'];
            $result = password_verify($password , $result);
            if(!$result) return false;
            else {
                $_SESSION['USER'] = $email;
                return true;
            }
        }
        
        public function unapprovedUsers() {
            $DB = Database::getDB();
            $DB->addParam('i', 1);
            $result = $DB->query("SELECT email, name FROM Admins WHERE (approved = ?)");
            return $result ? $result : array();
        }
        
        public function approveUser($email) {
            $DB = Database::getDB();
            $DB->addParam('s', $email);
            return $DB->query("UPDATE Admins SET approved = 1 WHERE (email = ?) AND (approved = 0) LIMIT 1");
        }
        
        public function createUser($email, $name, $password) {
            $DB = Database::getDB();
            $DB->addParam('s', $email);
            $DB->addParam('s', $name);
            $DB->addParam('s', password_hash($password, PASSWORD_BCRYPT));
            return $DB->query("INSERT INTO Admins (email, name, password) VALUES (?, ?, ?)");
        }
    }
    
?>