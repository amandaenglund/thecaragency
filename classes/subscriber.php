<?php
    
    class Subscriber {
        
        public function add($name, $email) {
            $params = array();
            $DB = Database::getDB();
            $params['email'] = array('s' => $email);
            $params['name'] = array('s' => $name);
            return $DB->query("INSERT INTO Subscribers (email, name) VALUES (?, ?)", $params);
        }
        
        public function getAll() {
            $params = array();
            $DB = Database::getDB();
            return $DB->query("SELECT * FROM Subscribers", $params);
        }
    }
    
?>