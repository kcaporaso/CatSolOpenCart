<div class="top">
  <h1><?php echo $heading_title; ?>&nbsp;&nbsp;<a href="<?php echo $shop_now; ?>">SHOP NOW</a></h1>
</div>
<div class="middle">
  <div style="float:left;padding-right:15px;">
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>

  <p><b><?php echo $text_my_account; ?></b></p>
  <ul>
    <?php if (!$this->customer->isSPS()) { ?>
    <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
    <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
    <?php } ?>
    <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
  </ul>
  <p><b><?php echo $text_my_orders; ?></b></p>
  <ul>
    <li><a href="<?php echo $history; ?>"><?php echo $text_history; ?></a></li>
    <?php /* ?><li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li><?php */ ?>
  </ul>
  <?php if ($this->customer->isSPS()) { ?>
  <p><b><?php echo "Your Shopping Lists"; ?></b></p>
  <ul>
    <?php 
    if (count($shop_lists)) {
       foreach ($shop_lists as $list) {
       ?>
          <li><a href="<?php echo $list_url . '#' . $list['id']; ?>"><?php echo $list['name']; ?></a></li>
       <?php
       }
    } else {
    ?>
    <li>No saved shopping lists</li>
    <?php
    }
    ?>
  </ul>
  <?php } ?>
  <?php if (!$this->customer->isSPS() && $site_is_gold) { ?>
  
  <p><b><?php echo "Your Wish Lists"; ?></b></p>
  <ul>
    <?php 
    if (count($wish_lists)) {
       foreach ($wish_lists as $list) {
       ?>
          <li><a href="<?php echo $list_url . '#' . $list['id']; ?>"><?php echo $list['name']; ?></a></li>
       <?php
       }
    } else {
    ?>
    <li>No saved wish lists</li>
    <?php
    }
    ?>
  </ul>
  <?php } ?>
  <?php if (!$this->customer->isSPS()) { ?>
  <p><b><?php echo $text_my_newsletter; ?></b></p>
  <ul>
    <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
  </ul>
  <?php } ?>
  </div>
</div>
<div class="bottom">&nbsp;</div>
