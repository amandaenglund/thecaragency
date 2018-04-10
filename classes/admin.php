<?php

    class Admin {
        
        private $admin;
        
        public function __construct() {
            if(isset($_SESSION['USER'])) {
                $params = array();
                $DB = Database::getDB();
                $params['email'] = array('s' => $_SESSION['USER']);
                $result = $DB->query("SELECT name, email FROM Admins WHERE (email = ?) AND (approved = 1) LIMIT 1", $params);
                if($result) $this->admin = reset($result);
            }
        }
        
        public function isLoggedIn() {
            return isset($this->admin);
        }
        
        public function getEmail() {
            return $this->admin['email'];
        }
        
        public function getName() {
            return $this->admin['name'];
        }
        
        public function logOut() {
            unset($_SESSION['USER']);
        }
        
        public function logIn($email, $password) {
            $params = array();
            $DB = Database::getDB(); 
            $params['email'] = array('s' => $email);
            $result = $DB->query("SELECT password FROM Admins WHERE (email = ?) AND (approved = 1) LIMIT 1", $params);
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
            $params = array();
            $DB = Database::getDB();
            $params['approved'] = array('i' => 1);
            $result = $DB->query("SELECT email, name FROM Admins WHERE (approved = ?)", $params);
            return $result ? $result : array();
        }
        
        public function approveUser($email) {
            $params = array();
            $DB = Database::getDB(); 
            $params['email'] = array('s' => $email);
            return $DB->query("UPDATE Admins SET approved = 1 WHERE (email = ?) AND (approved = 0) LIMIT 1", $params);
        }
        
        public function createUser($email, $name, $password) {
            $params = array();
            $DB = Database::getDB();
            $params['email'] = array('s' => $email);
            $params['name']  = array('s' => $name);
            $params['pass']  = array('s' => password_hash($password, PASSWORD_BCRYPT));
            return $DB->query("INSERT INTO Admins (email, name, password) VALUES (?, ?, ?)", $params);
        }
    }
    
?>