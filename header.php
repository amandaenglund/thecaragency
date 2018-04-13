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
    <link rel="stylesheet" type="text/css" media="screen" href="./styles/style.css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v5.0.9/js/all.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Allerta Stencil' rel='stylesheet'>
    <script src="./scripts/script.js"></script>
</head>
<body>
<div id="background">
<header>
    <ul>
        
        <li id="logo"><img src="./images/knesla_logo.png" alt="Knesla Logo" width="150px;">The Car Agency</li>
        <li><i class="fa fa-sign-in" aria-hidden="true"></i>Hej Elon</li>
        <li><i class="fas fa-user-circle"></i> Mitt konto</li>
        <li><i class="fas fa-shopping-cart"></i> (0)</li>
    </ul>

</header>


<!--Navigationskategorierna kommer sedan att importeras från Json eller databasen-->
<nav>
<a>Model 1</a> |<!--bara placeholder för nu-->
<a>Model 2 </a>|<!--bara placeholder för nu-->
<a>Model 3 </a>|<!--bara placeholder för nu-->
<a>Sport </a><!--bara placeholder för nu-->
</nav>