<?php

    class Admin {
        
        private $admin;
        
        public function __construct() {
            if(isset($_SESSION['ADMIN'])) {
                $DB = Database::getDB();
                $DB->addParam('s', $_SESSION['ADMIN']);
                $result = $DB->query("SELECT name, email FROM Admins WHERE (email = ?) AND (approved = 1) LIMIT 1");
                if($result) $this->admin = reset($result);
            }
        }
        
        public function removeUDir() {
            $files = glob('../images/'.$this->getEmail().'/*');
            foreach($files as $file){ if(is_file($file)) unlink($file); }
            $files = '../images/'.$this->getEmail();
            if(is_dir($files)) @rmdir($files);
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
            unset($_SESSION['ADMIN']);
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
                $_SESSION['ADMIN'] = $email;
                return true;
            }
        }
        
        public function unapprovedUsers() {
            $DB = Database::getDB();
            $DB->addParam('i', 0);
            $result = $DB->query("SELECT email, name FROM Admins WHERE (approved = ?)");
            return $result ? $result : array();
        }
        
        public function approveUsers($admins) {
            $result = array();
            $DB = Database::getDB();
            foreach($admins as $email) {
                $DB->clearParams();
                $DB->addParam('s', $email);
                $result[$email] = $DB->query("UPDATE Admins SET approved = 1 WHERE (email = ?) AND (approved = 0) LIMIT 1");
            }
            return $result;
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