<?php

	header("Content-Type: text/css");
?>

html {
	overflow: -moz-scrollbars-vertical;
	margin: 0;
	padding: 0;
}
* {
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
body {margin:0; padding:0; font-family:Verdana, Helvetica, Arial, sans-serif; font-size:10px;}

td, th, input, textarea, select, p{
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
	margin:0 auto;
	text-align: left;
	background: #FFFFFF;
}
#social {width:100%; float:left;}
#social .share{ padding:10px; float:left;}
#extra_shipping { float:left; padding-left:10px; }


#header{background:#fff url(../image/BND_Logo.png) 10px center no-repeat; min-height:145px; height:145px; padding:1px 0; margin-bottom:1px; position:relative;}
#header .tagline{text-align:center; font-size:1.3em; color:#8d8d8d; margin-top:0;}
#header h1{color:#E32C2D; text-align:center; font-family:"Times New Roman", "Times Roman", Times, serif; font-size:2.2em; margin:1.3em 255px .3em 175px;}
#header h2{color:#8D8D8D; text-align:center; font-size:1.3em; font-weight:bold; margin:.3em 255px .3em 175px;}
#header .phone{color:#133991; text-align:center; font-size:1.8em; font-weight:bold; margin:.3em 255px .3em 175px;}
#header #link_logo_overlay{position:absolute;left:20px;top:25px;height:100px; width:157px;}

#nav{float:right; width:745px; height:60px;}
#nav ul{list-style:none; margin:0 0 5px 0; padding:0; text-align:center; font-size:0;}
#nav li{display:inline; border-right:1px solid #3F65B9; font-size:10px;}
#nav li:last-child{border:none;}
#nav a{text-decoration:none; color:#3F65B9; padding:0 .5em; font-weight:bold;}
#nav img{}
#nav .fancy{background-color:#E9EFFF; border:1px solid #90ACEC;padding:8px 0;}
#nav .fancy a{color:#688AD8;font-size:1.3em; font-weight:normal;}
#nav .fancy li{border:none; border-right:1px solid #688AD8; }
#nav .fancy li:last-child{border:none;}

#module_cart{float:right; width:300px; background:#fff; margin:0 1em 0 0; position:relative;}
#module_cart .userinfo{background:#fff url(../image/BND_iconPadLock.png) center right no-repeat; padding-right:3em; height:38px; font-size:1.1em; text-align:right; line-height:1.5em;}
#module_cart .userinfo a{color:#3F65B9; text-decoration:none;}
#module_cart .cartinfo{width:195px; height:50px; background:#EDEDEF; border:1px solid #D5D6D6; margin:0 0 0 auto; line-height:50px; font-size:.9em; text-align:center; font-weight:bold; color:#6D6E71;}
#module_cart .cartinfo .icon{width:36px; height:100%; background:url(../image/BND_iconCart.png) center center no-repeat; margin:0 0 0 -18px; float:left; }
#module_cart .cartinfo .subtotal{width:50%; float:right; border-left:1px solid #DDDEDE; font-weight:bold; font-size:1.8em; color:#E12121;}
#module_cart .items{width:38%; float:left; }
#module_cart .links{text-align:center;width:200px;margin:0 0 0 auto; color:#6D6E71; font-weight:bold; font-size:.9em;}
#module_cart .links a{text-decoration:none; color:#6D6E71;}
#module_cart p{}

#module_search{border:1px solid #DADBDB; background:#EDEDEF url(../image/BND_bgSatisfactionGuaranteed.png) 95% bottom no-repeat; min-height:55px; padding-top:5px; position:relative;}
#module_search label{color:#D80D0F; font-weight:bold; font-size:1.2em; vertical-align:middle; padding:5px; float:left; margin-top:5px;}
label#module_search_description{float:none; display:block; position:absolute; bottom:-4px; left:15%; vertical-align:middle; color:#5587D4; font-size:.9em;}
label#module_search_description input{vertical-align:middle;}
#module_search a{color:#6D8FCD; font-weight:bold; text-decoration:none; font-size:1.0em;}
#filter_keyword{float:left; width:325px; height:30px; line-height:30px; border:1px solid #133A91; color:#777; font-size:1.2em; vertical-align:middle; margin:0 8px 3px; text-align:center;}
#button_search{float:left; width:67px; height:24px; background:url(../image/BND_buttonSearch.png) center center no-repeat; margin-top:5px;}
#button_search span{visibility:hidden;}
#button_search img{vertical-align:middle;}
#button_search_advanced{display:block; margin:5px 0 5px 455px;clear:left;}
#module_search .top{display:none;}
#column_right #module_search .middle{background:none; min-height:55px; float:none; width:auto; border:none; padding:0;}
#module_search .bottom{display:none;}


#breadcrumb {
   clear:both;
	font-size:1.2em;
	padding:5px 0;
}
#breadcrumb a{color:#3f65b9; text-decoration:none; font-weight:bold;}

