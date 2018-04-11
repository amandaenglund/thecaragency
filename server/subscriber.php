<?php
    
    $response = 'ERROR';

    if(isset($_POST['name']) && isset($_POST['email'])) {
        
        require('../classes/database.php');
        require('../classes/subscriber.php');
        
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $output['error'] = 'Fel format e-postadress!';
            die(json_encode($output));
        }
        
        $_POST['name'] = trim(preg_replace('/\s+/', ' ', $_POST['name']));
        if($_POST['name'] == '') {
            $output['error'] = 'Ange ditt namn!';
            die(json_encode($output));
        }
        
        $subscriber = new Subscriber();
        $result = $subscriber->add($_POST['name'], $_POST['email']);
        if($result) $response = 'SUCCESS';
        else if($result === false) {
            $DB = Database::getDB();
            if($DB->getError() == 1062) $response = 'DUPLICATE';
        }
    }

    echo $response;
    
?>