<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      <tr>
        <td><span class="required">*</span> <?php echo $entry_merchant; ?></td>
        <td><input type="text" name="worldpay_merchant" value="<?php echo $worldpay_merchant; ?>" />
          <br />
          <?php if ($error_merchant) { ?>
          <span class="error"><?php echo $error_merchant; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_password; ?></td>
        <td><input type="text" name="worldpay_password" value="<?php echo $worldpay_password; ?>" />
          <br />
          <?php if ($error_password) { ?>
          <span class="error"><?php echo $error_password; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_callback; ?></td>
        <td><textarea cols="40" rows="5"><?php echo $callback; ?></textarea></td>
      </tr>
      <tr>
        <td><?php echo $entry_test; ?></td>
        <td><select name="worldpay_test">
            <?php if ($worldpay_test == '0') { ?>
            <option value="0" selected="selected"><?php echo $text_off; ?></option>
            <?php } else { ?>
            <option value="0"><?php echo $text_off; ?></option>
            <?php } ?>
            <?php if ($worldpay_test == '100') { ?>
            <option value="100" selected="selected"><?php echo $text_successful; ?></option>
            <?php } else { ?>
            <option value="100"><?php echo $text_successful; ?></option>
            <?php } ?>
            <?php if ($worldpay_test == '101') { ?>
            <option value="101" selected="selected"><?php echo $text_declined; ?></option>
            <?php } else { ?>
            <option value="101"><?php echo $text_declined; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_order_status; ?></td>
        <td><select name="worldpay_order_status_id">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $worldpay_order_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_geo_zone; ?></td>
        <td><select name="worldpay_geo_zone_id">
            <option value="0"><?php echo $text_all_zones; ?></option>
            <?php foreach ($geo_zones as $geo_zone) { ?>
            <?php if ($geo_zone['geo_zone_id'] == $worldpay_geo_zone_id) { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_status; ?></td>
        <td><select name="worldpay_status">
            <?php if ($worldpay_status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_sort_order; ?></td>
        <td><input type="text" name="worldpay_sort_order" value="<?php echo $worldpay_sort_order; ?>" size="1" /></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
