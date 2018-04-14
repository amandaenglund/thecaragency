<?php

    class Shipper {
        
        private $shipperID = 0;
        
        function __construct($shipperID = 0) {
            if($shipperID) {
                $DB = Database::getDB();
                $DB->addParam('i', $shipperID);
                $result = $DB->query("SELECT shipperID FROM Shippers WHERE (shipperID = ?) LIMIT 1");
                if($result && count($result)) $this->shipperID = reset($result)['shipperID'];
            }
        }
        
        public function isValid() {
            return $this->shipperID;
        }
        
        public function getCost($quantity) {
            $DB = Database::getDB();
            $DB->addParam('i', $this->shipperID);
            $result = $DB->query("SELECT costPerUnit FROM Shippers WHERE (shipperID = ?) LIMIT 1");
            return ($result && count($result)) ? ($quantity * reset($result)['costPerUnit']) : false;
        }
        
        public function getAll() {
            $DB = Database::getDB();
            $result = $DB->query("SELECT shipperID, name, deliveryTime FROM Shippers ORDER BY shipperID ASC");
            return ($result && count($result)) ? $result : array();
        }
    }
    
?>