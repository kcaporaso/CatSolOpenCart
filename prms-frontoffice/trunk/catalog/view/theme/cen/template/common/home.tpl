<div class="top">
  <!-- <h1><?php echo $heading_title; ?></h1> -->
</div>
<div class="middle">
  <!-- <div><?php echo $welcome; ?></div> -->
  <?php /*  ?><div><?php echo @$events; ?></div><? */ // enable to show Event on Home Page if on current day ?>
  <!-- <div class="heading">
  <?php if ($_SESSION['store_code'] != 'IPA') { ?>
  <?php echo $text_featured; ?>
  <?php } ?>
  </div> -->
  <?php require_once DIR_FRONTOFFICE.'catalog/view/includes/CEN_product_list_common.php'; ?>
</div>
<div class="bottom">&nbsp;</div>
