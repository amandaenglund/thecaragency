<?php
if(!isset($_POST['action'])) die('Ett fel uppstod!');

require('../classes/database.php');
require('../classes/customer.php');
$customer = new Customer;

if(($_POST['action'] == 'CSIGNIN') && isset($_POST['email']) && isset($_POST['password'])) {   
    if($customer->signIn($_POST['email'], $_POST['password'])){ 
    echo 1;
    }else {echo 0;}   
} 

if(($_POST['action'] == 'CSIGNOUT')) {     
    $customer->signOut();      
} 



?>
