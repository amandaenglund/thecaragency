<?php
  require('classes/database.php');
  require('classes/customer.php');
  require('classes/categories.php');
  $customer = new Customer;
  $categorie = new Categories;
?> 
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>The Car Agency</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="./styles/style.css" />   
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v5.0.9/js/all.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="./scripts/script.js"></script>
  </head>
 <body>
  <header>
    <div class="logo">
        <a href="index.php"><img src="./images/knesla_logo.png" alt="Knesla Logo" width="150px;"></a>     
    </div>
    <div class="state">
      <div class="login" <?php if($customer->isSignedIn()){echo "style='display:none;'";}?>>
        <a class="loginbtn"><i class="fas fa-sign-in-alt"></i><small>Logga in</small></a>
        <div class="dropdown-login">  
          <p>E-postadress:</p> <input type="email" name="email">
          <p>Lösenord:</p>  <input type="password" name="password">
          <input type="button" onclick="customerSignIn()" value="Logga in">
        </div>
      </div>
      <div class="logout" <?php if(!$customer->isSignedIn()){echo "style='display:none;'";}?>>
      <a class="logoutbtn"><i class="fas fa-user-circle"></i></i><small><?php if($customer->isSignedIn()){echo $customer->getCustomerName();} ?></small></a>
        <div class="dropdown-logout">
          <a href="customer.php"><p>Mitt konto</p></a>
          <input type="button" onclick="customerSignOut()" value="Logga ut">
        </div>
      </div>
      <div class="kassa">
        <a href="#" class="kassabtn"><i class="fas fa-cart-arrow-down"></i><small>(0)</small></a> 
      </div>
    </div>       
  </header>

  <div class="nav">
    <?php 
      $categorie = $categorie->getAll();
      foreach($categorie as $key=>$array){echo "<a href=cars.php?catid=".$array['categoryID'].">".$array['name']."</a>"." ";}?>
  </div>