<?php
    require('../classes/database.php');
    require('../classes/admin.php');
    
    $admin = new Admin();
    $admin->signOut();
    die(header('Location: ./'));
    
?>