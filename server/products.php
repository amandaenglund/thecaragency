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
        
        $image = explode('/', @$_POST['image']); $image = end($image);
        if(!is_file('../images/'.$admin->getEmail().'/'.$image)) { $output['error'] = 'Ladda upp bilden!'; die(json_encode($output)); }
        
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
        if($_POST['acceleration'] <= 0) { $output['error'] = 'Ange acceleration!'; die(json_encode($output)); }        


        $_POST['quantity'] = intval(preg_replace("/[^0-9]/", "", @$_POST['quantity']));
        if($_POST['quantity'] <= 0) { $output['error'] = 'Ange antalet!'; die(json_encode($output)); }

        $_POST['description'] = trim(preg_replace('/\s+/', ' ', @$_POST['description']));
        if($_POST['description'] == '') { $output['error'] = 'Ange beskrivningen!'; die(json_encode($output)); }
        
    }
    
    if($_POST['action'] == 'ADD') {
        
        $products = new Products();
        $prodID = $products->create(
            $_POST['name'], $_POST['year'], $_POST['price'], $_POST['battery'], 
            $_POST['maxspeed'], $_POST['acceleration'], $_POST['quantity'], $_POST['description']
        );
        
        if(!$prodID) die(json_encode($output));
        if(!rename ('../images/'.$admin->getEmail().'/'.$image, "../images/$prodID.jpg")) { 
            $output['error'] = $prodID; die(json_encode($output));
        }
        
        foreach($_POST['categories'] as $value) {
            if(!$products->insertCategory($value, $prodID)) {
                $output['error'] = $prodID; die(json_encode($output));
            }
        }
        
        $output = array('success' => $prodID);
        
    }
    
    echo json_encode($output)
?>