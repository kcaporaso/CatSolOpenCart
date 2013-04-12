<div id="information" class="box">
  <div class="top">
  <img src="catalog/view/theme/default/image/<?php echo $_SESSION['store_code']?>_icon_information.png" alt="" /><br/>
  </div>
  <div class="middle">
    <ul>
      <?php foreach ($informations as $information) { ?>
      <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
      <?php } ?>
      <!-- <li><a href="<?php echo $calendar; ?>"><?php echo $text_calendar; ?></a></li> -->
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
      <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
      <?php if ($gold_site) { ?>
      <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
      <?php } ?>
    </ul>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
