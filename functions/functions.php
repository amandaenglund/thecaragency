<?php
session_start();
require '../classes/databas.php';

function adminLogin($email,$password){
  $admindb = new Admin();
  $results = $admindb->login($email,$password);
  if($results){
    $_SESSION['admin']=$results['email'];
  }else{
    return 0;
  }

}
adminLogin('','');


?>