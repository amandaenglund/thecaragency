<?php
    
    class Cart {
        
        private $cart = array(
            'products' => array(),
            'shipper'  => 0
        );
        
        public function __construct() {            
            if(isset($_SESSION['CART'])) $this->cart = $_SESSION['CART'];
        }
        
        public function add($prodID) {
            $product = new Products($prodID);
            $product = $product->getProduct();
            if(empty($product['productID'])) return false;
            $prodID = $product['productID'];
            
            if(isset($this->cart['products'][$prodID])) {
                $quantity = $this->cart['products'][$prodID] + 1;
                if($quantity > $product['unitsInStock']) return false;
                else $this->cart['products'][$prodID] = $quantity;                
            } else $this->cart['products'][$prodID] = 1;

            $_SESSION['CART'] = $this->cart;
            
            return true;            
        }
        
        public function clear() {
            unset($_SESSION['CART']);
        }
        
        public function update($prodID, $quantity) {
            $product = new Products($prodID);
            $product = $product->getProduct();
            if(empty($product['productID'])) return false;
            $prodID = $product['productID'];
            $quantity = abs(intval($quantity));
            
            if(isset($this->cart['products'][$prodID])) {
                if($quantity > $product['unitsInStock']) return false;
                else if($quantity) $this->cart['products'][$prodID] = $quantity;
                else unset($this->cart['products'][$prodID]);
                
                if(!count($this->cart['products'])) unset($_SESSION['CART']);
                else $_SESSION['CART'] = $this->cart;
                return true;
            }
            
            return false;
        }
        
        public function shippingCost() {
            $cost = 0;
            if(isset($this->cart['shipper'])) {
                $shipper = new Shipper($this->cart['shipper']);
                $cost = $shipper->getCost($this->getQuantity());
            }
            return $cost;
        }
        
        public function getProducts() {
            $products = array();            
            foreach($this->cart['products'] as $key => $value) {
                $product = new Products($key);
                $product = $product->getProduct();
                if(isset($product['productID'])) {
                    $key = $product['productID'];
                    $products[$key]['name']  = $product['name'];
                    $products[$key]['price'] = $product['price'];
                    if($value > $product['unitsInStock']) {
                        $value = $product['unitsInStock'];
                        $this->cart['products'][$key] = $value;
                        $_SESSION['CART'] = $this->cart;
                    }
                    $products[$key]['quantity'] = $value;
                }
            }            
            return $products;
        }
        
        public function getQuantity() {
            $total = 0;
            foreach($this->cart['products'] as $value) {
                $total += $value;
            }
            return $total;
        }  
        
        public function setShipper($shipperID) {
            $shipper = new Shipper($shipperID);
            if($shipperID = $shipper->isValid()) {
                $this->cart['shipper'] = $shipperID;
                $_SESSION['CART'] = $this->cart;
                return true;
            }
            return false;
        }
        
        public function getShipper() {
            return $this->cart['shipper'];
        }
    }

?>