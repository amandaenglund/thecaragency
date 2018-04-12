<?php
require('./classes/database.php');
require('./classes/customer.php');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>The Car Agency</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="./styles/style.css" />
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v5.0.9/js/all.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="./scripts/script.js"></script>
</head>
<body>
<header>
    <div class="logo">
        <img src="./images/knesla_logo.png" alt="Knesla Logo" width="150px;">     
    </div>
    <div class="state">
      <div class="login">
        <a class="loginbtn"><i class="fas fa-sign-in-alt"></i></a>
        <div class="dropdown-login">
          <p>EMAIL:</p> <input type="text" name="firstname" value="Mickey">
          <p>PASSWORD:</p>  <input type="text" name="lastname" value="Mouse">
          <input type="submit" value="Submit">
        </div>
      </div>
      <div class="logout">
      <a class="logoutbtn"><i class="fas fa-user-circle"></i></i></a>
        <div class="dropdown-logout">
          <a href="#"><p>MY ACCOUNT</p></a>
          <input type="button" onclick="alert('Hello World!')" value="LOGOUT">
        </div>
      </div>
      <div class="kassa">
        <a href="#" class="kassabtn"><i class="fas fa-cart-arrow-down"></i><small>(0)</small></a> 
      </div>
    </div>       
</header>


<!--Navigationskategorierna kommer sedan att importeras från Json eller databasen-->
<nav>
Model 1 |<!--bara placeholder för nu-->
Model 2 |<!--bara placeholder för nu-->
Model 3 |<!--bara placeholder för nu-->
Sport <!--bara placeholder för nu-->
</nav>