<?php 
    require('header.php');
    require('../classes/products.php');
    require('../classes/customer.php');
    require('../classes/shipper.php'); 
    require('../classes/order.php');
    
    $total = new Order();
    $total = $total->getTotal();
?>
<div class="content">
    <div class="main">
        <h3 class="center">Orderlista</h3>
        <div class="row">
            <div class="title">
                <div class="browser">
                    <label><i title="Första" class="fa fa-angle-double-left hide" onclick="getOrders(current = 1)"></i></label>
                    <label><i title="Förra" class="fa fa-angle-left hide" onclick="getOrders(current--)"></i></label>
                    <label>1 / <?=$total?></label>
                    <label><i title="Nästa" class="fa fa-angle-right" onclick="getOrders(current++)"></i></label>
                    <label><i title="Sista" class="fa fa-angle-double-right" onclick="getOrders(current = total)"></i></label>
                </div>
            </div>
            <div id="prodDiv" class="column">
                <div class="title"><label>Produkter</label></div>
                <div class="body"></div>
            </div>
            <div id="orderDiv" class="column">
                <div class="title"><label>Produkter</label></div>
                <div class="body"></div>
            </div>
        </div>
    </div>
</div>
<script>
    var current = 1;
    var total = <?=$total;?>;
    getOrders();
</script>
<?php require('footer.php'); ?>