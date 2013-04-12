<?php

    if (strstr($_SERVER['SERVER_NAME'], 'prms-local-')) {
    	 
        // LOCAL DEV
        require_once('C:/_work/_projects/prms-frontoffice/config.php');
        
    } elseif (strstr($_SERVER['SERVER_NAME'], 'xod.ca')) {
        
        // DEV
        require_once('/home/content/g/c/h/gchuah/html/prms-frontoffice/trunk/config.php');
        
    } else {
        
        // PRODUCTION
        require_once('/home/andrea32/www/catsolonline.com/prms-frontoffice/trunk/config.php');
        
    }
	
	
	require_once(DIR_SYSTEM . 'startup.php');
	
	$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	$store_row = $db->get_record('store', "storefront_url  LIKE '%{$_SERVER['SERVER_NAME']}%'");
	
	if (!$store_row) {
		exit;
	} else {
		$store_code = $store_row['code'];
	}
	
	header("Content-Type: text/css");
?>

html {
	overflow: -moz-scrollbars-vertical;
	margin: 0;
	padding: 0;
}
* {
	font-family: Arial, Helvetica, sans-serif;
}
body {
	margin: 0px;
	padding: 0px;
	text-align: center;
}
body, td, th, input, textarea, select, a {
	font-size: 12px;
}
form {
	padding: 0;
	margin: 0;
	display: inline;
}
input, textarea, select {
	margin: 3px 0px;
}
a, a:visited {
	color: #000000;
	text-decoration: underline;
	cursor: pointer;
}
a:hover {
	text-decoration: none;
}
a img {
	border: none;
}
p {
	margin-top: 0px;
}
/* layout */
#container {
	width: 1000px;
	margin-left: auto;
	margin-right: auto;
	text-align: left;
	background: #FFFFFF;
}
#header {
	position: relative;
}
#header .div1 {
	height: 150px;
}
#header .div2 {
	position: relative;
	top: 15px;
	left: 0px;
}
#header .div2-logo {
   position: relative;
   top: 15px;
   left: 0px;
   height:235px;
}
#header .div2-logo .home a {
   position:absolute;
   left:39px;
   top:10px;
   height:98px;
   width:312px;
   cursor:pointer; 
}
#header .div2-logo .shopnow a {
   position:absolute;
   left:9px;
   top:190px;
   height:28px;
   width:112px;
   cursor:pointer; 
}

#header .div2-logo .orderform a {
   position:absolute;
   left:125px;
   top:190px;
   height:28px;
   width:115px;
   cursor:pointer; 
}

#header .div2-logo .wishlist a {
   position:absolute;
   left:250px;
   top:190px;
   height:28px;
   width:115px;
   cursor:pointer; 
}

#header .div2-logo .login a {
   position:absolute;
   left:375px;
   top:190px;
   height:28px;
   width:115px;
   cursor:pointer; 
}

#header .div2-logo .list a {
   position:absolute;
   left:500px;
   top:190px;
   height:28px;
   width:115px;
   cursor:pointer; 
}
#header .div2-logo .contactus a {
   position:absolute;
   left:625px;
   top:190px;
   height:28px;
   width:115px;
   cursor:pointer; 
}

#header .div2-logo .calendar a {
   position:absolute;
   left:755px;
   top:190px;
   height:28px;
   width:115px;
   cursor:pointer; 
}
#header .div2-logo .home a span { display: none; } 
#header .div2-logo .shopnow a span { display: none; } 
#header .div2-logo .orderform a span { display: none; } 
#header .div2-logo .wishlist a span { display: none; } 
#header .div2-logo .login a span { display: none; } 
#header .div2-logo .list a span { display: none; } 
#header .div2-logo .contactus a span { display: none; } 
#header .div2-logo .calendar a span { display: none; } 

#header .div3 {
	background: url('../image/search.png') no-repeat;
	width: 250px;
	height: 62px;
	position: absolute;
	top: 15px;
	right: 0px;
	padding-top: 13px;
	padding-left: 17px;
}

#header .div3-1 {
	width: 180px;
	height: 52px;
	position: absolute;
	top: 5px;
	right: 0px;
	padding-top: 5px;
	padding-left: 17px;
}

#header .div4 {
	padding-left: 10px;
	padding-right: 10px;
	height: 40px;
	background: url('<?php echo HTTPS_IMAGE .'stores/'.$store_code ?>/nav_background.png') repeat-x;
}
#header .div4 img {
	float: left;
	margin-right: 5px;
}
#header .div4 a {
	padding: 12px 0px 12px 0px;
	margin-left: 10px;
	margin-right: 10px;
	display: inline-block;
	color: #FFFFFF;
	text-align: center;
	text-decoration: none;
	font-size: 14px;
	font-family: Verdana, Geneva, sans-serif;
}
#header .div5 a {
	float: left;
}
#header .div6 a {
	float: right;
}
#breadcrumb {
	padding-top: 8px;
	padding-bottom: 10px;
	padding-left: 10px;
}
#column_left {
	float: left;
	width: 180px;
	margin-right: 10px;
}
#column_right {
	float: right;
	width: 180px;
	margin-left: 10px;
}
#content {
	float: left;
	width: 580px;
	margin-bottom: 10px;
}
#content .top {
	padding: 8px 0px 6px 10px;
	background: url('../image/content_top.png') no-repeat;
}
#content .top h1, .heading {
	color: #000000;
	font-size: 14px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	/*text-transform: uppercase;*/
	margin: 0px;
}
.heading {
	border-bottom: 1px solid #DDDDDD;
	padding-bottom: 3px;
	margin-bottom: 10px;
}
#content .middle {
	border-left: 1px solid #DDDDDD;
	border-right: 1px solid #DDDDDD;
	background: #FFFFFF;
	padding: 10px 10px 1px 10px;
	min-height: 380px;
}
#content .bottom {
	background: url('../image/content_bottom.png') no-repeat;
	height: 5px;
}

