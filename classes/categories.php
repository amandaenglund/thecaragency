<?php

    class Categories {
        
        public function isValid($catID) {
            $params = array();
            $DB = Database::getDB();
            $params['categoryID'] = array('i' => $catID);
            $params = $DB->query("SELECT id FROM Categories WHERE (categoryID = ?)", $params);
            return ($params && count($params)) ? true : false;
        }
        
        public function getAll() {
            $params = array();
            $DB = Database::getDB();
            $params = $DB->query("SELECT categoryID, name FROM Categories ORDER BY categoryID", $params);
            return ($params && count($params)) ? $params : array();
        }
        
    }
    
?>