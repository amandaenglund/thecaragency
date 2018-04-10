<?php require('header.php'); ?>
<div class="content">
    <div class="main">
        <h3 class="center">Lägga till nya produkter</h3>
        <div class="row">
            <div class="title"><span><i class="fa fa-save"></i></span></div>
            <div class="column">
                <div class="title">
                    <label>Ladda upp bilden</label>
                    <span><i class="fa fa-upload"></i><input type="file" name="uploadFile"
                    onchange="uploadImage(this.files[0])" size="1" accept="image/jpeg" /></span>
                    <!--<span>
                        <form action="../server/upload.php" method="post" enctype="multipart/form-data" target="uploadTarget">
                            <i class="fa fa-upload"></i><input class="uploadFile" type="file" name="uploadFile" title=""
                            style="cursor: pointer;" onchange="document.forms.item(0).submit();" size="1" accept="image/jpeg" />
                            <iframe class="frame" name="uploadTarget" src="#"></iframe>
                        </form>
                    </span>-->
                </div>
                <div id="image" class="body"></div>
            </div>
            <div class="column">
                <div class="title"><label>Kategorier</label></div>
                <div class="body"></div>
            </div>
            <div class="column">
                <div class="title"><label>Egenskaper</label></div>
                <div class="body"></div>
            </div>
        </div>
    </div>
</div>
<?php require('footer.php'); ?>