#column_left {width:255px; margin:-60px 0 0 -1000px; float:left;}
#column_right {display:none;}

#sps_order_mgmt_content {
   clear:both;
	float: left;
	width: 1030px;
	margin-bottom: 10px;
   margin-left:-10px;
}
#sps_order_mgmt_content .top {
	padding: 8px 0px 6px 10px;
   -moz-border-radius-topleft: 8px; -webkit-border-top-left-radius: 8px; border-top-left-radius: 8px; -moz-border-radius-topright: 8px; -webkit-border-top-right-radius: 8px; border-top-right-radius: 8px;
    border:1px solid #90ACEC;
   background:#D5E4F7 url('../image/bgGradientBlue.png') top center repeat-x;
}
#sps_order_mgmt_content .top h1, .heading {
	color: #004FA2;
	font-size: 14px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	/*text-transform: uppercase;*/
	margin: 0px;
}
#sps_order_mgmt_content .top a{color:#004FA2; background:url('../image/bgGradientBlue.png') center 50% repeat; border:1px solid #90ACEC; text-decoration:none; padding:2px 4px; margin-top:-5px;
    -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;
}

#skip_payment_link {color:#004FA2; background:url('../image/bgGradientBlue.png') center 50% repeat; border:1px solid #90ACEC; text-decoration:none; padding:2px 4px; margin-top:-5px;
    -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;
}

#sps_order_mgmt_content .middle {
	border-left: 1px solid #90ACEC;
	border-right: 1px solid #90ACEC;
	background: #FFFFFF;
	padding: 10px 10px 1px 10px;
	min-height: 400px;
   height:auto;
   overflow:auto;
}
#sps_order_mgmt_content .bottom {
	height: 5px;
   border:1px solid #90ACEC;
   background-color:#90ACEC;
   -moz-border-radius-bottomleft: 8px; -webkit-border-bottom-left-radius: 8px; border-bottom-left-radius: 8px; -moz-border-radius-bottomright: 8px; -webkit-border-bottom-right-radius: 8px; border-bottom-right-radius: 8px;
}
#content {width:745px; margin-left:255px; float:left; margin-bottom:10px;}

#content .top {
   border:solid #90ACEC;
   border-width:1px 1px 0 1px;
   padding: 8px 0px 6px 10px;
   background:#D5E4F7 url('../image/bgGradientBlue.png') top center repeat-x;
   -moz-border-radius-topleft: 8px; -webkit-border-top-left-radius: 8px; border-top-left-radius: 8px; -moz-border-radius-topright: 8px; -webkit-border-top-right-radius: 8px; border-top-right-radius: 8px;
}
#content .top a{color:#004FA2; background:url('../image/bgGradientBlue.png') center 50% repeat; border:1px solid #90ACEC; text-decoration:none; padding:2px 4px; margin-top:-5px;
	-moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;
}
#content .top h1, .heading {
   color: #004FA2;
   font-size: 1.4em;
   font-family: Arial, Helvetica, sans-serif;
   font-weight: bold;
   /*text-transform: uppercase;*/
   margin: 0px;
}
.heading {
   border-bottom: 1px solid #90ACEC;
   padding-bottom: 3px;
   margin-bottom: 10px;
}
.heading h2{
   font-size:1em;
   margin-bottom:0;
}
#content .middle {
   border:solid #90ACEC;
   border-width:0 1px;
   background: #FFFFFF;
   padding: 10px 10px 1px 10px;
   min-height: 380px;
   float:left;
   width:723px;
}
#content .bottom {
   clear:left;
   /*background: url('../image/content_bottom.png') no-repeat;*/
   height: 8px;
   border:solid #90ACEC;
   border-width:0 1px 1px 1px;
   -moz-border-radius-bottomleft: 8px; -webkit-border-bottom-left-radius: 8px; border-bottom-left-radius: 8px; -moz-border-radius-bottomright: 8px; -webkit-border-bottom-right-radius: 8px; border-bottom-right-radius: 8px;
}

