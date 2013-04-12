<?php
// Heading 
$_['heading_title'] = 'Checkout Failure';

// Text
$_['text_basket']   = 'Cart';
$_['text_shipping'] = 'Shipping';
$_['text_payment']  = 'Payment';
$_['text_failure']  = 'Failure';

if ($_SESSION['store_code'] == 'BND') {
   $_['text_message']  = '<p>There was a problem while trying to process your order!</p><p>If the problem persists please try selecting a different payment method or you can contact benderburkot.com by <a href="%s">clicking here</a>.';
} else {
   $_['text_message']  = '<p>There was a problem while trying to process your order!</p><p>If the problem persists please try selecting a different payment method or you can contact the store owner by <a href="%s">clicking here</a>.';
}
?>
