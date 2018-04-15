<?php
    require('header.php');
    require('../classes/newsletter.php');
    require('../classes/subscriber.php');
    
    $temp = new Newsletter();
    $total = $temp->getTotal();
    
    $temp = new Subscriber();
    $temp = $temp->getAll();
?>
<div class="content">
    <div class="main">
        <h3 class="center">Nyhetsbrev</h3>
        <div class="row">
            <div class="title"><span onclick="sendNewsletter()"><i class="fa fa-send"></i></span></div>
            <div class="column">
                <div class="title"><label>Nyhetsbrev</label></div>
                <div class="body">
                     <div class="input"><label>Ämnet</label><input id="subject" type="text" placeholder="Ämnet" /></div>
                     <div class="input"><label>Texten</label><textarea id="texten"></textarea></div>
                </div>
            </div>
            <div class="column">
                <div class="title"><label>Prenumeranter</label></div>
                <div class="body">
                     <div class="emails bold"><label>Namn</label><label>E-postadress</label></div><?php
                     foreach($temp as $value) {
                        echo '<div class="emails"><label>'.$value['name'].'</label><label>'.$value['email'].'</label></div>';
                     }                 
                ?></div>
            </div>
        </div>
        <?php if($total) { ?>
        <h3 class="center">Gamla Nyhetsbreven</h3>
        <div class="row">
            <div class="title">
                <div class="browser">
                    <label><i title="Första" class="fa fa-angle-double-left hide" onclick="getNewsletter(current = 1)"></i></label>
                    <label><i title="Förra" class="fa fa-angle-left hide" onclick="getNewsletter(current--)"></i></label>
                    <label>1 / <?=$total?></label>
                    <label><i title="Nästa" class="fa fa-angle-right" onclick="getNewsletter(current++)"></i></label>
                    <label><i title="Sista" class="fa fa-angle-double-right" onclick="getNewsletter(current = total)"></i></label>
                </div>
            </div>
            <div class="column">
                <div class="body">
                     <div class="input props"><span class="bold">Datum:</span><div id="dateDiv"></div></div>
                     <div class="input props"><span class="bold">Ämnet:</span><div id="subjectDiv"></div></div>
                     <div class="input props"><span class="bold">Texten:</span><div id="bodyDiv"></div></div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<script>
    var current = 1;
    var total = <?=$total;?>;
    getNewsletter();
</script>
<?php require('footer.php'); ?>