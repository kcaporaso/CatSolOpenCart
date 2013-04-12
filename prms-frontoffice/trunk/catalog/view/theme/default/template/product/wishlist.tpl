<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <!--div class="sort">
    <div class="div1">
      <select name="sort" onchange="location=this.value">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if (($sort . '-' . $order) == $sorts['value']) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="div2"><?php echo $text_sort; ?></div>
  </div-->
  Wish Lists Coming Soon!!
  <?php //require_once DIR_FRONTOFFICE.'catalog/view/includes/product_list_common.php'; ?>
  <div class="pagination"><?php echo $pagination; ?></div>
</div>
<div class="bottom">&nbsp;</div>
