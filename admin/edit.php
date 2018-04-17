<?php 
    require('header.php');
    
    $admin->removeUDir();
        
    require('../classes/categories.php');
    $categories = new Categories();
    $categories = $categories->getAll();
    
    require('../classes/products.php');
    $total = new Products();
    $total = $total->getTotal();
?>
<div class="content">
    <div class="main">
        <h3 class="center">Redigera produkt</h3>
        <div class="row">
            <div class="title">
                <div class="browser">
                    <label><i title="Första" class="fa fa-angle-double-left hide" onclick="getProduct(current = 1)"></i></label>
                    <label><i title="Förra" class="fa fa-angle-left hide" onclick="getProduct(current--)"></i></label>
                    <label>1 / <?=$total?></label>
                    <label><i title="Nästa" class="fa fa-angle-right" onclick="getProduct(current++)"></i></label>
                    <label><i title="Sista" class="fa fa-angle-double-right" onclick="getProduct(current = total)"></i></label>
                </div>
                <span onclick="updateProduct()"><i class="fa fa-save"></i></span>
                <span onclick="deleteProduct()"><i class="fa fa-trash"></i></span>
            </div>
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
                <div class="title"><label>Egenskaper <span>(ID: )</span></label></div>
                <div class="body">
                    <div class="input props"><label>Namn</label><input id="name" type="text" /></div>
                    <div class="input props"><label>Årsmodel</label><input id="year" type="text" /></div>
                    <div class="input props"><label>Pris</label><input id="price" type="text" /></div>
                    <div class="input props"><label>Batteri</label><input id="battery" type="text" /></div>
                    <div class="input props"><label>Topphastighet</label><input id="maxspeed" type="text" /></div>
                    <div class="input props"><label>Acceleration</label><input id="acceleration" type="text" /></div>
                    <div class="input props"><label>Antal</label><input id="quantity" type="number" min="0" /></div>
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
    var current = 1, prodID = 0;
    var total = <?=$total;?>;
    getProduct();
</script>
<?php require('footer.php'); ?>