<?php

    $output = array('error' => 'Ett fel uppstod!');
    if(!isset($_POST['action'])) die(json_encode($output));
    
    require('../classes/database.php');
    require('../classes/customer.php');
    
    $customer = new Customer();
    
    if(($_POST['action'] == 'ADMIN') && isset($_POST['current'])) {
        require('../classes/admin.php');
        $total = new Admin();
        if(!$total->isSignedIn()) die(json_encode($output));
        
        $current = intval($_POST['current']); unset($_POST['current']);
        if($current <= 0) $current = 1;

        $total = $customer->getTotal();
        if($current > $total) $current = $total;
        
        if($customer = $customer->getCurrent($current)) {
            $output = array('customers' => $customer);
            $output['current'] = $current;
            $output['total']   = $total;
        }
        
    } else if(($_POST['action'] == 'SIGNIN') && isset($_POST['email']) && isset($_POST['password'])) {
        if($customer->signIn($_POST['email'], $_POST['password'])) $output['error'] = false;
        else $output['error'] = 'E-postadress eller lösenord är fel!';
        
    } else if(($_POST['action'] == 'SIGNOUT')) {
        $customer->signOut();
        $output['error'] = false;
        
    } else if(($_POST['action'] == 'changeState') && isset($_POST['orderID'])){
        if(!$customer->isSignedIn()) die(json_encode($output));
        require('../classes/order.php');
        $order = new Order($_POST['orderID']);
        if(!$order->isValid()) die(json_encode($output));
        if(!$order->changeStatus(RECEIVED)) die(json_encode($output));
        $output['error'] = false; 
    }
    
    echo json_encode($output);
?>