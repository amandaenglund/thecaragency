<?php
require '../classes/databas.php';

function adminLogin($email,$password){
  $admindb = new Admin();
  $results = $admindb->login($email);
  return password_verifying($password,$results['password']);
}


function password_encrypting($password){
 return password_hash($password, PASSWORD_DEFAULT);
}

 
function password_verifying($password,$hash){
  return password_verify($password, $hash );
}

function signupAdmin($email,$password,$name){
$admindb = new Admin();
$password = password_encrypting($password);
$admindb->signUp($email,$password,$name);
}

?>
