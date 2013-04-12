<?php
// Heading
$_['heading_title']  = 'Contact Us';

// Text 
$_['text_address']   = 'Address:';
$_['text_email']     = 'E-Mail:';
$_['text_telephone'] = 'Telephone:';
$_['text_fax']       = 'Fax:';

if ($_SESSION['store_code'] == 'BND') {
   $_['text_message']   = '<p>Your inquiry has been successfully sent to Bender-Burkot.com!</p>';
} else {
   $_['text_message']   = '<p>Your inquiry has been successfully sent to the store owner!</p>';
}

// Entry Fields
$_['entry_name']     = 'First Name:';
$_['entry_email']    = 'E-Mail Address:';
$_['entry_enquiry']  = 'Inquiry:';
$_['entry_captcha']  = 'Enter the code in the box below:';
$_['entry_newsletter'] = 'Please sign me up for your email newsletter';

// Email
$_['email_subject']  = 'Inquiry %s';
$_['email_newsletter'] = "Subscribe to Newsletter! \n\n";

// Errors
$_['error_name']     = 'Name must be greater than 3 and less than 32 characters!';
$_['error_email']    = 'E-Mail Address does not appear to be valid!';
$_['error_enquiry']  = 'Inquiry must be greater than 10 and less than 1000 characters!';
$_['error_captcha']  = 'Verification code does not match the image!';
?>
