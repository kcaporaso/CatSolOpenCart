<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle" style="margin-top:0px; padding-top:0px;">
  <?php if ($products) { ?>
	<?php if (defined('BENDER')) { ?>
	<?php require_once DIR_FRONTOFFICE.'catalog/view/includes/product_list_featured.php'; ?>
	<?php } else { ?>
	<?php require_once DIR_FRONTOFFICE.'catalog/view/includes/retail_product_list_featured.php'; ?>
	<?php } ?>
  <?php } ?>
</div>
<div class="bottom">&nbsp;</div>
