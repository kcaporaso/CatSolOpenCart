<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
    <title><?php echo $title; ?></title>
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <base href="<?php echo $base; ?>" />
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.php" />
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
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo reset(explode('/', $this->template)); ?>/stylesheet/calendar.css" />
    <script type="text/javascript" src="catalog/view/javascript/clock/clock.js"></script>    
</head>
<body>
	<?php echo $content; ?>   
</body>
</html>
