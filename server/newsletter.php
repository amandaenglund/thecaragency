<?php 

 
$first_name= $_POST['subscribeFirstname']; 
$email= $_POST['subscribeEmail']; 
  
  
// Connection to DBase  
$dbc= mysqli_connect($host,$user,$password, $dbase)  
or die("Unable to select database"); 
 
 
$query= "INSERT INTO $table  ". "VALUES ('$first_name', '$email')"; 
 
mysqli_query ($dbc, $query) 
or die ("Error querying database"); 
 
echo 'Tack för din anmälan om nyhetsbrev!' . '<br>'; 
 
mysqli_close($dbc); 
 
?> 
 