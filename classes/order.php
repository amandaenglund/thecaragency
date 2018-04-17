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
        
        public function statusTXT($status) {
            if($status === 1) return 'Mottagen';
            else if($status === 0) return 'Skickad';
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
            $order['shipping'] = array('name' => $result['name'], 'time' => $result['deliveryTime'], 'cost' => $order['shippingCost']);
            unset($order['shippingCost']);
            
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
        
        public function placeOrder($shipperID, $customer, $products) {
            $result = array('error' => false);

            $shipper = new Shipper($shipperID);
            if(!$shipper->isValid()) {
                $result['error'] = "SHIPPER";
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
            $shippingCost = $shipper->getCost($total);
            
            try {
                $DB = Database::getDB();
                $DB->startTransacion();
                
                $temp = new Customer();
                if($customerID = $temp->isSignedIn()) {
                    $temp = $temp->update(
                        $customer['companyName'], $customer['contactName'], $customer['phoneNumber'], 
                        $customer['address'], $customer['postalCode'], $customer['city']
                    );
                    if($temp === false) throw new Exception('Customer update failed!');
                    
                } else {

                    $temp = $temp->create(
                        $customer['email'], $customer['companyName'],  $customer['contactName'],
                        $customer['phoneNumber'], $customer['address'], $customer['postalCode'], $customer['city']
                    );
                    
                    if($temp === false) {
                        if($DB->getError() == 1062) $result['error'] = 'DUPLICATE';
                        throw new Exception('Customer creating failed!');
                        
                    } else {
                        $password = $temp['password'];
                        $customerID = $temp['customerID'];
                    }
                }

                unset($temp);
                $DB->clearParams();
                $DB->addParam('i', $customerID);
                $DB->addParam('i', $shipperID);
                $DB->addParam('s', $customer['companyName'] ? $customer['companyName'] : $customer['contactName']);
                $DB->addParam('s', $customer['address']);
                $DB->addParam('s', $customer['postalCode']);
                $DB->addParam('s', $customer['city']);
                $DB->addParam('i', $shippingCost);
                $query  = "INSERT INTO Orders (customerID, shipperID, customerName, deliveryAddress, deliveryPostalCode, ";
                $query .= "deliveryCity, shippingCost) VALUES (?, ?, ?, ?, ?, ?, ?)";
                if(!$DB->query($query)) throw new Exception('Orders insert failed!');
                $orderID = $DB->insertID();
                
                foreach($products as $key => $value) {
                    $DB->clearParams();
                    $DB->addParam('i', $key);
                    $DB->addParam('i', $orderID);
                    $DB->addParam('i', $value['unitPrice']);
                    $DB->addParam('i', $value['quantity']);
                    $query = "INSERT INTO OrderedProducts (productID, orderID, unitPrice, quantity) VALUES (?, ?, ?, ?)";
                    if(!$DB->query($query)) throw new Exception('OrderedProducts insert failed!');
                    
                    $DB->clearParams();
                    $DB->addParam('i', $value['quantity']);
                    $DB->addParam('i', $key);
                    $query = "UPDATE Products SET unitsInStock = unitsInStock - ? WHERE (productID = ?)";
                    if(!$DB->query($query)) throw new Exception('Update the quantity of the products failed!');
                }
                
                $DB->commit();
                
                $result['orderID'] = $orderID;
                $result['customerID'] = $customerID;
                if(isset($password)) $result['password'] = $password;
                
            } catch (Exception $e) {
                $DB->rollBack();
                if(!$result['error']) $result['error'] = true;
            }
            
            return $result;
        }
    }
?>