<?php
    require('../classes/database.php');
    require('../classes/admin.php');
    
    $admin = new Admin();
    if(!$admin->isSignedIn()) die(header('Location: ./'));
?>
<!doctype html>
<html lang="sv"><head>
<meta charset="utf-8" />
<link rel="stylesheet" href="../styles/admin.css"/>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://use.fontawesome.com/917257e2ef.js"></script>
<script src="../scripts/admin.js"></script>
<title>The Car Agency - Administration</title>
</head><body>
    <div class="container">
        <div class="header">
            <a href="./home.php"><img src="../images/knesla_logo.png" /><span>KNESLA</span></a>
            <a href="./add.php"><i class="fa fa-car"></i><span>Lägg till</span></a>
            <a href="./edit.php"><i class="fa fa-car"></i><span>Redigera</span></a>
            <a href="./orders.php"><i class="fa fa-shopping-cart"></i><span>Orderlista</span></a>
            <a href="./newsletter.php"><i class="fa fa-envelope"></i><span>Nyhetsbrev</span></a>
            <a href="./customers.php"><i class="fa fa-users"></i><span>Kundlista</span></a>
            <a href="./signout.php"><i class="fa fa-sign-out"></i><span>Logga ut</span></a>
        </div>