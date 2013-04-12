<div id="module_mfg" class="box">
  <div class="top">
    <img src="catalog/view/theme/default/image/<?php echo $_SESSION['store_code'];?>_icon_brand.png" alt="" /><br/>
  </div>
  <div id="mfg" class="middle" style="text-align: center;">
    <select style="width:88%" onchange="location=this.value">
      <option value=""><?php echo $text_select; ?></option>
      <?php foreach ($manufacturers as $manufacturer) { ?>
      <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
      <option value="<?php echo $manufacturer['href']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
