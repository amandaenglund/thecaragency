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
        <h3 class="center">Redigera produkterna</h3>
        <div class="row">
            <div class="title"><span onclick="updateProduct()"><i class="fa fa-save"></i></span></div>
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
                <div class="body">
                    <div class="input props"><label>Namn</label><input id="name" type="text" placeholder="Namn" /></div>
                    <div class="input props"><label>Årsmodel</label><input id="year" type="text" placeholder="Årsmodel" /></div>
                    <div class="input props"><label>Pris</label><input id="price" type="text" placeholder="Pris" /></div>
                    <div class="input props"><label>Batteri</label><input id="battery" type="text" placeholder="Batteri" /></div>
                    <div class="input props"><label>Topphastighet</label><input id="maxspeed" type="text" placeholder="Topphastighet" /></div>
                    <div class="input props"><label>Acceleration</label><input id="acceleration" type="text" placeholder="Acceleration" /></div>
                    <div class="input props"><label>Antal</label><input id="quantity" type="number" value="1" min="1" placeholder="Antal" /></div>
                </div>
            </div>
            <div id="description" class="column">
                <div class="title"><label>Beskrivning</label></div>
                <div class="body"><textarea></textarea></div>
            </div>
        </div>
    </div>
</div>
<script>

</script>
<?php require('footer.php'); ?>