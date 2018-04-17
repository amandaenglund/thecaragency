<?php

    $output = array('error' => 'Ett fel uppstod!');
    if(!isset($_POST['action'])) die(json_encode($output));
    
    require('../classes/database.php');
    require('../classes/products.php');
    require('../classes/customer.php');
    require('../classes/shipper.php'); 
    require('../classes/order.php');
    
    if(($_POST['action'] == 'SEND') && isset($_POST['orderID'])) {
        require('../classes/admin.php');
        $order = new Admin();
        if(!$order->isSignedIn()) die(json_encode($output));
        
        $order = new Order($_POST['orderID']);
        if(!$order->isValid()) die(json_encode($output));
        if($order->changeStatus(SENT)) $output['error'] = false;
        
    } else if(($_POST['action'] == 'GET') && isset($_POST['current'])) {
        require('../classes/admin.php');
        $order = new Admin();
        if(!$order->isSignedIn()) die(json_encode($output));
        
        $current = intval($_POST['current']); unset($_POST['current']);
        if($current <= 0) $current = 1;
        
        $order = new Order();
        $total = $order->getTotal();
        if($current > $total) $current = $total;
        
        if($order = $order->getCurrent($current)) { 
            $order  = new Order($order);
            $output = array('order' => $order->getOrder());
            $output['order']['statusTXT'] = $order->statusTXT($output['order']['status']);
            $output['current'] = $current;
            $output['total']   = $total;
        }        
    }
    
    echo json_encode($output);
?>