<?php

    $output = array('error' => 'Ett fel uppstod!');
    if(!isset($_POST['action'])) die(json_encode($output));
    
    require('../classes/database.php');
    require('../classes/categories.php');
    require('../classes/products.php');
    require('../classes/admin.php');
    
    $admin = new Admin();
    if(!$admin->isSignedIn()) die(json_encode($output));
    
    if(($_POST['action'] == 'ADD') || ($_POST['action'] == 'EDIT')) {
        
        if(isset($_POST['categories']) || is_array($_POST['categories']) || count($_POST['categories'])) {
            $categories = new Categories();
            foreach($_POST['categories'] as $key => $value) {
                $value = intval($value);
                if(!$categories->isValid($value)) unset($_POST['categories'][$key]);
                else $_POST['categories'][$key] = $value;
            }
            if(!count($_POST['categories'])) { $output['error'] = 'Välj kategorier!'; die(json_encode($output)); }
            
        } else { $output['error'] = 'Välj kategorier!'; die(json_encode($output)); }
        
        $_POST['name'] = trim(preg_replace('/\s+/', ' ', @$_POST['name']));
        if($_POST['name'] == '') { $output['error'] = 'Ange namnet!'; die(json_encode($output)); }
        
        $_POST['year'] = intval(preg_replace("/[^0-9]/", "", @$_POST['year']));
        if(strlen(strval($_POST['year'])) != 4) { $output['error'] = 'Ange årsmodel!'; die(json_encode($output)); }
        
        $_POST['price'] = intval(preg_replace("/[^0-9]/", "", @$_POST['price']));
        if($_POST['price'] <= 0) { $output['error'] = 'Ange priset!'; die(json_encode($output)); }
        
        $_POST['battery'] = trim(preg_replace('/\s+/', ' ', @$_POST['battery']));
        if($_POST['battery'] == '') { $output['error'] = 'Ange batteri!'; die(json_encode($output)); }
        
        $_POST['maxspeed'] = intval(preg_replace("/[^0-9]/", "", @$_POST['maxspeed']));
        if($_POST['maxspeed'] <= 0) { $output['error'] = 'Ange topphastigheten!'; die(json_encode($output)); }
        
        $_POST['acceleration'] = str_replace(",", ".", @$_POST['acceleration']);
        $_POST['acceleration'] = floatval(preg_replace("/[^0-9\.]/", "", $_POST['acceleration']));
        if(($_POST['acceleration'] <= 0) || ($_POST['acceleration'] >= 10)) {
            $output['error'] = 'Accelerationen måste vara mellan 0.1 till 99.9!'; die(json_encode($output));
        }        

        $_POST['quantity'] = intval(preg_replace("/[^0-9]/", "", @$_POST['quantity']));
        if($_POST['quantity'] < 0) { $output['error'] = 'Ange antalet!'; die(json_encode($output)); }

        $_POST['description'] = trim(preg_replace('/\s+/', ' ', str_replace("\n", "<br/>", @$_POST['description'])));
        if($_POST['description'] == '') { $output['error'] = 'Ange beskrivningen!'; die(json_encode($output)); }
        
    }
    
    if($_POST['action'] == 'ADD') { 
    
        $image = explode('/', @$_POST['image']); $image = end($image); 
        if(!is_file('../images/'.$admin->getEmail().'/'.$image)) { $output['error'] = 'Ladda upp bilden!'; die(json_encode($output)); }
        
        $products = new Products();
        $prodID = $products->create(
            $_POST['name'], $_POST['year'], $_POST['price'], $_POST['battery'], 
            $_POST['maxspeed'], $_POST['acceleration'], $_POST['quantity'], $_POST['description']
        );
        
        if(!$prodID) die(json_encode($output)); else $products = new Products($prodID); 
        if(!rename ('../images/'.$admin->getEmail().'/'.$image, "../images/$prodID.jpg")) { 
            $output['error'] = $prodID; die(json_encode($output));
        }
        
        if(!$products->insertCategories($_POST['categories'])) {
            $output['error'] = $prodID; die(json_encode($output));
        }
        
        $output = array('success' => $prodID);
        
    } else if(($_POST['action'] == 'GET') && isset($_POST['current'])) {
        $current = intval($_POST['current']); unset($_POST['current']);
        if($current <= 0) $current = 1;
        
        $products = new Products();
        $total = $products->getTotal();
        if($current > $total) $current = $total;
        
        if($prodID = $products->producID($current)) {
            $products = new Products($prodID);
            $output = array('product' => $products->getProduct());
            $output['product']['categories'] = $products->getCategories();
            $output['current'] = $current;
            $output['total']   = $total;
        }
        
    } else if(($_POST['action'] == 'EDIT') && isset($_POST['prodID'])) {
        $prodID = intval($_POST['prodID']); unset($_POST['prodID']);
        $product = new Products($prodID);
        if(!$product->isValid()) { $output['error'] = 'Ogiltigt produkt-id!'; die(json_encode($output)); }
        
        $image = explode('/', @$_POST['image']); $image = end($image);
        if($image == "$prodID.jpg") unset($image);
        else if(!is_file('../images/'.$admin->getEmail().'/'.$image)) { $output['error'] = 'Ladda upp bilden!'; die(json_encode($output)); }
        if(isset($image) && !rename ('../images/'.$admin->getEmail().'/'.$image, "../images/$prodID.jpg")) die(json_encode($output));
        
        $result = $product->update(
            $_POST['name'], $_POST['year'], $_POST['price'], $_POST['battery'], 
            $_POST['maxspeed'], $_POST['acceleration'], $_POST['quantity'], $_POST['description']
        );
        if($result === false) die(json_encode($output));
        
        $result = $product->updateCategories($_POST['categories']);
        if($result === false) die(json_encode($output));
        
        $output = array('success' => $prodID);
    }
    
    echo json_encode($output)
?>