/* Cart at top */
.box-cart {
	margin-bottom: 5px;
	background: url('../image/box_top.png') no-repeat;
}
.box-cart .top {
	padding: 8px 0px 6px 7px;
	color: #000000;
	font-size: 14px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	text-transform: uppercase;
}
.box-cart .top img {
	float: left;
	margin-right: 5px;
}
.box-cart .middle {
	border-left: 1px solid #DDDDDD;
	border-right: 1px solid #DDDDDD;
	background: #FFFFFF;
	padding: 5px;
}
.box-cart .bottom {
	height: 5px;
	background: url('../image/box_bottom.png') no-repeat;
}
/* End cart at top */

.box {
	margin-bottom: 10px;
	background: url('../image/box_top.png') no-repeat;
}
.box .top {
	padding: 8px 0px 6px 7px;
	color: #000000;
	font-size: 14px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	text-transform: uppercase;
}
.box .top img {
	float: left;
	margin-right: 5px;
}
.box .middle {
	border-left: 1px solid #DDDDDD;
	border-right: 1px solid #DDDDDD;
	background: #FFFFFF;
	padding: 10px;
}
.box .bottom {
	height: 5px;
	background: url('../image/box_bottom.png') no-repeat;
}

.success {
	padding: 5px 0px;
	margin-bottom: 10px;
	background: #E4F1C9;
	border: 1px solid #A5BD71;
	font-size: 11px;
	font-family: Verdana, Geneva, sans-serif;
	text-align: center;
}
.warning {
	padding: 5px 0px;
	margin-bottom: 10px;
	background: #FFDFE0;
	border: 1px solid #FF9999;
	font-size: 11px;
	font-family: Verdana, Geneva, sans-serif;
	text-align: center;
}
.wait {
	padding: 5px 0px;
	margin-bottom: 10px;
	background: #FBFAEA;
	border: 1px solid #EFEBAA;
	font-size: 11px;
	font-family: Verdana, Geneva, sans-serif;
	text-align: center;
}
.required {
	color: #FF0000;
	font-weight: bold;
}
.error {
	color: #FF0000;
	display: block;
}
.help {
	cursor: pointer;
}
.tooltip {
	border: 1px solid #FDDA5C;
	background: #FBFF95;
	padding: 5px;
	font-size: 11px;
	width: 250px;
}
.clear { /* generic container (i.e. div) for floating buttons */
	overflow: hidden;
	width: 100%;
}

a.button-red {
	background: transparent url('../image/button_right_red.png') no-repeat scroll top right;
	color: #FFFFFF;
	display: inline-block;
	font: normal 12px arial, sans-serif;
	height: 25px;
	margin-right: 6px;
	padding-right: 18px; /* sliding doors padding */
	text-decoration: none;
}
a.button-red span {
	background: transparent url('../image/button_left_red.png') no-repeat;
	display: inline-block;
	padding: 5px 0 5px 18px;
}

a.button {
	background: transparent url('../image/button_right.png') no-repeat scroll top right;
	color: #FFFFFF;
	display: inline-block;
	font: normal 12px arial, sans-serif;
	height: 25px;
	margin-right: 6px;
	padding-right: 18px; /* sliding doors padding */
	text-decoration: none;
}
a.button span {
	background: transparent url('../image/button_left.png') no-repeat;
	display: inline-block;
	padding: 5px 0 5px 18px;
}

