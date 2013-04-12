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
	
	header("Content-Type: text/css");
?>
#social {width:100%; float:left;}
#social .share{ padding:10px; float:left;}
#menu {float: left;list-style: none;margin: 0;padding: 0;width: 100%;}
#menu li {float: left;text-transform:uppercase;margin: 0;padding: 0;}
#menu a {background: url("../image/custom/CEN/divider.gif") bottom right no-repeat;color: #0053a6;display: block;float: left;margin: 0;padding: 7px 12px;text-decoration: none;font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold;}
#menu a:hover {background: #2f4ea1 url("images/hover.gif") bottom center no-repeat;color: #fff;padding-bottom: 7px;}

.kbc-cart {background-color:#4f6ec1; margin-right:10px; border:#4561ac 1px solid; color:#FFF;}
.kbc-cart a { color:#FFF;}
.kbc-cart a:visited { color:#FFF;}
.text-info { padding: 7px 0 0px 0; color:#FFF; font-size:14px; font-weight:bold;}
#buttonRed { background-color:#F00; color:#FFF;}

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
	/*background-color:#b8ebff;*/
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
	color: #0053a6;
	text-decoration: none;
	cursor: pointer;
}
a:hover {
	text-decoration: underline;
}
a img {
	border: none;
}
p {
	margin-top: 0px;
}
/* layout */
#container {
	width: 960px;
	margin-left: auto;
	margin-right: auto;
	text-align: left;
	background: #FFFFFF;
	background-image:url(../image/custom/CEN/bkg_content.png);
	background-repeat:repeat-y;
}

#container-home {
	width: 960px;
	margin-left: auto;
	margin-right: auto;
	text-align: left;
	background: #FFFFFF;
	background-repeat:repeat-y;
}

#main_container {
	padding:5px;
	float:left;
	width:960px;
}

#main_container-mid {
	padding:0 5px 5px 5px;
	float:left;
	width:960px;
}

#main_container-feature {
	padding:5px;
	float:left;
	width:936px;
	border: 1px solid #CCC;
	margin: 0 0 10px 5px;
}

#mc_con_left {width:655px; float:left;}
#mc_con_right {width:295px; float:left;}
#mc_con_top {width:295px; height:203px; float:left;}
#mc_con_bottom {width:295px; height:187px; float:left;}
#mc_con_feature {width:301px; height:187px; float:left; padding:5px; margin-left:1px;}
#mc_con_feature h1 { font-family:Helvetica, sans-serif; font-size:18px; font-weight:bold; color:#2f4ea1;}
#mc_con_b_title {width:165px;float:left; padding:5px;}

#header {position: relative;
}
#header .divlinks{color:#fff; margin:0 0 5px 0;}
#header .divlinks a{color:#fff; padding:0 10px;}
#header .divlinks a:first-child{padding-left:0;}
#header .div1 {
	height: 124px;
	background-color:#999;
	background-image:url(../image/custom/CEN/bkg_header.jpg);
	background-repeat:no-repeat;

}
#header .div2 {
	position: relative;
	top: 17px;
	left: 45px;
	width:515px;
	float:left;

}

 .div2-logo {
   height:100px;
   width:100px;
   float:left;
}


#header .div2-logo {
   position: relative;
   top: 0px;
   left: 10px;
   height:100px;
   width:100px;
   float:left;
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
	width: 260px;
	height: 52px;
	position: absolute;
	top: 0;
	right: 0px;
	padding-top: 5px;
	padding-left: 17px;
}

#header .div4 {
	padding-left: 10px;
	padding-right: 10px;	
	height: 29px;
	background-color:#F9A102;
    background-image:url(../image/custom/CEN/bkg_nav.gif);
	background-repeat:repeat-x;

}
#header .div4 img {
	float: left;
	margin-right: 5px;
}

#header .div5 a {
	float: left;
}
#header .div6 a {
	float: right;
}
#breadcrumb {
	float:right;
    height:50px;
    line-height:50px;
	padding:0 12px;
    width:708px;
	background-color:#fbe48c;
    margin-left:-10px;
    overflow:hidden;
}
#breadcrumb a:first-child{font-weight:bold;}
#column_left {
	float: left;
	width: 228px;
	margin-right: 10px;
	background-color:#c9d3eb;
}
#column_right {
	display:none;
	float: right;
	width: 180px;
	margin-left: 10px;
}
#content {
	float: left;
	width: 710px;

	margin-bottom: 10px;
	padding-left:5px;
}
#content .top {
	/* padding: 8px 0px 6px 10px; */
	/* background: url('../image/content_top.png') no-repeat; */
}
#content .top h1, .heading {
	color: #2b4a9d;
	font-size: 2em;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	/*text-transform: uppercase;*/
	margin: 10px 0;
}
.heading {
	border-bottom: 1px solid #DDDDDD;
	padding-bottom: 3px;
	margin-bottom: 10px;
}
#content .middle {
	padding: 10px 10px 1px 10px;
	min-height: 380px;
}

