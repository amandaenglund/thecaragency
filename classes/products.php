<?php

class Products {

    private $admin;
    
    function __construct($prodID){
        
        
    }
    
    public function getProduct($prodID){
        $params = array();
        $DB = Database::getDB();
        $params['productID'] = array('i' => $prodID);
        $result = $DB->query("SELECT * FROM Products WHERE (productID = ?) LIMIT 1", $params);
        
        echo $result;

   }


    public function getProducts() {

        $params = array();
        $DB = Database::getDB();
        $params['productID'] = array('i' => $prodID);
        $result = $DB->query("SELECT * FROM Products WHERE (productID = ?)", $params);

   }

}


?>