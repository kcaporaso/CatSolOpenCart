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
        <td width="25%"><span class="required">*</span> <?php echo $entry_name; ?></td>
        <td><input size="45" type="text" name="name" value="<?php echo $name; ?>" />
          <br />
          <?php if ($error_name) { ?>
          <span class="error"><?php echo $error_name; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_state; ?></td>
        <td><select name="state_id">
            <?php foreach ($states as $s) { ?>
            <option value="<?php echo $s['id'] ?>" <?php if ($s['id'] == $state_id) { echo ' selected="selected"'; }?>><?php echo $s['name']; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_active; ?></td>
        <td><select name="active">
            <?php if ($active) { ?>
            <option value="0"><?php echo $text_disabled; ?></option>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <?php } else { ?>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <option value="1"><?php echo $text_enabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_free_shipping; ?></td>
        <td><select name="free_shipping">
            <?php if ($free_shipping) { ?>
            <option value="0"><?php echo $text_disabled; ?></option>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <?php } else { ?>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <option value="1"><?php echo $text_enabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_free_freight_over; ?></td>
        <td><input type="text" name="free_freight_over" value="<?php echo $free_freight_over; ?>" size="8"/>
          <br />
          <?php if ($error_free_freight_over) { ?>
          <span class="error"><?php echo $error_free_freight_over; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo "Sales Tax Customer Group"; ?></td>
        <td><select name="customer_group_id">
        <?php
           foreach ($customer_group as $cg) { ?>
              <option value="<?php echo $cg['customer_group_id']; ?>" <?php if ($cg['customer_group_id'] == $customer_group_id) { echo 'selected="selected"'; } ?>><?php echo $cg['group_name']; ?></option>
           <?php }
        ?>
        </select>&nbsp;&nbsp;<a href="<?php echo $customer_group_url . '&customer_group_id=' . $customer_group_id; ?>">View/Edit Customer Group</a></td>
      </tr>
      <tr>
        <td><?php echo "Tax Exempt"; ?></td>
        <td><select name="tax_exempt">
            <?php if ($tax_exempt) { ?>
            <option value="0"><?php echo $text_disabled; ?></option>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <?php } else { ?>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <option value="1"><?php echo $text_enabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_discount_1; ?></td>
        <td><input type="text" name="discount_1" value="<?php echo $discount_1; ?>" size="8"/>
          <br /><span class="help">(Numbers Only: e.g. 10.0 = 10%)</span>
          <?php if ($error_discount_1) { ?>
          <span class="error"><?php echo $error_discount_1; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_discount_2; ?></td>
        <td><input type="text" name="discount_2" value="<?php echo $discount_2; ?>" size="8"/>
          <br /><span class="help">(Numbers Only: e.g. 10.0 = 10%)</span>
          <?php if ($error_discount_2) { ?>
          <span class="error"><?php echo $error_discount_2; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_discount_3; ?></td>
        <td><input type="text" name="discount_3" value="<?php echo $discount_3; ?>" size="8"/>
          <br /><span class="help">(Numbers Only: e.g. 10.0 = 10%)</span>
          <?php if ($error_discount_3) { ?>
          <span class="error"><?php echo $error_discount_3; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_discount_4; ?></td>
        <td><input type="text" name="discount_4" value="<?php echo $discount_4; ?>" size="8"/>
          <br /><span class="help">(Numbers Only: e.g. 10.0 = 10%)</span>
          <?php if ($error_discount_4) { ?>
          <span class="error"><?php echo $error_discount_4; ?></span>
          <?php } ?></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
