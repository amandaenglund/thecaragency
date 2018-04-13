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
<div id="background">
<header>
    <ul>
        <li id="logo"><img src="./images/knesla_logo.png" alt="Knesla Logo" width="150px;"></li>
        <li><i class="fas fa-user-circle"></i> Hej Elon</li>
        <li><i class="fas fa-user-circle"></i> Mitt konto</li>
        <li><i class="fas fa-shopping-cart"></i> (0)</li>
    </ul>
</header>

<!--Navigationskategorierna kommer sedan att importeras från Json eller databasen-->
<nav>
    Model 1 |<!--bara placeholder för nu-->
    Model 2 |<!--bara placeholder för nu-->
    Model 3 |<!--bara placeholder för nu-->
    Sport <!--bara placeholder för nu-->
</nav>




<div id="content">
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<?php
echo "Hello World!";
?>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
</div>  
</div>
<footer>
    <div id="newsletter">
        <p>Prenumerera på Kneslas nyhetsbrev</p>
        <input type="text" id="subscribeName" placeholder="Ditt namn" /><br />
        <input type="text" id="subscribeEmail" placeholder="Din e-post"/><br />
        <input type="submit"  value="OK" onclick="Subscribe()" />
    </div>
    <div id="about">
        <p>The Car Agency</p>
        <p>Kungsgatan 100</p>
        <p>410 10 Göteborg</p>
    </div>
</footer>
</body>
</html>