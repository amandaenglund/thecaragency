<?php

   require('index.php');
   require('./classes/categories.php');
   if(!isset($_GET['catid'])) die(header('Location: ./'));
   $categories = new Categories($_GET['catid']);
   if(!$categories->isValid()) die(header('Location: ./'));
   
   $products = $categories->getProducts();
   
   foreach($products as $value) {
       echo '<div>';   
       echo '<h2 style="color:white;">'.$value['name'].'</h2>';
    echo '<a href="car.php?catid='.$value['productID'].'">';
    echo '<img src="./images/'.$value['productID'].'.jpg" /></a>';
    echo "<br/>";
       echo '<button><a href="car.php?catid='.$value['productID'].'">Jag är nyfiken på denna!</a></button>';
       echo '</div>';
   }
?>

echo '<h1>'.$value['name'].'</h1>';
echo '<img src="./images/'.$value['productID'].'.jpg" />';
echo '<ul>';
echo '<li>'.$value['price'].'</li>';
echo '<li>'.$value['maxSpeed'].'</li>';
echo '<li>'.$value['acceleration'].'</li>';
echo '<li>'.$value['modelYear'].'</li>';
echo '<li>'.$value['battery'].'</li>';
echo '<li>'.$value['unitsInStock'].'</li>';
echo '<li>'.$value['description'].'</li>';
echo '</ul>';
