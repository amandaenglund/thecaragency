<?php
   require ('header.php');
   
   require('classes/products.php');
   
   if(isset($_GET['catid'])) {
       $products = new Categories($_GET['catid']);
       if(!$products->isValid()) die(header('Location: ./'));
       
       $catName = $products->getCategory();
       $catDesc = $catName['description'];
       $catName = $catName['name'];
       $products = $products->getProducts();

   } else {
       $catName = 'Alla Kneslor';
       $catDesc = 'The Car Agency´s modell KNESLA står för hållbar utveckling, funktionalitet och en drömisk, skön känsla på vägen.';
       $products = new Products();
       $products = $products->getAll();
   }
   
?>
<div class="content">
  <?php 
  echo '<div style="padding: 0px 50px;">';  
  echo '<h1 class="catName">'. $catName. '</h1>';
  echo '<h4>'. $catDesc. '</h4>';
  echo '</div>';
  foreach($products as $value) {
       echo '<div>';   
       echo '<h2 style="color:white;">'.$value['name'].'</h2>';
       echo '<h4 style="color: white;">'.$value['price'].' SEK</h4>';
    echo '<a href="car.php?carid='.$value['productID'].'">';
    echo '<img src="./images/'.$value['productID'].'.jpg" /></a>';
    echo "<br/>";
       echo '<button class="nyfikenButton"><a href="car.php?carid='.$value['productID'].'">Jag är nyfiken på denna!</a></button>';
       echo '<br/>';
       echo '<br/>';
       echo '<br/>';
       echo '</div>';
  }
       ?>
</div>

<?php require('footer.php'); ?>