<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<script type="text/javascript" src="view/javascript/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/superfish/js/hoverIntent.js"></script>
<script type="text/javascript" src="view/javascript/jquery/superfish/js/superfish.js"></script>
<script type="text/javascript" src="view/javascript/jquery/tab.js"></script>
<script type="text/javascript" src="view/javascript/jquery/jquery.corner.js"></script>
<script type="text/javascript" src="view/javascript/tooltip/tooltip.js"></script>
<script type="text/javascript" src="view/javascript/jquery/jquery.jqprint.js"></script>
<script type="text/javascript" src="view/javascript/jquery/jquery-ui-1.8.6.custom.min.js"></script>
<link type="text/css" href="view/stylesheet/css/ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />
</head>
<body>
<div id="header"><?php echo $header; ?></div>
<div id="menu"><?php echo $menu; ?></div>
<div id="container">
  <?php /* ?><div id="column_left"></div><?php */ ?>
  <div id="content">
    <?php if ($breadcrumbs) { ?>
    <div class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
      <?php } ?>
    </div>
    <?php } ?>
    <?php echo $content; ?></div>
  <?php /* ?><div id="column_right"></div><?php */ ?>
</div>
<div id="footer"><?php echo $footer; ?></div>
<script type="text/javascript">
    $("table.list thead tr td:first-child").corner("tl");
    $("table.list thead tr td:last-child").corner("tr");
    $("div.tabs").corner("top");
</script>
</body>
</html>
