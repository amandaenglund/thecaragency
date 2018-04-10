<?php
    
    $output = array('error' => 'Ett fel uppstod!');
    if(!isset($_POST['action'])) die(json_encode($output));
    
    require('../classes/database.php');
    require('../classes/admin.php');
    $admin = new Admin();
    
    if(($_POST['action'] == 'signin') && isset($_POST['email']) && isset($_POST['password'])) { 
        
        $_POST['email'] = trim(strtolower($_POST['email']));
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $output['msg'] = 'Fel format e-postadress!';
            die(json_encode($output));
        }
        
        $_POST['password'] = trim($_POST['password']);
        if(strlen($_POST['password']) < 4) {
            $output['msg'] = 'Lösenordet måste vara minst 4 bokstäver!';
            die(json_encode($output));
        }
        
        if($admin->logIn($_POST['email'], $_POST['password'])) $output = array('error' => false);
        else $output['msg'] = 'Fel användarnamn eller lösenord!';
        
    } else if(($_POST['action'] == 'create') && isset($_POST['email']) && isset($_POST['name']) && isset($_POST['password'])) {
        
        $_POST['email'] = trim(strtolower($_POST['email']));
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $output['error'] = 'Fel format e-postadress!';
            die(json_encode($output));
        }
        
        $_POST['name'] = trim(preg_replace('/\s+/', ' ', $_POST['name']));
        if($_POST['name'] == '') {
            $output['error'] = 'Ange ditt namn!';
            die(json_encode($output));
        }
        
        $_POST['password'] = trim($_POST['password']);
        if(strlen($_POST['password']) < 4) {
            $output['error'] = 'Lösenordet måste vara minst 4 bokstäver!';
            die(json_encode($output));
        }
        
        if($result = $admin->createUser($_POST['email'], $_POST['name'], $_POST['password'])) $output = array('error' => false);
        else if($result === false) {
            $DB = Database::getDB();
            if($DB->getError() == 1062) $output['error'] = 'E-postadress duplicat!';
        }
        
    } else if(($_POST['action'] == 'approve') && isset($_POST['email'])) {
        
        $_POST['email'] = trim(strtolower($_POST['email']));
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $output['error'] = 'Fel format e-postadress!';
            die(json_encode($output));
        }

        if($result = $admin->approvedUser($_POST['email'])) $output = array('error' => false);
        else if($result === 0) $output['error'] = 'Användaren har redan godkänts!';
    }
    
    echo json_encode($output);
?>