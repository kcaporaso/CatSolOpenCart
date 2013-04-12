<?php
// Heading
$_['heading_title']         = 'Delivery Information';

// Text 
$_['text_basket']           = 'Cart';
$_['text_shipping']         = 'Shipping';
if (defined('BENDER')) {
   $_['text_shipping_to']  = 'Please choose where your items will be delivered. If you are a School Purchasing System user your addresses have been preassigned';
} else {
   $_['text_shipping_to']  = 'Please choose from your address book where you would like the items to be delivered to.';
} 
$_['text_shipping_address'] = 'Shipping Address';
$_['text_shipping_method']  = 'Shipping Method';

if (defined('BENDER')) {
   $_['text_shipping_methods'] = 'The shipping charges shown are an ESTIMATE based on a percentage of your order total. The actual shipping charges for the order will be prepaid and added to the final invoice.';
} else {
   $_['text_shipping_methods'] = 'Please select the preferred shipping methods to use on this order.';
}

$_['text_comments']         = 'Add Comments About Your Order';

// Error
$_['error_shipping']        = 'Error: Shipping method required!';
?>
