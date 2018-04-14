<?php
    
    class Subscriber {
        
        public function add($name, $email) {
            $DB = Database::getDB();
            $DB->addParam('s', $email);
            $DB->addParam('s', $name);
            return $DB->query("INSERT INTO Subscribers (email, name) VALUES (?, ?)");
        }
        
        public function getAll() {
            $DB = Database::getDB();
            return $DB->query("SELECT * FROM Subscribers ORDER BY name ASC");
        }
    }
    
?>