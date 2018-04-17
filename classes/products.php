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
        
        public function create($name, $year, $price, $battery, $maxspeed, $acceleration, $quantity, $description, $categories) {            
            try {
                $DB = Database::getDB();
                $DB->startTransacion();
                $DB->addParam('s', $name);
                $DB->addParam('i', $year);
                $DB->addParam('i', $price);
                $DB->addParam('s', $battery);
                $DB->addParam('i', $maxspeed);
                $DB->addParam('d', $acceleration);
                $DB->addParam('i', $quantity);
                $DB->addParam('s', $description);
                $query  = "INSERT INTO Products (name, modelYear, price, battery, maxSpeed, acceleration, unitsInStock, description) ";
                $query .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                if(!$DB->query($query)) throw new Exception('Insert into Products error!');
                $result = $DB->insertID();
                
                foreach($categories as $value) {
                    $DB->clearParams();
                    $DB->addParam('i', $value);
                    $DB->addParam('i', $result);
                    $query = "INSERT INTO ProductCategory (categoryID, productID) VALUES (?, ?)";
                    if(!$DB->query($query)) throw new Exception('Insert into ProductCategory error!');
                }
                
                $DB->commit();
                
            } catch(Exception $e) {
                $DB->rollBack();
                $result = false;
            }
            
            return $result;
        }
        
        public function getAll() {
            $DB = Database::getDB();
            $query  = "SELECT productID, name, price FROM Products WHERE (unitsInStock > 0)";
            $result = $DB->query($query);
            return (!$result || !count($result)) ? array(): $result;
        }
        
        public function getTotal() {
            $DB = Database::getDB();
            $result = $DB->query("SELECT COUNT(*) as total FROM Products");
            return $result ? reset($result)['total']: 0;
        }
        
        public function getProduct($admin = false) {
            $DB = Database::getDB();
            $DB->addParam('i', $this->prodID);
            if($admin) $result = "SELECT * FROM Products WHERE (productID = ?) LIMIT 1";
            else $result = "SELECT * FROM Products WHERE (productID = ?) AND (unitsInStock > 0) LIMIT 1";
            $result = $DB->query($result);
            if(!$result || !count($result)) return $result;
            else return reset($result);
        }
        
        public function getCurrent($current) {
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
        
        public function update($name, $year, $price, $battery, $maxspeed, $acceleration, $quantity, $description, $categories) {
            try {
                $result = true;
                $DB = Database::getDB();
                $DB->startTransacion();
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
                if($DB->query($query) === false) throw new Exception('Update Products error!');
                
                $DB->clearParams();
                $DB->addParam('i', $this->prodID);
                $query = "DELETE FROM ProductCategory WHERE (productID = ?)";
                if(!$DB->query($query)) throw new Exception('Delete from ProductCategory error!');
                
                foreach($categories as $value) {
                    $DB->clearParams();
                    $DB->addParam('i', $value);
                    $DB->addParam('i', $this->prodID);
                    $query = "INSERT INTO ProductCategory (categoryID, productID) VALUES (?, ?)";
                    if(!$DB->query($query)) throw new Exception('Insert into ProductCategory error!');
                }
                
                $DB->commit();
                
            } catch(Exception $e) {
                $DB->rollBack();
                $result = false;
            }
            
            return $result;
        }
        
        public function delete() {
            try {
                $result = true;
                $DB = Database::getDB();
                $DB->startTransacion();
                $DB->addParam('i', $this->prodID);
                $query = "DELETE FROM ProductCategory WHERE (productID = ?)";
                if(!$DB->query($query)) throw new Exception('Delete from ProductCategory error!');
                
                $DB->clearParams();
                $DB->addParam('i', $this->prodID);
                $query = "DELETE FROM Products WHERE (productID = ?)";
                if(!$DB->query($query)) throw new Exception('Delete from Products error!');                
                
                $DB->commit();
                
            } catch(Exception $e) {
                $DB->rollBack();
                $result = false;
            }
            
            return $result;
        }
    }
?>