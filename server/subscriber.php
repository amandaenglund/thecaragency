<?php
    
    $response = 'ERROR';

    if(isset($_POST['name']) && isset($_POST['email'])) {
        
        $_POST['email'] = trim(strtolower($_POST['email']));
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) die('EMAIL');
        
        $_POST['name'] = trim(preg_replace('/\s+/', ' ', $_POST['name']));
        if($_POST['name'] == '') die('NAME');

        require('../classes/database.php');
        require('../classes/subscriber.php');
        
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