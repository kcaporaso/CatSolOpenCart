<?php
// Heading
$_['heading_title'] = 'Your Order Has Been Processed!';

// Text

if ($_SESSION['store_code'] == 'BND') {

$_['text_message_1']  = '<p>Thank you for your order, #'.$_SESSION['completed_order_id'].'.</p>';
$_['text_message_2']  = '<p>Email confirmation of this order has been sent to you.</p>
<p>You can view this order as well as previous orders in your order history, by going to the <a href="%s">My Account</a> page and by clicking on <a href="%s">History</a>.</p>
<p>Please direct any questions you have to <a href="%s">benderburkot.com</a>.</p><p>Thanks for shopping with us online!</p>';

} else {

$_['text_message_1']  = '<p>Thank you for your order, #'.$_SESSION['completed_order_id'].'.</p>';
$_['text_message_2']  = '<p>Email confirmation of this order has been sent to you.</p>
<p>You can view this order as well as previous orders in your order history, by going to the <a href="%s">My Account</a> page and by clicking on <a href="%s">History</a>.</p>
<p>Please direct any questions you have to the <a href="%s">store owner</a>.</p><p>Thanks for shopping with us online!</p>';

}

$_['text_basket']   = 'Cart';
$_['text_shipping'] = 'Shipping';
$_['text_payment']  = 'Payment';
$_['text_confirm']  = 'Confirm';
$_['text_success']  = 'Success';
?>
