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
	width: 1008px;
	margin-left: auto;
	margin-right: auto;
	text-align: left;
	background: #FFFFFF;
}
#header {
	position: relative;
}
#header .div1 {
	height: 155px;
}
#header .div2 {
   background: url('../image/LCR_Login.png') no-repeat;
	position: relative;
	top: 15px;
	left: 0px;
   height:185px;
}
#header .div2 .account a {
   position:absolute;
   left:880px;
   top:100px;
   height:25px;
   width:110px;
   cursor:pointer; 
   z-index:200;
}
#header .div2 .home2 a {
   position:absolute;
   left:799px;
   top:100px;
   height:25px;
   width:60px;
   cursor:pointer; 
   z-index:200;
}
#header .div2 .home a {
   position:absolute;
   left:30px;
   top:10px;
   height:165px;
   width:220px;
   cursor:pointer; 
}
#header .div2 .shopnow a {
   position:absolute;
   left:250px;
   top:130px;
   height:25px;
   width:110px;
   cursor:pointer; 
}
#header .div2 .quickorder a {
   position:absolute;
   left:360px;
   top:130px;
   height:25px;
   width:135px;
   cursor:pointer; 
}
#header .div2 .specials a {
   position:absolute;
   left:500px;
   top:130px;
   height:25px;
   width:120px;
   cursor:pointer; 
}
#header .div2 .wishlist a {
   position:absolute;
   left:620px;
   top:130px;
   height:25px;
   width:120px;
   cursor:pointer; 
}
#header .div2 .ordering a {
   position:absolute;
   left:755px;
   top:130px;
   height:25px;
   width:155px;
   cursor:pointer; 
   z-index:200;
}
#header .div2 .aboutus a {
   position:absolute;
   left:910px;
   top:130px;
   height:25px;
   width:90px;
   cursor:pointer; 
   z-index:200;
}

#header .div2 .account a span { display: none; }
#header .div2 .home a span { display: none; }
#header .div2 .home2 a span { display: none; }
#header .div2 .shopnow a span { display: none; }
#header .div2 .quickorder a span { display: none; }
#header .div2 .specials a span { display: none; }
#header .div2 .wishlist a span { display: none; }
#header .div2 .ordering a span { display: none; }
#header .div2 .aboutus a span { display: none; }


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
	position: absolute;
	top: 5px;
	right: -13px;
	padding-top: 5px;
	padding-left: 17px;
}

#header .div4 {
	padding-left: 10px;
	padding-right: 10px;
	height: 40px;
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
	width: 625px;
	margin-bottom: 10px;
}
#content .top {
	padding: 8px 0px 6px 10px;
   background:#8F217D;
	/*background: url('../image/content_top.png') no-repeat;*/
}

#content .top h1, .heading {
	color: #fff;
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
	background: #FFFFFF;
	padding: 10px 10px 1px 10px;
	min-height: 380px;
}

#content .middle .heading { padding-left:5px;padding:5px; background:#8F217D; }

#content .bottom {
	height: 5px;
}

/* Cart at top */
.box-cart {
	margin-bottom: 5px;
   position:absolute;
   right:0px;
	/*background: url('../image/box_top.png') no-repeat;*/
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
	background: url('../image/LCR_cart.png') no-repeat;
	padding: 5px;
   height:124px;
   width:246px;
}
.box-cart .bottom {
	height: 5px;
	/*background: url('../image/box_bottom.png') no-repeat;*/
}
/* End cart at top */

.box {
	margin-bottom: 10px;
	/*background: url('../image/box_top.png') no-repeat;*/
}
.box .top {
   padding-top:3px;
	color: #000000;
	font-size: 14px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	text-transform: uppercase;
   background:#8F217D;
   margin-bottom:5px;
}
.box .top img {
	margin-right: 0px;
}
.box .middle {
	background: #a8d63a;
	padding: 10px;
}
.box .bottom {
	height: 5px;
	/*background: url('../image/box_bottom.png') no-repeat;*/
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
	width: 95%;
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
	/*padding-bottom: 10px;*/
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
	list-style: none; /*url('../image/bullet_1.png');*/
}
#information ul {
	margin-top: 0px;
	margin-bottom: 0px;
	margin-left: 8px;
	padding-left: 12px;
	list-style: none; /*url('../image/bullet_2.png');*/
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
