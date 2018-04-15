<?php
   require ('header.php');
   require('./classes/products.php');

    
   $product = new Products($_GET['carid']);
   
   if(!$product->isValid()) die(header('Location: ./'));
   $product = $product->getProduct();
   ?>



<div id="content">
<?php 
       echo '<div class="ProduktInfo">';   
       echo '<h2 style="color:white;">'.$product['name'].'</h2>';
       echo '<h4 style="color: white;">'.$product['price'].' SEK</h4>';
    echo '<img style="width: 20%;" src="./images/'.$product['productID'].'.jpg" />';
    echo '<h3>Specifikationer</h3>';
    echo '<p>Tillverkad år: '.$product['modelYear'].'</p>';
    echo "<p>Batterityp: ".$product['battery'].'</p>';
    echo "<p>Toppfart: ".$product['maxSpeed'].'</p>';
    echo "<p>Acceleration: ".$product['acceleration'].'</p>';
    echo "<p>Antal produkter i lagret: ".$product['unitsInStock'].'</p>';
    echo "<div>Beskrivning: </br>".$product['description'].'</div>';
       echo '<button><a href="car.php?carid='.$product['productID'].'">Handla denna produkt</a></button>';
       echo '<br/>';
       echo '</div>';
  ?>


</div>




<?php require('footer.php'); ?>