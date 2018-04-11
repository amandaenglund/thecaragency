<?php 
    require('header.php');
    
    $files = glob('../images/'.$admin->getEmail().'/*');
    foreach($files as $file){ if(is_file($file)) unlink($file);}
    @rmdir('../images/'.$admin->getEmail());
    unset($files); unset($file);
    
    require('../classes/categories.php');
    $categories = new Categories();
    $categories = $categories->getAll();
?>
<div class="content">
    <div class="main">
        <h3 class="center">Lägga till nya produkter</h3>
        <div class="row">
            <div class="title"><span><i class="fa fa-save"></i></span></div>
            <div class="column">
                <div class="title">
                    <label>Ladda upp bilden (800x533)</label>
                    <span><i class="fa fa-upload"></i><input type="file" name="uploadFile"
                    onchange="{if(this.files[0]) uploadImage(this.files[0]);}" size="1" accept="image/jpeg" /></span>
                </div>
                <div id="image" class="body"></div>
            </div>
            <div class="column">
                <div class="title"><label>Kategorier</label></div>
                <div id="categories" class="body"><?php
                    foreach($categories as $category) {
                        echo '<input type="checkbox" value="'.$category['categoryID'].'" /> '.$category['name'].'<br />';
                    }
                ?></div>
            </div>
            <div id="properties" class="column">
                <div class="title"><label>Egenskaper</label></div>
                <div class="body"></div>
            </div>
            <div id="description" class="column">
                <div class="title"><label>Beskrivning</label></div>
                <div class="body"></div>
            </div>
        </div>
    </div>
</div>
<?php require('footer.php'); ?>