.middle img {
	border: 1px solid #DDDDDD;
}
#content .bottom {
	/* background: url('../image/content_bottom.png') no-repeat; */
	height: 5px;
}

.box-categories {
	background-color:#fbecb2;
	margin-bottom: 10px;
	color:#2b4a9d;
	font-size:20px;
	font-weight:bold;
	padding:0 12px;
    height:50px;
    line-height:50px;
}

.box {
	margin-bottom: 10px;
}
.box .top {
	color: #000000;
	font-size: 14px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	text-transform: uppercase;
   margin-bottom:10px;
}
.box .top img {
	float: left;
	margin-right: 5px;
}
.box .middle {
	padding: 10px;
}
.box .bottom {
	height: 5px;
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
	background: transparent url('http://cen.catsolonline.com/image/button_right_red.png') no-repeat scroll top right;
	color: #FFFFFF;
	display: inline-block;
	font: normal 12px arial, sans-serif;
	height: 25px;
	margin-right: 6px;
	padding-right: 18px; /* sliding doors padding */
	text-decoration: none;
}
a.button-red span {
	background-color:#F00;
	display: inline-block;
	padding: 3px 8px 3px 8px;
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
	width: 100%;
	margin-bottom: 0px;
}

.list td {
	text-align: left;
	vertical-align: top;
	/*padding-bottom: 5px;*/
	/*width: 139px;*/
	/*height: 50px;*/
    padding:0 20px 5% 0;
}
.list .prodthumb img{display:block; width:99%; margin-bottom:5px;}
.list .name a{font-weight:bold; display:block;}
.list .price {color:#2C53A2;}
.list .price del{font-weight:bold;}
.list .price .special{color:#f00;}
.list .description{color:#2C53A2;}
.list form{display:none;}
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
    -moz-border-radius-topleft:5px;
    -moz-border-radius-topright:5px;
    border-top-left-radius:5px;
    border-top-right-radius:5px;
}
.tabs a.selected {
	background: #FFFFFF;
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
	padding-top: 25px;
	border-top: 5px solid #c9d3eb;
	background-color:#FFF;
    color:#0054A7;
    font-size:10px;
}
#footer img {
	padding:10px;
}
#footer .div1 {
	float: left;
	text-align: left;
}
#footer .div2 {
	float: right;
	text-align: right;
}
#footer p{text-align:center; margin:5px auto;}
#footer a{font-size: .9em; padding:0 6px;}
#footer .longlinks{font-size:1.2em;}
#footer .longlinks a{text-transform:uppercase; font-weight:bold;}
#category{
	padding:0;
}
#category ul {
	margin:0 0 0 13px;
    padding:0;
	list-style: none; /*url('../image/bullet_1.png');*/
	font-size:12px;
	line-height:17px;
}
#category li{
	margin:5px 0;
}
#category li a {
	font-size:13px;
	font-weight:bold;
	line-height:17px;
}
#category li li a{color:#FE1600;}
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


#gallery {
	position:relative;
	height:390px
}
	#gallery a {
		float:left;
		position:absolute;
	}
	
	#gallery a img {
		border:none;
	}
	
	#gallery a.show {
		z-index:1
	}

	#gallery .caption {
		z-index:600; 
		background-color:#000; 
		color:#ffffff; 
		height:100px; 
		width:100%; 
		position:absolute;
		bottom:0;
	}

	#gallery .caption .content {
		margin:5px
	}
	
	#gallery .caption .content h3 {
		margin:0;
		padding:0;
		color:#1DCCEF;
	}
	
#select_category{float:right; margin:5px 10px;}


/* Page specific styling */
#common__home #content .middle{padding-left:0; padding-right:0;}
#product__category #content .middle{padding-left:0; padding-right:0;}
#product__product table table td{border-top:1px solid #ddd; padding:5px 10px; margin:0;}
#product__product #content .middle{padding-top:0;}
#product__product #image{width:100%;}
