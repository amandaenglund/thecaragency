<?php
   // require('../classes/database.php');
    //require('../classes/customer.php');
    //$customer = new customer();
    //if(!$customer->isLoggedIn()) die(header('Location: ../index.php'));
?>
<!doctype html>
<html lang="sv"><head>
<meta charset="utf-8" />
<link rel="stylesheet" href="../images/customer.css"/>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://use.fontawesome.com/917257e2ef.js"></script>
<title>The Car Agency - Customer</title>
</head><body>
    <div class="container">
        <div class="header">
            <a href="./"><img src="../images/knesla_logo.png" /><span>KNESLA</span></a>
            <label onclick="{$('.content>div').hide(); $('#signup').show();}"><i class="fa fa-sign-out" aria-hidden="true"></i><span>logOut</span></label>
        </div>
        <h1 style="margin:10px;">Welcome to Customer page </h1>
        <div class="content">
            <div class="specifi"></div>
            <div class="order"></div>          
        </div>
        <div class="footer">&copy; The Car Agency - Sverige <?=date('Y');?></div>
    </div>
</body></html>