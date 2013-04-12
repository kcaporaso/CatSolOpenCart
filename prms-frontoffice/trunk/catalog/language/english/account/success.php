<?php
// Heading
$_['heading_title'] = 'Your Account Has Been Created!';

// Text
if ($_SESSION['store_code'] == 'BND') {
   $_['text_message']  = '<p>Congratulations! Your new account has been successfully created!</p> <p>You can now take advantage of member priviledges to enhance your online shopping experience with us.</p> <p>If you have ANY questions about the operation of this online shop, please <a href="%s">contact benderburkot.com</a>.</p> <p>A confirmation has been sent to the provided email address. If you have not received it within the hour, please <a href="%s">contact us</a>.</p>';
} else {
   $_['text_message']  = '<p>Congratulations! Your new account has been successfully created!</p> <p>You can now take advantage of member priviledges to enhance your online shopping experience with us.</p> <p>If you have ANY questions about the operation of this online shop, please email the store owner.</p> <p>A confirmation has been sent to the provided email address. If you have not received it within the hour, please contact us.</p>';
}
$_['text_account']  = 'Account';
$_['text_success']  = 'Success';
?>
