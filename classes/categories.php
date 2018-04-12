<?php

    class Categories {
        
        public function isValid($catID) {
            $DB = Database::getDB();
            $DB->addParam('i', $catID);
            $result = $DB->query("SELECT categoryID FROM Categories WHERE (categoryID = ?)");
            return ($result && count($result)) ? true : false;
        }
        
        public function getAll() {
            $DB = Database::getDB();
            $result = $DB->query("SELECT categoryID, name FROM Categories ORDER BY categoryID");
            return ($result && count($result)) ? $result : array();
        }
        
    }
    
?>