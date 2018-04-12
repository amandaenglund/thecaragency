<?php
    class Products {
        
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

        public function getProduct($prodID) {
            $DB = Database::getDB();
            $DB->addParam('i', $prodID);
            return $DB->query("SELECT * FROM Products WHERE (productID = ?) LIMIT 1");
        }
        
        public function insertCategory($catID, $prodID) {
            $DB = Database::getDB();
            $DB->addParam('i', $catID);
            $DB->addParam('i', $prodID);
            return $DB->query("INSERT INTO ProductCategory (categoryID, productID) VALUES (?, ?)");
        }
        
        public function update($name, $year, $price, $battery, $maxspeed, $acceleration, $quantity, $description) {
            
        }
        

    }
?>