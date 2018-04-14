<?php

    class Newsletter {
        
        public function send($subject, $body) {
            $DB = Database::getDB();
            $DB->addParam('s', $subject);
            $DB->addParam('s', $body);
            return $DB->query("INSERT INTO Newsletters (subject, body) VALUES (?, ?)");
        }
        
        public function getTotal() {
            $DB = Database::getDB();
            $result = $DB->query("SELECT COUNT(*) AS total FROM Newsletters");
            return (!$result || !count($result)) ? 0 : reset($result)['total'];
        }
        
        public function getCurrent($current) {
            $current--;
            $DB = Database::getDB();
            $DB->addParam('i', $current);
            $result = $DB->query("SELECT date, subject, body FROM Newsletters ORDER BY newsletterID DESC LIMIT ?, 1");
            return (!$result || !count($result)) ? false : reset($result);
        }
    }
    
?>