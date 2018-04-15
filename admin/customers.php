<?php 
    require('header.php');
    
    require('../classes/categories.php');
    require('../classes/customer.php');   
    
    $total = new Customer();
    $total = $total->getTotal();
?>
<div class="content">
    <div class="main">
        <h3 class="center">Kundlista</h3>
        <div class="row">
            <div class="title">
                <div class="browser">
                    <label><i title="Första" class="fa fa-angle-double-left hide" onclick="getCustomers(current = 1)"></i></label>
                    <label><i title="Förra" class="fa fa-angle-left hide" onclick="getCustomers(current--)"></i></label>
                    <label>1 / <?=$total?></label>
                    <label><i title="Nästa" class="fa fa-angle-right" onclick="getCustomers(current++)"></i></label>
                    <label><i title="Sista" class="fa fa-angle-double-right" onclick="getCustomers(current = total)"></i></label>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var current = 1;
    var total = <?=$total;?>;
    getCustomers();
</script>
<?php require('footer.php'); ?>