.box {
	margin-bottom: 10px;
	border:1px solid #90ACEC;
	-moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;
	background-color:#EAF0FF;
}
.box .top {
	color: #0050A3;
	font-size: 1.4em;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	text-transform: uppercase;
}
.box .top img {
	margin:0 auto;
	display:block;
}
.box .top br{display:none;}
.box .middle {
	padding: 10px;
}
.box .bottom {
	height: 5px;
}
#keyboard_instructions p{
   color: #0050A3;
   font-size:1.1em;
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
	background: #EAF0FF;
	border: 1px solid #90ACEC;
	margin-bottom: 10px;
	clear: both;
	padding: 5px;
   -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;
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
	padding-bottom: 5px;
	width: 139px;
	/*height: 50px;*/
}
.sort {
	margin-bottom: 10px;
	background: #EAF0FF;
	height: 30px;
	width: 100%;
   color:#0050A3;
   -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;
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
.sort a{color:#0050A3;}
.pagination {
	display: inline-block;
	width: 100%;
	background: #EAF0FF;
   margin-bottom: 10px;
   color:#0050A3;
   -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;
}
.pagination .links, .pagination .results {
	padding: 7px;
}
.pagination .links {
	float: left;
}
.pagination .links a {
	border: 1px solid #90ACEC;
	padding: 4px 7px;
	text-decoration: none;
	color: #0050A3;
   -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;
}
.pagination .links b {
	border: 1px solid #90ACEC;
	padding: 4px 7px;
	text-decoration: none;
	color: #0050A3;
   background: #FFFFFF;
   -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;
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
	border-top: 1px solid #90ACEC;
	border-bottom: 1px solid #90ACEC;
	border-left: 1px solid #90ACEC;
	border-right: 1px solid #90ACEC;
	background: #FFFFFF url('../image/tab_blue.png') repeat-x;
	color: #004FA2;
	font-weight: bold;
	font-size: 13px;
	text-decoration: none;
	z-index: 1;
	position: relative;
	top: 1px;
	-moz-border-radius-topleft: 8px; -webkit-border-top-left-radius: 8px; border-top-left-radius: 8px; -moz-border-radius-topright: 8px; -webkit-border-top-right-radius: 8px; border-top-right-radius: 8px;
}
.tabs a.selected {
	background: #FFFFFF url('../image/tab_blue.png') repeat-x;
	border-bottom: 0px;
	padding-bottom: 8px;
	z-index: 3;
}
.page {
	border: 1px solid #90ACEC;
	background: #FFFFFF;
	display: inline-block;
	padding: 10px;
	display: block;
   float:left;
	width: 97%;
	clear: both;
	z-index: 2;
	margin-bottom: 10px;
	-moz-border-radius-bottomleft: 8px; -webkit-border-bottom-left-radius: 8px; border-bottom-left-radius: 8px; -moz-border-radius-bottomright: 8px; -webkit-border-bottom-right-radius: 8px; border-bottom-right-radius: 8px;
}
#footer {
	width: 100%;
	clear: both;
	padding-top: 5px;
   border-top: 1px solid #DDDDDD;
   margin:10px auto;
   color:#666;
}
#footer .div1 {
	float: left;
	text-align: left;
}
#footer .div2 {
	float: right;
	text-align: right;
}
#footer a{color:#666; text-decoration:none;}

#category{width:240px; border:1px solid #90ACEC; background-color:#EAF0FF; margin:0 auto 10px;}
#category .top{display:none;}
#category .middle{padding:0;background:none;}
#category ul{margin:0 5px; padding:0; list-style:none; zoom:1;}
#category li{background:transparent url(../image/BND_bgBorderDotted.png) bottom left repeat-x; padding:5px 0 7px; margin:1px 3px; position:relative; zoom:1;}
#category li:last-child{background:none;}
#category li a{color:#133991; font-size:1.3em; text-decoration:none; padding-right:17px; display:block; margin:1px 0;}
#category .sf-with-ul{background:url(../image/BND_iconNavArrow.png) right center no-repeat;}
#category .sf-sub-indicator{display:none;}
#category .view_all{display:block; text-align:right; padding-right:30px; background:url(../image/BND_iconNavArrowGlass.png) right center no-repeat; font-weight:bold; color:#133B91; text-decoration:none; font-size:1.2em; line-height:30px; margin:5px;}

/* CSS flyouts */
#category ul ul{position:absolute;left:-9999em;top:-2px; background-color:#eaf0ff;border:1px solid #90ACEC; width:230px; padding:0 5px; margin:0; display:none;
	-moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px; -moz-box-shadow:2px 2px 2px #ccc; -webkit-box-shadow:2px 2px 2px #ccc;  box-shadow:2px 2px 2px #ccc;
}
#category li:hover ul ul, #category li.sfHover ul ul{left:-9999em;}
#category li:hover ul, #category li.sfHover ul, #category li li:hover ul, #category li li.sfHover ul {left:224px; z-index:1000;display:block; }

