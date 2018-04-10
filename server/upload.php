<?php
    
    $file = file_get_contents('php://input');
    file_put_contents("../images/test.jpg", $file);
    die($file);

?>