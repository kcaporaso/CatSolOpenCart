<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
    <title><?php echo $title; ?></title>
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <base href="<?php echo $base; ?>" />
    <?php 
    $style = 'catalog/view/theme/default/stylesheet/'.$_SESSION['store_code'].'_stylesheet.php';
    ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $style;?>"/>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/cen/stylesheet/anylinkmenu.css"/>
    <!--[if lt IE 7]>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
    <![endif]-->
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/thickbox/thickbox-compressed.js"></script>
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/thickbox/thickbox.css" />
    <script type="text/javascript" src="catalog/view/javascript/jquery/tab.js"></script>    
    <script type="text/javascript" src="catalog/view/javascript/shadowbox.js"></script>    
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/shadowbox.css" />
    <script type="text/javascript">Shadowbox.init();</script>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/calendar.css" />
    <script type="text/javascript" src="catalog/view/javascript/clock/clock.js"></script>    
    <?php 
    $calendarroute = strpos($this->request->get['route'], 'calendar');
    if ($calendarroute >0) { ?> 
    <link rel="stylesheet" href="catalog/view/theme/default/stylesheet/master.css" type="text/css" media="screen" charset="utf-8" />
    <script src="catalog/view/javascript/coda.js" type="text/javascript"> </script>
    <?php } ?>

<script type="text/javascript" src="catalog/view/javascript/custom/CEN/menucontents.js"></script>

<script type="text/javascript" src="catalog/view/javascript/custom/CEN/anylinkmenu.js">

/***********************************************
* AnyLink JS Drop Down Menu v2.0- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Project Page at http://www.dynamicdrive.com/dynamicindex1/dropmenuindex.htm for full source code
***********************************************/

</script>

<script type="text/javascript">

//anylinkmenu.init("menu_anchors_class") //Pass in the CSS class of anchor links (that contain a sub menu)
anylinkmenu.init("menuanchorclass")

$(document).ready(function(){
	if( $('#category li:has(ul)').is('li') ){
		$('#category > ul > li').not(':has(ul)').hide();
	}
	anylinkmenu1.items = [];
	$('#category > ul > li > a').each(function(index, element){
		anylinkmenu1.items.push([$(element).text(), $(element).attr('href')]);
	});
	
});

</script>
    
</head>
<?php if($this->request->get['route']){$targetid = 'id="'.str_replace('/','__',$this->request->get['route']).'"';}else{ $targetid = '';}?>
<body <?php echo($targetid); ?>>
<div id="container">
  <div id="header"><?php echo $header; ?></div>
  <?php if ($_SESSION['store_code'] == 'KBC') { ?> <br/><br/> <?php } ?> 
  <div id="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php // KBC DEMO HACK 
       if ($breadcrumb['text'] == 'Home') { $breadcrumb['text'] = "Catalog Home"; }
    ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php 
    $checkoutroute = strpos($this->request->get['route'], 'checkout/shipping'); 
    if ($checkoutroute !== 0) {
       $checkoutroute = strpos($this->request->get['route'], 'checkout/payment'); 
    }
    if ($checkoutroute !== 0) {
       $checkoutroute = strpos($this->request->get['route'], 'checkout/confirm'); 
    }
    //echo $this->request->get['route'];
    //echo $checkoutroute;
  ?>
  <div id="column_left">
    <?php foreach ($modules as $module) { ?>
<?php //echo $module['code'] ?>
    <?php if ($checkoutroute === 0) { ?> 
    <?php if ($module['code'] == 'information') { ?>
    <?php   if ($module['position'] == 'left') { ?>
    <?php     echo ${$module['code']}; ?>
    <?php   } ?>
    <?php } ?>
    <?php } else { ?>
    <?php if ($module['position'] == 'left') { ?>
    <?php echo ${$module['code']}; ?>
    <?php } ?>
    <?php } ?>
    <?php } ?>
  </div>
  <div id="column_right">
    <?php foreach ($modules as $module) { ?>
    <?php if ($checkoutroute === 0) { ?>
    <?php if ($module['code'] == 'search') { ?>
    <?php   if ($module['position'] == 'right') { ?>
    <?php      echo ${$module['code']}; ?>
    <?php   } ?>
    <?php } ?>
    <?php } else { ?>
    <?php   if ($module['position'] == 'right') { ?>
    <?php      echo ${$module['code']}; ?>
    <?php   } ?>
   <?php } ?>
   <?php } ?>
  </div>
  <div id="content">
	<?php echo $content; ?>   
  </div>
  <div id="footer"><?php echo $footer; ?></div>
</div>

<?php if ($this->config->get('config_googleanalytics_code')): ?>
<script type="text/javascript">
  var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
  document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
  try{
     var pageTracker = _gat._getTracker("<?php echo $this->config->get('config_googleanalytics_code') ?>");
     pageTracker._trackPageview();
  } catch(err) {}</script>
<?php endif; ?>      
</body>
</html>
