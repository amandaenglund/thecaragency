<?php 
    require ('header.php');
    if(!$customer->isSignedIn()) die(header('Location: ./'));
    require('./classes/order.php')
?>
  <div class="customerContent">
  <div class="customerDetails">
     <h2>Kunduppgifter</h2>
     <?php 
         $customerInfo = $customer->getCustomer();
         foreach($customerInfo as $key => $value) {
            echo "<strong>".$key."</strong>"." "." : "."<i>".$value."</i>"."<br/>";
         }
     ?>
   </div>
   <div class="customerOrders">
     <h2>Beställningar</h2>
       <table><tr><th>Order nummer</th><th>Status</th><th>Model</th></tr>
       <?php  
           $beställningar = $customer->gerOrders();
           if($beställningar){
           $g="";
           foreach($beställningar as $key1=>$array1){ if($array1['orderID'] !== $g){ $x = 0; echo "<tr>";
            foreach($beställningar as $key2=>$array2){
              if($array1['orderID'] == $array2['orderID']){
                if($array2['orderID'] !== $x){
                  echo "<td>".$array2['orderID']."</td>";
                  if($array2['status'] == '1'){echo "<td style='color:#158200;'>mottagen</td>";}elseif($array2['status'] == '0'){echo "<td  style='color:#0e0187;'><i>Skickad</i><br><input class='status' id='".$array2['orderID']."' type='button' onclick='changeState()' value='mottagen'></td>";}else{echo "<td style='color:#870101;'>Beställd</td>";}
                  echo "<td>".$array2['name']."</td>";
                  $x = $array2['orderID'] ;
                }else{ echo "<td>".$array2['name']."</td>";}}
            }$x = 0; $g = $array1['orderID']; echo "</tr>";}
           }
          }
       ?>
       </table>
   </div>
  </div>  
<?php require ('footer.php');?>