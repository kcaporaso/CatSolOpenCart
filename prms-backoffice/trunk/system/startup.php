<?php 
// Error Reporting
error_reporting(E_ALL^ E_NOTICE);

// Check Version
if (version_compare(phpversion(), '5.1.0', '<') == TRUE) {
	exit('PHP5.1 Only');
}

define ("ISO_DATETIME_FORMAT",	"Y-m-d H:i:s");
define ("ISO_DATE_FORMAT",	"Y-m-d");

define('PAGENUMRECS', 100);

define("SILVER",   1);
define("GOLD",     2);
define("PLATINUM", 3);

define("SPS_ADMIN",     10000);
define("SPS_SUPERUSER", 10001);
define("SPS_APPROVER",  10006);
define("SPS_SHOPPER",   10002);

define("SPS_ORDER_PENDING_APPROVAL", 1);
define("SPS_ORDER_REJECTED", 11);
define("SPS_ORDER_APPROVED", 12);
define("SPS_PAYMENT_UPDATED",13);
define("SPS_ORDER_CANCELED" ,8);

define("SHOPPING_LIST", 0);
define("WISH_LIST", 1);

// Register Globals Fix
if (ini_get('register_globals')) {
	@ini_set('session.use_cookies', '1');
	@ini_set('session.use_trans_sid', '0');
		
	@session_set_cookie_params(0, '/');

   // KMC HACK for secure order viewing under 1 domain.
   /*if (!empty($_GET['ADMIN_SESSION_ID'])) {
      session_id($_GET['ADMIN_SESSION_ID']); 
   }*/
   
	@session_start();
	
	$globals = array($_REQUEST, $_SESSION, $_SERVER, $_FILES);

	foreach ($globals as $global) {
		foreach(array_keys($global) as $key) {
			unset($$key);
		}
	}
	
	ini_set('register_globals', 'Off');
}

// Magic Quotes Fix
if (ini_get('magic_quotes_gpc')) {
	function clean($data) {
   		if (is_array($data)) {
  			foreach ($data as $key => $value) {
    			$data[$key] = clean($value);
  			}
		} else {
  			$data = stripslashes($data);
		}
	
		return $data;
	}			
	
	$_GET = clean($_GET);
	$_POST = clean($_POST);
	$_COOKIE = clean($_COOKIE);
	
	ini_set('magic_quotes_gpc', 'Off');
}

// Engine
require_once(DIR_SYSTEM . 'engine/controller.php');
require_once(DIR_SYSTEM . 'engine/front.php');
require_once(DIR_SYSTEM . 'engine/loader.php'); 
require_once(DIR_SYSTEM . 'engine/model.php');
require_once(DIR_SYSTEM . 'engine/registry.php');
require_once(DIR_SYSTEM . 'engine/router.php'); 
require_once(DIR_SYSTEM . 'engine/url.php');

// Common
require_once(DIR_SYSTEM . 'library/cache.php');
require_once(DIR_SYSTEM . 'library/config.php');
require_once(DIR_SYSTEM . 'library/db.php');
require_once(DIR_SYSTEM . 'library/document.php');
require_once(DIR_SYSTEM . 'library/image.php');
require_once(DIR_SYSTEM . 'library/language.php');
require_once(DIR_SYSTEM . 'library/mail.php');
require_once(DIR_SYSTEM . 'library/pagination.php');
require_once(DIR_SYSTEM . 'library/request.php');
require_once(DIR_SYSTEM . 'library/response.php');
require_once(DIR_SYSTEM . 'library/session.php');
require_once(DIR_SYSTEM . 'library/template.php');
require_once(DIR_SYSTEM . 'library/calendar.php');
?>
