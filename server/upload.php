<?php
    
    $output = array('error' => 'Ett fel uppstod!');
    
    if($_SERVER['REQUEST_METHOD'] == 'PUT') {
        
        require('../classes/database.php');
        require('../classes/admin.php');
        
        $admin = new Admin();
        if(!$admin->isSignedIn()) die(json_encode($output));
        
        $tempDir = "../images/".$admin->getEmail();
        $image = file_get_contents('php://input');
        if(!is_dir($tempDir)) mkdir($tempDir);
        $filename = md5($_SERVER['REMOTE_ADDR'].rand().$admin->getName()).".jpg";
        $filename = "$tempDir/$filename";
        
        $image = file_put_contents($filename, $image);
        
        if($image === FALSE) {
            $output['error'] = 'Uppladdningen misslyckades!';
            die(json_encode($output));
        }
        
        $image = getimagesize($filename);
        
        if($image['mime'] != 'image/jpeg') {
            @unlink($filename);
            $output['error'] = 'Bildtypen måste vara jpg!';
            die(json_encode($output));
        }

        if($image[0] != 800) {
            @unlink($filename);
            $output['error'] = 'Bildens bredd ska vara 800px!';
            die(json_encode($output));
        }

        if($image[1] != 533) {
            @unlink($filename);
            $output['error'] = 'Bildens höjd ska vara 533px!';
            die(json_encode($output));
        }
        
        $output = array('image' => $filename);
    }

    echo json_encode($output);

?>