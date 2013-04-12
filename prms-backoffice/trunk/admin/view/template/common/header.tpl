<div class="div1">
  <div class="div2"><?php echo $_SESSION['store_name'] ?> Administration 
  <?php if ($this->user->isSPS()) { echo "<span style='color:gold;'>&nbsp;[SPS Platinum System]</span>"; } ?>
  </div>
  <?php if ($logged) { ?>
  	<div class="div3"><?php echo $user; ?> | <a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a>
  | <a target="_blank" href="<?php echo $shop; ?>">Online Catalog</a>
</div>
  <?php } ?>
</div>
