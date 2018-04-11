<?php
    require('database.php');
class Products {

    private $admin;
    
    
    public function getProduct($prodID){
        $params = array();
        $DB = Database::getDB();
        $params['productID'] = array('i' => $prodID);
        $result = $DB->query("SELECT * FROM Products WHERE (productID = ?) LIMIT 1", $params);
        
        print_r($result);

   }


    public function getProducts() {

        $params = array();
        $DB = Database::getDB();
        
        $result = $DB->query("SELECT * FROM Products ", $params);
        return $result;
   }


}

$test = new Products();

$array = $test->getProducts();


foreach($array as $value){
    echo "<div class=".$value['productID'].">";
    echo "<img src='../images/".$value['productID'].".jpg'><br/>";
    echo $value['description']."<br/>";
    echo $value['modelYear']."<br/>";
    echo "</div>";
}


?>