.buttons {
	background: #F8F8F8;
	border: 1px solid #DDDDDD;
	margin-bottom: 10px;
	clear: both;
	padding: 5px;
}
.buttons input {
	padding: 0px;
	margin: 0px;
}
.buttons table {
	width: 100%;
	border-collapse: collapse;
}
.buttons table td {
	vertical-align: middle;
}
.list {
	width: 95%;
	margin-bottom: 0px;
}
.list td {
	text-align: center;
	vertical-align: top;
	padding-bottom: 5px;
	width: 139px;
	/*height: 50px;*/
}
.sort {
	margin-bottom: 10px;
	background: #F8F8F8;
	height: 30px;
	width: 100%;
}
.sort .div1 {
   height:30px !important;
	float: right;
	margin-left: 5px;
	padding-top: 6px;
	padding-right: 9px;
}
.sort .div2 {
	text-align: right;
	padding-top: 9px;
}
.sort select {
	font-size: 11px;
	margin: 0;
	padding: 0;
}
.pagination {
	display: inline-block;
	width: 100%;
	background: #F8F8F8;
	margin-bottom: 10px;
}
.pagination .links, .pagination .results {
	padding: 7px;
}
.pagination .links {
	float: left;
}
.pagination .links a {
	border: 1px solid #CCCCCC;
	padding: 4px 7px;
	text-decoration: none;
	color: #000000;
}
.pagination .links b {
	border: 1px solid #CCCCCC;
	padding: 4px 7px;
	text-decoration: none;
	color: #000000;
	background: #FFFFFF;
}
.pagination .results {
	float: right;
}
.tabs {
	display: block;
	width: 100%;
	margin-bottom: 0px;
}
.tabs a {
	float: left;
	display: block;
	padding: 6px 15px 7px 15px;
	margin-right: 2px;
	border-top: 1px solid #DDDDDD;
	border-bottom: 1px solid #DDDDDD;
	border-left: 1px solid #DDDDDD;
	border-right: 1px solid #DDDDDD;
	background: #FFFFFF url('../image/tab.png') repeat-x;
	color: #000000;
	font-weight: bold;
	font-size: 13px;
	text-decoration: none;
	z-index: 1;
	position: relative;
	top: 1px;
}
.tabs a.selected {
	background: #FFFFFF url('../image/tab.png') repeat-x;
	border-bottom: 0px;
	padding-bottom: 8px;
	z-index: 3;
}
.page {
	border: 1px solid #DDDDDD;
	background: #FFFFFF;
	display: inline-block;
	padding: 10px;
	display: block;
	width: 536px;
	clear: both;
	z-index: 2;
	margin-bottom: 10px;
}
#footer {
	width: 100%;
	clear: both;
	padding-top: 5px;
	border-top: 1px solid #DDDDDD;
}
#footer .div1 {
	float: left;
	text-align: left;
}
#footer .div2 {
	float: right;
	text-align: right;
}
#category ul {
	margin-top: 0px;
	margin-bottom: 0px;
	margin-left: 8px;
	padding-left: 12px;
	list-style: url('../image/bullet_1.png');
}
#information ul {
	margin-top: 0px;
	margin-bottom: 0px;
	margin-left: 8px;
	padding-left: 12px;
	list-style: url('../image/bullet_2.png');
}
.cart {
	border-collapse: collapse;
	margin-bottom: 10px;
	width: 100%;
	border: 1px solid #EEEEEE;
}
.cart th {
	background: #EEEEEE;
	padding: 5px;
	font-weight: normal;
}
.cart td {
	padding: 5px;
}


.option {
	background: #DDDDDD;
	border: 1px solid #CCCCCC;
	margin: 2px;
}
.option_value {
	background: #EEEEEE;
}
.option_add {
	background: #FFFFFF;
	padding: 5px;
	border-bottom: 1px solid #CCCCCC;
	text-align: right;
}
.add {
	color: #000;
	display: inline-block;
	padding-right: 20px;
	background: url('/catalog/view/common/add.png') right center no-repeat;
}
.remove {
	color: #000;
	display: inline-block;
	padding-right: 20px;
	background: url('/catalog/view/common/delete.png') right center no-repeat;
}




.tagbox {
	
}

.tagbox ol  {
	position:absolute;
	background:#FFF;
	list-style:none;
	list-style-position: inside;
	margin:0;
	padding:0;
}
 
.tagbox ol li {
	width:500px;
	border-left:1px solid #333;
	border-right:1px solid #333;
}
   
.tagbox ol li em {
	color: Maroon;
	font-weight: bold;
	font-style: normal;
}

.tagbox ol li a {
	text-decoration:none;
	color:#000;
	display:block;
	padding:5px;
	border-bottom:1px solid #333;
}
  
.tagbox ol li a:hover, .hl {
	background:#999;
}

.tagbox-lkup {
	width:100%;
}
  
 #featured {
   width: 628px;
   float: left;
   margin-top: 10px;
   margin-bottom: 10px;
}
#featured_top {
   background-image: url(../image/featured_top.png);
   background-repeat: no-repeat;
   height: 54px;
   width: 628px;
}
#featured_content {
   float: left;
   width: 626px;
   border-right-width: 1px;
   border-left-width: 1px;
   border-right-style: solid;
   border-left-style: solid;
   border-right-color: #000;
   border-left-color: #000;
}
#featured_bottom {
   background-image: url(../image/featured_bottom.png);
   background-repeat: no-repeat;
   float: left;
   height: 11px;
   width: 628px;
}
#featured_left {
   float: left;
   width: 175px;
   margin-top: 5px;
   margin-right: 15px;
   margin-bottom: 0px;
   margin-left: 18px;
}
#featured_right {
   float: right;
   width: 175px;
   margin-top: 5px;
   margin-right: 15px;
   margin-bottom: 0px;
   margin-left: 15px;
}

#social {width:100%; float:left;}
#social .share{ padding:10px; float:left;}

