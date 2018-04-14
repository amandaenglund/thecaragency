<?php require ('header.php');?>
  <div class="customerContent">
   <div class="customerDetails">
     <h2>Specifikation</h2>
     <?php $customerSpecifi = $customer->getCustomerDetails();
     foreach($customerSpecifi as $key=>$value){
     if($key == "password"){continue;} echo "<strong>".$key."</strong>"." "." : "."<i>".$value."</i>"."<br/>";}?>
     
   </div>
   <div class="customerOrders">
     <h2>Beställningar</h2>


   </div>
  </div>  
<?php require ('footer.php');?>