<?php
    require('header.php');
    require('../classes/products.php');
    require('../classes/customer.php');
    require('../classes/shipper.php'); 
    require('../classes/order.php');
?>
<div class="content">
    <div class="main">
    <?php $array = $admin->unapprovedUsers(); if(count($array)) { ?>
        <h3 class="center">Ny administratör</h3>
        <div id="admins" class="row">
            <div class="title"><span onclick="approveUsers()"><i class="fa fa-save"></i></span></div>
            <?php
                foreach($array as $value) {
                    echo '<div class="column"><div class="body">'.$value['email'].'</div></div>';
                    echo '<div class="column"><div class="body">'.$value['name'].'</div></div>';
                    echo '<div class="column"><div class="body center"><input type="checkbox" value="'.$value['email'].'" /> Aktivera</div></div>';
                    echo '<div class="seperator"></div>';
                }
            ?>
        </div>
    <?php } $array = new Order(); $array = $array->unsentOrders(); if(count($array)) { ?>
        <h3 class="center">Nya beställningar</h3>
        <?php
            foreach($array as $value) {
                $total = $antal = 0;
                $value = new Order($value);
                $value = $value->getOrder();
                echo '<div class="row" style="margin-bottom: 50px;">';
                echo '<div class="title"><span title="Skicka ordern" onclick="sendOrder('.$value['orderID'].')"><i class="fa fa-send"></i>';
                echo '</span></div><div class="column"><div class="title"><label>Produkter</label></div><div class="body">';
                foreach($value['products'] as $prodID => $product) {
                    echo '<div class="input props"><img src="../images/'.$prodID.'.jpg" />';
                    echo '<div><a href="../car.php?carid='.$prodID.'">'.$product['name'].'</a><br/>';
                    echo 'Pris: '.$product['price'].' SEK<br/>Antal: '.$product['quantity']; 
                    echo '</div></div><div class="seperator"></div>';
                    $total += $product['quantity'] * $product['price'];
                    $antal += $product['quantity'];
                }
                $total += $value['shipping']['cost'];
                echo '<div class="input props"><span class="bold"><i class="fa fa-ship" style="font-size: 6em; width: 140px;"></i></span><div>';
                echo 'Frakt: '.$value['shipping']['name'].'<br/>Levereras inom: '.$value['shipping']['time'].'<br/>Kostnad: ';
                echo $value['shipping']['cost'].' SEK</div></div><div class="seperator"></div><div class="input props" style="text-align: right;">';
                echo '<span class="bold">Antal:</span><div>'.$antal.'</div><br/><span class="bold">Totalpris:</span><div>'.$total;
                echo ' SEK</div></div></div></div><div class="column"><div class="title"><label>Uppgifter</label></div><div class="body">';
                echo '<div class="input props"><span class="bold">Order-id:</span><div>'.$value['orderID'].'</div></div>';
                echo '<div class="input props"><span class="bold">Datum:</span><div>'.$value['orderDate'].'</div></div>';
                echo '<div class="input props"><span class="bold">Kund-id:</span><div>'.$value['customerID'].'</div></div>';
                echo '<div class="input props"><span class="bold">Kund namn:</span><div>'.$value['customerName'].'</div></div>';
                echo '<div class="input props"><span class="bold">Leverans adress:</span><div>'.$value['deliveryAddress'].'<br/>';
                echo $value['deliveryPostalCode'].' '.$value['deliveryCity'].'</div></div></div></div></div>';
            }
        } ?>
    </div>
</div>
<?php require('footer.php'); ?>