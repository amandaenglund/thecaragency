<?php
require '../classes/databas.php';

function adminLogin($email,$password){
  $admindb = new Admin();
  $results = $admindb->login($email);
  if($results){
    if(password_verifying($password,$results['password'])){
      if($results['approved'] == 1){
        return true;
      }else{
        return 'Not approved';
      }
    }else{
      return 'Password is not valid';
    }
  }else{
    return 'Email is not valid';
  }
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
