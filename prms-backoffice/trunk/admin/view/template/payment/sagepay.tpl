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
        <td width="25%"><span class="required">*</span> <?php echo $entry_vendor; ?></td>
        <td><input type="text" name="sagepay_vendor" value="<?php echo $sagepay_vendor; ?>" />
          <br />
          <?php if ($error_vendor) { ?>
          <span class="error"><?php echo $error_vendor; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_password; ?></td>
        <td><input type="text" name="sagepay_password" value="<?php echo $sagepay_password; ?>" />
          <br />
          <?php if ($error_password) { ?>
          <span class="error"><?php echo $error_password; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_test; ?></td>
        <td><select name="sagepay_test">
            <?php if ($sagepay_test == 'sim') { ?>
            <option value="sim" selected="selected"><?php echo $text_sim; ?></option>
            <?php } else { ?>
            <option value="sim"><?php echo $text_sim; ?></option>
            <?php } ?>
            <?php if ($sagepay_test == 'test') { ?>
            <option value="test" selected="selected"><?php echo $text_test; ?></option>
            <?php } else { ?>
            <option value="test"><?php echo $text_test; ?></option>
            <?php } ?>
            <?php if ($sagepay_test == 'live') { ?>
            <option value="live" selected="selected"><?php echo $text_live; ?></option>
            <?php } else { ?>
            <option value="live"><?php echo $text_live; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_transaction; ?></td>
        <td><select name="sagepay_transaction">
            <?php if ($sagepay_transaction == 'PAYMENT') { ?>
            <option value="PAYMENT" selected="selected"><?php echo $text_payment; ?></option>
            <?php } else { ?>
            <option value="PAYMENT"><?php echo $text_payment; ?></option>
            <?php } ?>
            <?php if ($sagepay_transaction == 'DEFERRED') { ?>
            <option value="DEFERRED" selected="selected"><?php echo $text_defered; ?></option>
            <?php } else { ?>
            <option value="DEFERRED"><?php echo $text_defered; ?></option>
            <?php } ?>
            <?php if ($sagepay_transaction == 'AUTHENTICATE') { ?>
            <option value="AUTHENTICATE" selected="selected"><?php echo $text_authenticate; ?></option>
            <?php } else { ?>
            <option value="AUTHENTICATE"><?php echo $text_authenticate; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_order_status; ?></td>
        <td><select name="sagepay_order_status_id">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $sagepay_order_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_geo_zone; ?></td>
        <td><select name="sagepay_geo_zone_id">
            <option value="0"><?php echo $text_all_zones; ?></option>
            <?php foreach ($geo_zones as $geo_zone) { ?>
            <?php if ($geo_zone['geo_zone_id'] == $sagepay_geo_zone_id) { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_status; ?></td>
        <td><select name="sagepay_status">
            <?php if ($sagepay_status) { ?>
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
        <td><input type="text" name="sagepay_sort_order" value="<?php echo $sagepay_sort_order; ?>" size="1" /></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
