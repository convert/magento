<?php

class Smashmetrics_Rekko_Block_Rekko extends Mage_Core_Block_Template {

    public function getPluginEnabledStatus() {

        return Mage::getStoreConfig('rekko_section/rekko_group/enabled', Mage::app()->getStore());
    }

    public function getRekkoMerchantID() {

        return Mage::getStoreConfig('rekko_section/rekko_group/merchant_ID', Mage::app()->getStore());
    }

    public function getDomainName() {

        return Mage::getStoreConfig('rekko_section/rekko_group/domain_name', Mage::app()->getStore());
    }
	
	public function email_Call_Newsletter() {

        return Mage::getStoreConfig('rekko_section/rekko_group/email_Call_Newsletter', Mage::app()->getStore());
    }
	
	public function email_Call_User() {

        return Mage::getStoreConfig('rekko_section/rekko_group/email_Call_User', Mage::app()->getStore());
    }


    public function getOrderDetails() {
        $request = $this->getRequest();
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $cart = Mage::getSingleton('checkout/session');

        $quote_id = $cart->getQuoteId();

        if ($module == 'checkout' && $controller == 'onepage' && $action == 'success'):

            $orderId = $cart->getLastOrderId();

            $lastOrderId = Mage::getSingleton('checkout/session')
                    ->getLastRealOrderId();

            $orderId = Mage::getModel('sales/order')
                    ->loadByIncrementId($lastOrderId)
                    ->getEntityId();

            $order = Mage::getModel('sales/order')->load($orderId);
            $_totalData = $order->getData();


            $orderDet['grand_total'] = $_totalData['base_grand_total'];
            $orderDet['coupon_code'] = $_totalData['coupon_code'];
            $orderDet['discount'] = abs($_totalData['base_discount_amount']);
            $orderDet['tax'] = $_totalData['base_tax_amount'];
            $orderDet['orderid'] = $_totalData['increment_id'];
            $orderDet['shipping_total'] = $_totalData['base_shipping_amount'];
            $orderDet['quote_id'] = 'successpage';
        else:
            $quote_id = $cart->getQuoteId();
            $item_quote = Mage::getModel('sales/quote')->load($quote_id);
            $totals = $cart->getQuote()->getTotals();

            if (isset($totals['tax']) && $totals['tax']->getValue()) {
                $tax = round($totals['tax']->getValue()); //Tax value if present
            } else {
                $tax = '';
            }

            $orderDet['grand_total'] = $item_quote->grand_total;
            $orderDet['coupon_code'] = $item_quote->coupon_code;
            $orderDet['discount'] = $item_quote->subtotal - $item_quote->subtotal_with_discount;
            $orderDet['tax'] = $tax;
            $orderDet['orderid'] = '';
            $orderDet['shipping_total'] = '';
            $orderDet['quote_id'] = $quote_id;


        endif;

        return $orderDet;
    }

    public function getCustomerDetails() {

        if ($this->helper('customer')->isLoggedIn()) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $customerData = Mage::getModel('customer/customer')->load($customer->getId())->getData();
        }
        else
        {
//            $customerData = Null;
            $request = $this->getRequest();
            $module = $request->getModuleName();
            $controller = $request->getControllerName();
            $action = $request->getActionName();
            $guestUser=array();
             if ($module == 'checkout' && $controller == 'onepage' && $action == 'success'):
            $cart = Mage::getSingleton('checkout/session');
            $orderId = $cart->getLastOrderId();

            $lastOrderId = Mage::getSingleton('checkout/session')
                    ->getLastRealOrderId();

            $orderId = Mage::getModel('sales/order')
                    ->loadByIncrementId($lastOrderId)
                    ->getEntityId();

           $order = Mage::getModel('sales/order')->load($orderId);
           $_totalData = $order->getData();
           $guestUser['firstname']=$_totalData['customer_firstname'];
           $guestUser['lastname']=$_totalData['customer_lastname'];
           $guestUser['email']=$_totalData['customer_email'];
           $guestUser['group_id']=$_totalData['customer_group_id'];
           $customerData = $guestUser;
           else:
               $customerData = Null;
           endif;
            
        }
        
            return $customerData;
    }

    public function getCartItems() {
        $request = $this->getRequest();
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $cart = Mage::getSingleton('checkout/session');
        $product = array();
        if ($module == 'checkout' && $controller == 'onepage' && $action == 'success'):
            $orderId = $cart->getLastOrderId();
            $lastOrderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
            $order = Mage::getModel('sales/order')->load($orderId);
            $ordered_items = $order->getAllItems();
            $i = 0;
            foreach ($ordered_items as $item) {
                $product[$i]['productId'] = $item->getProductId();
                $product[$i]['sku'] = $item->getSku();
                $product[$i]['name'] = $item->getName();
                $product[$i]['qty'] = $item->getQtyOrdered();
                $product[$i]['price'] = $item->getPrice();
                $_product = Mage::getModel('catalog/product')->load($product[$i]['productId']);
                $category = array();
                $catIds = $_product->getCategoryIds();
                foreach ($catIds AS $cid) {
                    $category[] = Mage::getModel('catalog/category')->load($cid)->getName();
                }
                $product[$i]['category'] = implode(',', $category);

                $i++;
            }
        else:
            $i = 0;
            foreach ($cart->getQuote()->getAllItems() as $item) {
                $product[$i]['productId'] = $item->getProductId();
                $product[$i]['sku'] = $item->getSku();
                $product[$i]['name'] = $item->getName();
                $product[$i]['qty'] = $item->getQty();
                $product[$i]['price'] = $item->getPrice();
                $catIds = $item->getProduct()->getCategoryIds();
                $category = array();
                foreach ($catIds AS $cid) {
                    $category[] = Mage::getModel('catalog/category')->load($cid)->getName();
                }
                $product[$i]['category'] = implode(',', $category);
                $i++;
            }
        endif;
        return $product;
    }

}
