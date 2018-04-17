<?php

    $output = array('error' => 'Ett fel uppstod!');
    if(empty($_POST['action'])) die(json_encode($output));
    
    require('../classes/database.php');
    require('../classes/products.php');
    require('../classes/cart.php');
    require('../classes/shipper.php');
    require('../classes/order.php');
    require('../classes/customer.php');
    
    if($_POST['action'] == 'ORDER') {
        $customer = array();
        $customer['companyName'] = trim(preg_replace('/\s+/', ' ', @$_POST['companyName']));
        
        $customer['contactName'] = trim(preg_replace('/\s+/', ' ', @$_POST['name']));
        if($customer['contactName'] == '') { $output['error'] = 'Ange ditt namn!'; die(json_encode($output)); }
        
        $customer['phoneNumber'] = preg_replace("/[^0-9]/", "", @$_POST['phone']);
        if($customer['phoneNumber'] == '') { $output['error'] = 'Ange ditt telefonnummer!'; die(json_encode($output)); }
        
        $customer['email'] = trim(strtolower(@$_POST['email']));
        if(!filter_var($customer['email'], FILTER_VALIDATE_EMAIL)) {
            $output['error'] = 'Ange din e-postadress!';
            die(json_encode($output));
        }
        
        $customer['address'] = trim(preg_replace('/\s+/', ' ', @$_POST['address']));
        if($customer['address'] == '') { $output['error'] = 'Ange din adress!'; die(json_encode($output)); }
        
        $customer['postalCode'] = trim(preg_replace('/\s+/', ' ', @$_POST['zipcode']));
        if($customer['postalCode'] == '') { $output['error'] = 'Ange ditt postnummer!'; die(json_encode($output)); }
        
        $customer['city'] = trim(preg_replace('/\s+/', ' ', @$_POST['city']));
        if($customer['city'] == '') { $output['error'] = 'Ange din stad!'; die(json_encode($output)); }
        
        if(empty($_POST['shipperID'])) { $output['error'] = 'välj en fraktmetod!'; die(json_encode($output)); }
        $shipperID = intval($_POST['shipperID']);
        
        $subscriber = false;
        if(isset($_POST['newsletter']) && intval($_POST['newsletter'])) $subscriber = true;
        
        $cart = new Cart();
        $products = array();
        $temp = $cart->getProducts();
        foreach($temp as $key => $value) {
            $products[$key] = $value['quantity'];
        }
        
        $order = new Order();
        $order = $order->placeOrder($shipperID, $customer, $products);
        
        if($order['error'] === 'SHIPPER')        $output['error'] = 'Frakt alternativet finns inte.';
        else if($order['error'] === 'QUANTITY')  $output['error'] = 'några av produkterna är slut!';
        else if($order['error'] === 'PRODID')    $output['error'] = 'några av produkterna är ogiltig!';
        else if($order['error'] === 'EMPTY')     $output['error'] = 'Kundvagnen är tom!';
        else if($order['error'] === 'DUPLICATE') $output['error'] = 'Duplicate e-postadress!';
        else if(isset($order['orderID'])) {
            $output = array('orderID' => $order['orderID']);
            $output['customerID'] = $order['customerID'];
            if(isset($order['password'])) {
                $temp = new Customer();
                $temp->signIn($customer['email'], $order['password']);
                $output['password'] = $order['password'];
            }
            $cart->clear();
        }
        
        if($subscriber) {
            require('../classes/subscriber.php');
            $subscriber = new Subscriber();
            $subscriber->add($customer['contactName'], $customer['email']);
        }
        
    } else if(($_POST['action'] == 'ADD') && isset($_POST['prodID'])) {        

        $cart = new Cart();
        if(!$cart->add($_POST['prodID'])) {
            $output['error'] = 'Produkten är slut.';
            die(json_encode($output));
        }
        
        $output = array('quantity' => $cart->getQuantity());
        
    } else if(($_POST['action'] == 'SHIPPER') && isset($_POST['shipperID'])) {
        $cart  = new Cart();
        $total = $cart->getQuantity();
        if(!$total) {
            $output['error'] = 'EMPTY';
            die(json_encode($output));
        }
        
        if(!$cart->setShipper($_POST['shipperID'])) {
            $output['error'] = 'Frakt alternativet finns inte.';
            die(json_encode($output));
        }
        
        $price = 0;
        $products = $cart->getProducts();
        foreach($products as $key => $value) {
            $price += $value['price'] * $value['quantity'];
        }
        $price += $cart->shippingCost();
            
        $output = array('total' => $price);
        
    } else if(($_POST['action'] == 'UPDATE') && isset($_POST['prodID']) && isset($_POST['quantity'])) {
        
        $cart = new Cart();
        if(!$cart->getQuantity()) {
            $output['error'] = 'Kundvagnen är tom!';
            die(json_encode($output));
        }
            
        if(!$cart->update($_POST['prodID'], $_POST['quantity'])) {
            $output['error'] = 'Produkten är slut.';
            die(json_encode($output));
        }
        
        $total = $cart->getQuantity();
        if(!$total) {
            $output['error'] = 'Kundvagnen är tom!';
            die(json_encode($output));
        }
            
        $price = 0;
        $products = $cart->getProducts();
        foreach($products as $key => $value) {
            $price += $value['price'] * $value['quantity'];
        }
        $price += $cart->shippingCost();
                
        $output = array('total' => $total, 'price' => $price);
            
        $output['error'] = false;
    }
    
    echo json_encode($output);
?>