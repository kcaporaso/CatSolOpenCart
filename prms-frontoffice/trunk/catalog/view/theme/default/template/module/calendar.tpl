<!-- Part of Events Calendar by Fido-X (http://www.fido-x.net) -->
<?php
   $usecustom = false;
   $basepath = "/home/andrea32/www/catsolonline.com/prms-frontoffice/trunk/";
   $iconpath = "catalog/view/theme/" . reset(explode('/', $this->template)) . "/image/";
   $testpath = $bathpath . $iconpath . $_SESSION['store_code'] . "_icon_calendar.png";
   if (file_exists($testpath)) {
      $usepath = $iconpath . $_SESSION['store_code'] . "_icon_calendar.png"; 
      $usecustom = true;
   } else {
      $usepath = $iconfpath . "icon_calendar.png";
   } 
?>
<div class="box">
  <?php if ($usecustom) { ?>
     <div class="top"><img src="<?php echo $usepath; ?>"/></div><br/><br/>
  <?php } else { ?>
     <div class="top" style="background: url('catalog/view/theme/<?php echo reset(explode('/', $this->template)); ?>/image/icon_calendar.png') 8px 8px no-repeat; padding-left: 30px;"><?php echo $heading_title; ?></div>
  <?php } ?>
  <div id="calendar" class="middle">
		<div id="month"><?php echo $show_month; ?></div>
		<div id="clock">
			<form name="clock">
				<div style="margin: 5px 0px 2px 0px; text-align: center;">
					<input name="clock" /><script type="text/javascript">showClock();</script>
				</div>
			</form>
		</div>
	</div>
  <div class="bottom"></div>
</div>
