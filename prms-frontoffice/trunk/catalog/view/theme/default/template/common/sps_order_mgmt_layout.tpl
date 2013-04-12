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
    <!--[if lt IE 7]>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
    <![endif]-->
    <!--script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.3.2.min.js"></script-->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/thickbox/thickbox-compressed.js"></script>
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/thickbox/thickbox.css" />
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery.formatCurrency-1.1.0.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/tab.js"></script>    
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery.print.js"></script>    
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery.jqprint.js"></script>    
    <script type="text/javascript" src="catalog/view/javascript/shadowbox.js"></script>    
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/shadowbox.css" />
    <script type="text/javascript">Shadowbox.init();</script>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo reset(explode('/', $this->template)); ?>/stylesheet/calendar.css" />
    <script type="text/javascript" src="catalog/view/javascript/clock/clock.js"></script>    
    <?php 
    $calendarroute = strpos($this->request->get['route'], 'calendar');
    if ($calendarroute >0) { ?> 
    <link rel="stylesheet" href="catalog/view/theme/default/stylesheet/master.css" type="text/css" media="screen" charset="utf-8" />
    <script src="catalog/view/javascript/coda.js" type="text/javascript"> </script>
    <?php } ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js" type="text/javascript"></script>
    <link type="text/css" href="catalog/view/stylesheet/css/ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" /> 
</head>
<body>
<div id="container">
  <?php echo $header; ?>
  <?php if ($_SESSION['store_code'] == 'KBC') { ?> <br/><br/> <?php } ?> 
  <div id="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php // KBC DEMO HACK 
       if ($breadcrumb['text'] == 'Home') { $breadcrumb['text'] = "Catalog Home"; }
    ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div id="column_left">
  </div>
  <div id="column_right">
  </div>
  <div id="sps_order_mgmt_content">
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
