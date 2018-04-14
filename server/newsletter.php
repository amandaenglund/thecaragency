<?php

    $output = array('error' => 'Ett fel uppstod!');
    if(!isset($_POST['action'])) die(json_encode($output));
    
    require('../classes/database.php');
    require('../classes/newsletter.php');
    require('../classes/admin.php');
    
    $admin = new Admin();
    if(!$admin->isSignedIn()) die(json_encode($output));
    
    if(($_POST['action'] == 'SEND') && isset($_POST['subject']) && isset($_POST['body'])) {
        
        $_POST['subject'] = trim(preg_replace('/\s+/', ' ', $_POST['subject']));
        if($_POST['subject'] == '') { $output['error'] = 'Ange ämnet!'; die(json_encode($output)); }
        
        $_POST['body'] = str_replace("\n", "<br/>", trim(preg_replace('/\s+/', ' ', $_POST['body'])));
        if($_POST['body'] == '') { $output['error'] = 'Ange texten!'; die(json_encode($output)); }
        
        $newsletter = new Newsletter();
        if($newsletter->send($_POST['subject'], $_POST['body']) == 1) $output = array('error' => false);
        
    } else if(($_POST['action'] == 'GET') && isset($_POST['current'])) {
        $current = intval($_POST['current']); unset($_POST['current']);
        if($current <= 0) $current = 1;
        
        $newsletter = new Newsletter();
        $total = $newsletter->getTotal();
        if($current > $total) $current = $total;
        
        if($newsletter = $newsletter->getCurrent($current)) {
            $output = array('newsletter' => $newsletter);
            $output['current'] = $current;
            $output['total']   = $total;
        }
    }
    
    echo json_encode($output);
?>