<?php
    
    define('SENT',     0);
    define('RECEIVED', 1);
    
    class Order {
        
        private $orderID = 0;
        
        function __construct($orderID = 0) {
            if($orderID) {
                $DB = Database::getDB();
                $DB->addParam('i', $orderID);
                $result = $DB->query("SELECT orderID FROM Orders WHERE (orderID = ?) LIMIT 1");
                if($result && count($result)) $this->orderID = reset($result)['orderID'];
            }
        }
        
        public function getCurrent($current) {
            $current--;
            $DB = Database::getDB();
            $DB->addParam('i', $current);
            $result = $DB->query("SELECT orderID FROM Orders ORDER BY status ASC LIMIT ?, 1");
            return (!$result || !count($result)) ? false : reset($result)['orderID']; 
        }
        
        public function getTotal() {
            $DB = Database::getDB();
            $result = $DB->query("SELECT COUNT(*) AS total FROM Orders");            
            return (!$result || !count($result)) ? 0 : reset($result)['total'];
        }
        
        /**
         * $status == null; Ordered 
         * $status == 0;    Sent 
         * $status == 1;    Received 
        **/
        public function changeStatus($status) {
            $DB = Database::getDB();
            $status = $status ? RECEIVED : SENT;
            $DB->addParam('i', $status);
            $DB->addParam('i', $this->orderID);
            if($status === SENT) return $DB->query("UPDATE Orders SET status = ?, shipDate = CURRENT_TIMESTAMP() WHERE (orderID = ?)");
            else return $DB->query("UPDATE Orders SET status = ? WHERE (orderID = ?)");
        }
        
        public function getStatus() {
            $order = $this->getOrder();
            if($order['status'] === 1) return 'Hämtad';
            else if($order['status'] === 0) return 'Skickad';
            else return 'Beställd';
        }
        
        public function unsentOrders() {
            $orders = array();
            $DB = Database::getDB();
            $result = $DB->query("SELECT orderID FROM Orders WHERE (status IS NULL) ORDER BY orderID DESC");
            if($result && count($result)) {
                foreach($result as $value) { array_push($orders, $value['orderID']); }
            }
            return $orders;
        }

        public function getOrder() {
            $DB = Database::getDB();
            $DB->addParam('i', $this->orderID);
            $result = $DB->query("SELECT * FROM Orders WHERE (orderID = ?) LIMIT 1");
            if(!$result && !count($result)) return false;
            $order = reset($result);
            
            $DB->clearParams();
            $DB->addParam('i', $order['shipperID']);
            $result = $DB->query("SELECT * FROM Shippers WHERE (shipperID = ?) LIMIT 1");
            if(!$result && !count($result)) return false;
            $result = reset($result); unset($order['shipperID']);
            $order['shipping'] = array('name' => $result['name'], 'time' => $result['deliveryTime'], 'cost' => $order['shippingcost']);
            unset($order['shippingcost']);
            
            $DB->clearParams();
            $DB->addParam('i', $this->orderID);
            $result  = "SELECT o.productID, o.unitPrice, o.quantity, p.name FROM OrderedProducts AS o, ";
            $result .= "Products AS p WHERE (o.orderID = ?) AND (o.productID = p.productID)";
            $result  = $DB->query($result);
            if(!$result && !count($result)) return false;
            foreach($result as $value) {
                $order['products'][$value['productID']] = array(
                    'name' => $value['name'], 'price' => $value['unitPrice'], 'quantity' => $value['quantity']
                );
            }
            return $order;
        }
        
        public function isValid() {
            return $this->orderID;
        }
        
        public function placeOrder($shipperID, $customerID, $products) {
            $result = array('error' => false);

            $shipper = new Shipper($shipperID);
            if(!$shipper->isValid()) {
                $result['error'] = true;
                return $result;
            }
            
            $customer = new Customer($customerID);
            $customer = $customer->getCustomer();
            if(!$customer) {
                $result['error'] = true;
                return $result;
            }

            $temp = array(); $total = 0;
            foreach($products as $product => $quantity) {
                $product = new Products($product);
                if($product->isValid()) {
                    $product = $product->getProduct();
                    if($product['unitsInStock'] < $quantity) { $result['error'] = 'QUANTITY'; break; }
                    $temp[$product['productID']] = array('unitPrice' => $product['price'], 'quantity' => $quantity);
                    $total += $quantity;
                    
                } else {
                    $result['error'] = 'PRODID';
                    break;
                }
            }
            $products = $temp;
            if(!$total) $result['error'] = 'EMPTY';
            if($result['error']) return $result;
            
            $order = array();
            $order['customerID']         = $customerID;
            $order['shipperID']          = $shipperID;
            $order['customerName']       = $customer['companyName'] ? $customer['companyName'] : $customer['contactName'];            
            $order['deliveryAddress']    = $customer['address'];            
            $order['deliveryPostalCode'] = $customer['postalCode'];            
            $order['deliveryCity']       = $customer['city'];            
            $order['shippingcost']       = $shipper->getCost($total);
            
            try {
                $DB = Database::getDB();
                $DB->startTransacion();
                $DB->addParam('i', $order['customerID']);
                $DB->addParam('i', $order['shipperID']);
                $DB->addParam('s', $order['customerName']);
                $DB->addParam('s', $order['deliveryAddress']);
                $DB->addParam('s', $order['deliveryPostalCode']);
                $DB->addParam('s', $order['deliveryCity']);
                $DB->addParam('i', $order['shippingcost']);
                $query  = "INSERT INTO Orders (customerID, shipperID, customerName, deliveryAddress, deliveryPostalCode, ";
                $query .= "deliveryCity, shippingcost) VALUES (?, ?, ?, ?, ?, ?, ?)";
                if(!$DB->query($query)) throw new Exception('Orders insert failed!');
                $orderID = $DB->insertID();
                
                foreach($products as $key => $value) {
                    $DB->clearParams();
                    $DB->addParam('i', $key);
                    $DB->addParam('i', $orderID);
                    $DB->addParam('i', $value['unitPrice']);
                    $DB->addParam('i', $value['quantity']);
                    $temp = $DB->query("INSERT INTO OrderedProducts (productID, orderID, unitPrice, quantity) VALUES (?, ?, ?, ?)");
                    if(!$temp) throw new Exception('OrderedProducts insert failed!');
                    
                    $DB->clearParams();
                    $DB->addParam('i', $value['quantity']);
                    $DB->addParam('i', $key);
                    $temp = $DB->query("UPDATE Products SET unitsInStock = unitsInStock - ? WHERE (productID = ?)");
                    if(!$temp) throw new Exception('Update the quantity of the products failed!');
                }
                
                $DB->commit();
                
                $result = array('orderID' => $orderID);
                
            } catch (Exception $e) {
                $DB->rollBack();
                $result['error'] = true;
            }
            
            return $result;
        }
    }
?>