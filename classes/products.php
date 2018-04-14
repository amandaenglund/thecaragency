<?php
    class Products {
        
        private $prodID = 0;
        
        function __construct($prodID = 0) {
            if($prodID) {
                $DB = Database::getDB();
                $DB->addParam('i', $prodID);
                $result = $DB->query("SELECT productID FROM Products WHERE (productID = ?) LIMIT 1");
                if(is_array($result) || count($result)) $this->prodID = reset($result)['productID'];
            }
        }
        
        public function isValid() {
            return $this->prodID;
        }
        
        public function create($name, $year, $price, $battery, $maxspeed, $acceleration, $quantity, $description) {
            $DB = Database::getDB();
            $DB->addParam('s', $name);
            $DB->addParam('i', $year);
            $DB->addParam('i', $price);
            $DB->addParam('s', $battery);
            $DB->addParam('i', $maxspeed);
            $DB->addParam('d', $acceleration);
            $DB->addParam('i', $quantity);
            $DB->addParam('s', $description);
            $result  = "INSERT INTO Products (name, modelYear, price, battery, maxSpeed, acceleration, unitsInStock, description) ";
            $result .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $result  = $DB->query($result);
            return $result ? $DB->insertID() : $result;
        }
        
        public function getTotal() {
            $DB = Database::getDB();
            $result = $DB->query("SELECT COUNT(*) as total FROM Products");
            return $result ? reset($result)['total']: 0;
        }
        
        public function getProduct() {
            $DB = Database::getDB();
            $DB->addParam('i', $this->prodID);
            $result = $DB->query("SELECT * FROM Products WHERE (productID = ?) LIMIT 1");
            if(!$result || !count($result)) return $result;
            else return reset($result);
        }
        
        private function insertCategory($catID) {
            $DB = Database::getDB();
            $DB->addParam('i', $catID);
            $DB->addParam('i', $this->prodID);
            return $DB->query("INSERT INTO ProductCategory (categoryID, productID) VALUES (?, ?)");
        }

        public function insertCategories($categories) {
            foreach($categories as $category) {
                if(!$this->insertCategory($category)) return false;
            }
            return true;
        }
        
        public function producID($current) {
            $current--;
            $DB = Database::getDB();
            $DB->addParam('i', $current);
            $result = $DB->query("SELECT productID FROM Products ORDER BY productID DESC LIMIT ?, 1");
            return $result ? reset($result)['productID'] : 0;
        }
        
        public function getCategories() {
            $DB = Database::getDB();
            $DB->addParam('i', $this->prodID);
            $result = $DB->query("SELECT categoryID FROM ProductCategory WHERE (productID = ?)");
            if(!$result || !count($result)) return $result;
            
            $categories = array();
            foreach($result as $value) {
                array_push($categories, $value['categoryID']);
            }
            return $categories;            
        }
        
        public function update($name, $year, $price, $battery, $maxspeed, $acceleration, $quantity, $description) {
            $DB = Database::getDB();
            $DB->addParam('s', $name);
            $DB->addParam('i', $year);
            $DB->addParam('i', $price);
            $DB->addParam('s', $battery);
            $DB->addParam('i', $maxspeed);
            $DB->addParam('d', $acceleration);
            $DB->addParam('i', $quantity);
            $DB->addParam('s', $description);
            $DB->addParam('i', $this->prodID);
            $query  = "UPDATE Products SET name = ?, modelYear = ?, price = ?, battery = ?, maxSpeed = ?, acceleration = ?, ";
            $query .= "unitsInStock = ?, description = ? WHERE(productID = ?)";
            return $DB->query($query);
        }
        
        public function updateCategories($categories) { 
            $DB = Database::getDB();
            $DB->addParam('i', $this->prodID);
            $result = $DB->query("DELETE FROM ProductCategory WHERE (productID = ?)");
            if($result === false) return $result;
            else return $this->insertCategories($categories);
        }
    }
?>