<?php
    require('header.php');
    
    $cart = new Cart();
    $total = $cart->getQuantity();
    if(!$total) die(header('Location: ./'));
    
    require('./classes/products.php');
    require('./classes/shipper.php');

?>
<div class="content">
    <div class="row">
        <h2>Kassa</h2>
        <div class="column">
            <h4>Produkter</h4>
            <?php
                $price = 0;
                $products = $cart->getProducts();
                foreach($products as $key => $value) {
                    echo '<div class="product">';
                    echo '<img src="images/'.$key.'.jpg" />';
                    echo '<div><a href="./car.php?carid='.$key.'">'.$value['name'].'</a><br />';
                    echo 'Pris: '.$value['price'].' SEK<br />';
                    echo 'Antal: <input type="number" min="0" value="'.$value['quantity'].'" onchange="updateCart('.$key.', this)" /> ';
                    echo '<button id="delete" onclick="updateCart('.$key.', this)">Ta bort</button> ';
                    echo '</div></div>';
                    $price += $value['quantity'] * $value['price'];
                }
            ?>
            <div class="product">
                <i class="fas fa-shipping-fast" style="font-size: 6em; width: 130px;"></i><div>
                <?php
                    $shippers = new Shipper();
                    $shippers = $shippers->getAll();
                    foreach($shippers as $value) {
                        $shipper = new Shipper($value['shipperID']);
                        $shipcost = $shipper->getCost($total);
                        if($value['shipperID'] == $cart->getShipper()) {
                            echo '<input onclick="setShipper('.$value['shipperID'].')" type="radio" value="';
                            echo $value['shipperID'].'" name="shipping" checked="checked" /> ';
                            $price += $shipcost;
                                                    
                        } else {
                            echo '<input onclick="setShipper('.$value['shipperID'].')" type="radio" value="';
                            echo $value['shipperID'].'" name="shipping" /> ';
                        }
                        echo $value['name'].', '.$value['deliveryTime'].', '.$shipcost.' SEK<br/>';
                    }
                ?></div>
            </div>
            <div class="product" style="text-align: right;">
                <div><span>Antal: </span><label id="antal"><?=$total;?></label></div><br />
                <div><span>Totalpris: </span><label id="price"><?=$price;?> SEK</label></div>
            </div>
        </div>
        <div class="column">
            <h4>Kunduppgiter</h4><?php $customer = $customer->getCustomer(); ?>
            <div class="cstmr"><label>Företag</label><input id="company" type="text" value="<?=@$customer['companyName'];?>" placeholder="Företagsnamn" /></div>
            <div class="cstmr"><label>Kontaktnamn</label><input id="name" type="text" value="<?=@$customer['contactName'];?>" placeholder="Kontaktnamn" /></div>
            <div class="cstmr"><label>Telefon</label><input id="phone" type="text" value="<?=@$customer['phoneNumber'];?>" placeholder="Telefon" /></div>
            <div class="cstmr"><label>E-postadress</label><input id="email" type="text"<?=isset($customer['email']) ? ' disabled' : '';?> value="<?=@$customer['email'];?>" placeholder="E-postadress" /></div>
            <div class="cstmr"><label>Adress</label><input id="address" type="text" value="<?=@$customer['address'];?>" placeholder="Adress" /></div>
            <div class="cstmr"><label>Postnummer</label><input id="zipcode" type="text" value="<?=@$customer['postalCode'];?>" placeholder="Postnummer" /></div>
            <div class="cstmr"><label>Stad</label><input id="city" type="text" value="<?=@$customer['city'];?>" placeholder="Stad" /></div>
            <div class="cstmr"><input id="newsletter" type="checkbox" placeholder="Stad" /><span>Prenumerera på vårt nyhetsbrev</span></div>            
            <div class="cstmr"><button onclick="placeOrder()">Beställa</button></div>
        </div>
    </div>

</div>
<?php require ('footer.php'); ?>
