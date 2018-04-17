<?php
    
    class Categories {
        
        private $categoryID = 0;
        
        function __construct($catID = 0) {
            if($catID) {
                $DB = Database::getDB();
                $DB->addParam('i', $catID);
                $result = $DB->query("SELECT categoryID FROM Categories WHERE (categoryID = ?) LIMIT 1");
                if(is_array($result) || count($result)) $this->categoryID = reset($result)['categoryID'];
            }
        }
        
        public function getCategory() {
            $DB = Database::getDB();
            $DB->addParam('i', $this->categoryID);
            $result = $DB->query("SELECT name, description FROM Categories WHERE (categoryID = ?) LIMIT 1");
            return ($result && !count($result)) ? $result : reset($result);
        }
        
        public function isValid() {
            return isset($this->categoryID);
        }
        
        public function getAll() {
            $DB = Database::getDB();
            $result = $DB->query("SELECT categoryID, name FROM Categories ORDER BY categoryID ASC");
            return ($result && count($result)) ? $result : array();
        }
        
        public function getProducts() {
            $DB = Database::getDB();
            $DB->addParam('i', $this->categoryID);
            $result = "SELECT productID FROM ProductCategory WHERE (categoryID = ?)";
            $result = $DB->query($result);
            if(!$result || !count($result)) return array();
            
            $products = array();
            foreach($result as $value) {
                $temp = new Products($value['productID']);
                $temp = $temp->getProduct();
                if(isset($temp['productID'])) array_push($products, $temp);
            }
            
            return $products;
        }
    }
    
?>