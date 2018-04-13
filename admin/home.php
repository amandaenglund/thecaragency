<?php
    require('header.php');
    $array = $admin->unapprovedUsers();
    
    //die(print_r($array));
?>
<div class="content">
    <div class="main">
        <h3 class="center">Nya administratör</h3>
        <div id="admins" class="row">
            <div class="title"><span onclick="updateProduct()"><i class="fa fa-save"></i></span></div>
            <div class="column">
                <div class="body"></div>
            </div>
            <div class="column">
                <div class="body"></div>
            </div>
            <div class="column">
                <div class="body"></div>
            </div>
        </div>
    </div>
</div>
<?php require('footer.php'); ?>