#information{width:240px; margin:0 auto 10px; font-size:1.3em; color:#9BCE17; border:1px solid #697EAC; background-color:#133A91;}
#information a{color:#fff;text-decoration:none;}
#information ul {
	margin-top: 0px;
	margin-bottom: 0px;
	margin-left: 8px;
	padding-left: 12px;
	list-style: disc; /*url('../image/bullet_2.png');*/
}
.cart {
	border-collapse: collapse;
	margin-bottom: 10px;
	width: 100%;
	border: 1px solid #90ACEC;
}
.cart th {
   color:#133991;
	background: #EAF0FF;
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
.cart tbody tr.even {
	background: #FFFFFF;
}
.cart tbody tr.odd {
	background: #E4EEF7;
}

/* Featuredi Products Grid Display and Carousel */
.featured_grid{width:100%; float:left;}
.featured_grid .featured_item{width:23%;padding:1px 1% 20px 1%; margin-bottom:10px;  float:left; min-height:230px; position:relative;}
.featured_grid .featured_item img{display:block; margin:0 auto;}
.featured_grid .featured_item .product_name{color:#133991;text-align:center; margin:3px 0;}
.featured_grid .featured_item .product_name a{text-decoration:none; font-weight:bold; color:#133991;}
.featured_grid .featured_item .product_description{display:none;}
.featured_grid .featured_item .product_price{text-align:center; color:#E32729; font-weight:bold; margin:3px 0;}
.featured_grid .featured_item .product_price del{text-decoration:strike-through;}
.featured_grid .featured_item .more_info{ display:block; width:70%; margin:8px auto; text-align:center; font-weight:bold; color:#004FA2; background:#EAF0FF url('../image/BND_iconNavArrowGlass.png') -4px 55% no-repeat; text-decoration:none; padding:2px; border:1px solid #90ACEC;
	-moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;
}
.featured_grid .featured_item .add_to_cart{ display:block; width:70%; margin:8px auto; text-align:center; font-weight:bold; color:#EE3528; background:#FBF0F0 url('../image/BND_iconNavArrowGlass-red.png') -4px 55% no-repeat; text-decoration:none; padding:2px; border:1px solid #EE3528;
        -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;
}

#featured_product_carousel{width:100%; height:180px; overflow:hidden;}
#featured_product_carousel .featured_item{width:80%;margin:0 auto 20px auto; border:1px solid #90ACEC; background-color:#EAF0FF; height:130px; padding:10px; position:relative;
   -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px; -moz-box-shadow:2px 2px 2px #90ACEC; -webkit-box-shadow:2px 2px 2px #90ACEC; box-shadow:2px 2px 2px #90ACEC;
}
#featured_product_carousel img{float:left;margin-right:15px;border:1px solid #FFF; background-color:#FFF; padding:5px;
   -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px; -moz-box-shadow:0 0 4px #90ACEC; -webkit-box-shadow:0 0 4px #90ACEC; box-shadow:0 0 4px #90ACEC;
}
#featured_product_carousel .product_name{font-weight:bold; font-weight:1.4em; color:#133991;margin:3px 0;}
#featured_product_carousel .product_name a{text-decoration:none; font-weight:bold; color:#133991;}
#featured_product_carousel .product_description{height:43px; color:#133991; overflow:hidden;}
#featured_product_carousel .product_price{text-align:center; color:#E32729; font-weight:bold; margin:3px 0; float:right; font-size:1.6em; position:absolute; bottom:34px; right:10px;}
#featured_product_carousel .product_price del{display:block; font-size:.8em; color:#133991;}
#featured_product_carousel .more_info{position:absolute; right:10px; bottom:10px; display:block; width:110px; text-align:center; font-weight:bold; color:#004FA2; background:#EAF0FF url('../image/BND_iconNavArrowGlass.png') -6px 55% no-repeat; text-decoration:none; padding:2px 2px 2px 20px; border:1px solid #90ACEC; font-size:1.3em;
   -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px; -moz-box-shadow:0 0 4px #90ACEC; -webkit-box-shadow:0 0 4px #90ACEC; box-shadow:0 0 4px #90ACEC;
}
#featured_product_carousel .add_to_cart{position:absolute; right:10px; bottom:10px; display:block; width:110px; text-align:center; font-weight:bold; color:#EE3528; background:#FBF0F0 url('../image/BND_iconNavArrowGlass-red.png') -6px 55% no-repeat; text-decoration:none; padding:2px 2px 2px 20px; border:1px solid #EE3528; font-size:1.3em;
       -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px; -moz-box-shadow:0 0 4px #EE3528; -webkit-box-shadow:0 0 4px #EE3528; box-shadow:0 0 4px #EE3528;
}

/* Subcategory Listing on product pages */
.subcategory_list{background-color:#D5E4F7; border:solid #90ACEC; border-width:0 1px 1px 1px; width:743px; float:left; padding:0 0 5px 0;
}
.subcategory_list ul{margin:0 1% 5px 1%; padding:0; width:98%; float:left; list-style:none;}
.subcategory_list li{margin:0; padding:1px 1%; width:30%; float:left;}
.subcategory_list li a{text-decoration:none; color:#0050A3; font-weight:bold;}
.subcategory_list a.slidetoggle{padding:10px; color:#0050A3; text-decoration:none;}

/* Account Login Page boxes */
.account_box{background:#EAF0FF; border: 1px solid #90ACEC; padding: 10px; min-height: 175px;
   -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;
}
#account_new_customer{float: left; display: inline-block; width: 49%;}
#account_returning_customer{float: right; display: inline-block; width: 49%;}

/* Product Page boxes  */
.product_addcart_container{background:#EAF0FF; border: 1px solid #90ACEC; padding:5px 10px; text-align:center;
   -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;
}
.product_addcart_container .button_addlist_wish{}
.product_addcart_container .button_addlist_wish img{vertical-align:middle;}
.product_addcart_container .button_addlist_shopping{}
.product_addcart_container .button_addlist_shopping img{vertical-align:middle;}
.product_variant_table{width: 100%; border-collapse: collapse;}
.product_variant_table td{text-align:left;padding:1px;}
.product_variant_table td+td{text-align:right;}
.product_variant_table td[align=center]{text-align:center;}
.product_variant_table b{color:#0050A3;}
.product_variant_item_number{}

.product_variant_savings_text{color:#0050A3; font-size:.9em;}
.product_variant_savings_price{color:green; font-size:.9em;}
.product_variant_price_discount{color:red;}
span.product_variant_price{}
del.product_variant_price{}

.new_list_container{text-align:left;border-top:1px dashed #90ACEC;padding-top:3px;}
.existing_list_container{text-align:left;border-top:1px dashed #90ACEC;padding-top:3px;}

.important{color:#D80D0F !important; font-weight:bold;}
.bimg{display:block; margin:0 auto;}
.fl{float:left;}
.fr{float:right;}

/* Fieldset (and emulated fieldset) styles*/
.fieldset{background:#EAF0FF; border: 1px solid #90ACEC; padding:10px; margin-bottom:10px;
   -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;
}

/* Corner radius */
.corner-tl { -moz-border-radius-topleft: 8px; -webkit-border-top-left-radius: 8px; border-top-left-radius: 8px; }
.corner-tr { -moz-border-radius-topright: 8px; -webkit-border-top-right-radius: 8px; border-top-right-radius: 8px; }
.corner-bl { -moz-border-radius-bottomleft: 8px; -webkit-border-bottom-left-radius: 8px; border-bottom-left-radius: 8px; }
.corner-br { -moz-border-radius-bottomright: 8px; -webkit-border-bottom-right-radius: 8px; border-bottom-right-radius: 8px; }
.corner-top { -moz-border-radius-topleft: 8px; -webkit-border-top-left-radius: 8px; border-top-left-radius: 8px; -moz-border-radius-topright: 8px; -webkit-border-top-right-radius: 8px; border-top-right-radius: 8px; }
.corner-bottom { -moz-border-radius-bottomleft: 8px; -webkit-border-bottom-left-radius: 8px; border-bottom-left-radius: 8px; -moz-border-radius-bottomright: 8px; -webkit-border-bottom-right-radius: 8px; border-bottom-right-radius: 8px; }
.corner-right {  -moz-border-radius-topright: 8px; -webkit-border-top-right-radius: 8px; border-top-right-radius: 8px; -moz-border-radius-bottomright: 8px; -webkit-border-bottom-right-radius: 8px; border-bottom-right-radius: 8px; }
.corner-left { -moz-border-radius-topleft: 8px; -webkit-border-top-left-radius: 8px; border-top-left-radius: 8px; -moz-border-radius-bottomleft: 8px; -webkit-border-bottom-left-radius: 8px; border-bottom-left-radius: 8px; }
.corner-all { -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px;}


/* Extra classes that get applied in IE via javascript to fix bugs */
#nav .li-last-child, #nav .fancy .li-last-child{border:none;}
#category .li-last-child{background:none;}


#ie6warning{display:block;width:100%; background-color:#FFC7CE; text-align:center; color:#9C0006; border:1px solid #9C0006; margin-bottom:10px; font-weight:bold; }
