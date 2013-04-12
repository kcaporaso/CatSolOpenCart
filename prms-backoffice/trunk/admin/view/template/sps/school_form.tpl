<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons">
  <?php if (!empty($school_id)) { ?><a onclick="location='<?php echo $add_approval_chain; ?>';" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_chain; ?></span><span class="button_right"></span></a> <?php } ?>
  <a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs">
<a tab="#tab_general"><?php echo $tab_general; ?></a>
<a tab="#tab_billing"><?php echo $tab_billing; ?></a>
</div>
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
        <td><?php echo $entry_district; ?></td>
        <td><select name="district_id">
               <?php foreach ($districts as $district) { ?>
                  <option value="<?php echo $district['id']; ?>" 
                          <?php if ($district['id'] == $district_id) { echo 'selected="selected"'; } ?> ><?php echo $district['name']; ?></option>
               <?php } ?>
            </select>
        </td>
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
        <td><?php echo $entry_address1; ?></td>
        <td><input type="text" name="address1" value="<?php echo $address1; ?>" size="45" />
          <br />
          <?php if ($error_address1) { ?>
          <span class="error"><?php echo $error_address1; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_address2; ?></td>
        <td><input type="text" name="address2" value="<?php echo $address2; ?>" />
          <br />
          <?php if ($error_address2) { ?>
          <span class="error"><?php echo $error_address2; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_city; ?></td>
        <td><input type="text" name="city" value="<?php echo $city; ?>" />
          <br />
          <?php if ($error_city) { ?>
          <span class="error"><?php echo $error_city; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_state; ?></td>
        <td><input type="text" name="state" value="<?php echo $state; ?>" />
          <br />
          <?php if ($error_state) { ?>
          <span class="error"><?php echo $error_state; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_zipcode; ?></td>
        <td><input type="text" name="zipcode" value="<?php echo $zipcode; ?>" />
          <br />
          <?php if ($error_zipcode) { ?>
          <span class="error"><?php echo $error_zipcode; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_county; ?></td>
        <td><input type="text" name="county" value="<?php echo $county; ?>" />
          <br />
          <?php if ($error_county) { ?>
          <span class="error"><?php echo $error_county; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_country; ?></td>
        <td><input type="text" name="country" value="<?php echo $country; ?>" />
          <br />
          <?php if ($error_country) { ?>
          <span class="error"><?php echo $error_country; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_phone; ?></td>
        <td><input type="text" name="phone" value="<?php echo $phone; ?>" />
          <br />
          <?php if ($error_phone) { ?>
          <span class="error"><?php echo $error_phone; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_fax; ?></td>
        <td><input type="text" name="fax" value="<?php echo $fax; ?>" />
          <br />
          <?php if ($error_fax) { ?>
          <span class="error"><?php echo $error_fax; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_url; ?></td>
        <td><input type="text" name="url" value="<?php echo $url; ?>" />
          <br />
          <?php if ($error_url) { ?>
          <span class="error"><?php echo $error_url; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_email; ?></td>
        <td><input type="text" name="email" value="<?php echo $email; ?>" />
          <br />
          <?php if ($error_email) { ?>
          <span class="error"><?php echo $error_email; ?></span>
          <?php } ?></td>
      </tr>

      <!-- NOT SURE THIS BELONGS HERE tr>
        <td><?php echo $entry_instant_approval; ?></td>
        <td><select name="instant_approval">
            <?php if ($instant_approval) { ?>
            <option value="0"><?php echo $text_disabled; ?></option>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <?php } else { ?>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <option value="1"><?php echo $text_enabled; ?></option>
            <?php } ?>
          </select></td>
      </tr-->
      <?php if (!empty($school_id)) { ?>
      <tr>
        <td><?php echo $entry_approval_chain; ?></td>
        <td><select name="approval_chain_id">
           <?php if (!count($chains)) { ?>
              <option value="none">None Defined</option>
           <?php } ?>
           <?php foreach($chains as $chain) {?> 
              <option value="<?php echo $chain['id']; ?>" <?php if ($approval_chain_id == $chain['id']) { echo ' selected="selected"'; } ?> ><?php echo $chain['name']; ?></option>
           <?php } ?>
        </select></td>
      </tr>
      <?php } ?>
    </table>
  </div>
  <div id="tab_billing" class="page">
    <table class="form">
      <tr>
        <td width="25%"><?php echo $entry_billing_firstname; ?></td>
        <td><input size="45" type="text" name="billing_firstname" value="<?php echo $billing_firstname; ?>" /></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_billing_lastname; ?></td>
        <td><input size="45" type="text" name="billing_lastname" value="<?php echo $billing_lastname; ?>" /></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_billing_address1; ?></td>
        <td><input size="45" type="text" name="billing_address1" value="<?php echo $billing_address1; ?>" /></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_billing_address2; ?></td>
        <td><input size="45" type="text" name="billing_address2" value="<?php echo $billing_address2; ?>" /></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_billing_city; ?></td>
        <td><input size="45" type="text" name="billing_city" value="<?php echo $billing_city; ?>" /></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_billing_state; ?></td>
        <td><input size="2" type="text" name="billing_state" value="<?php echo $billing_state; ?>" /></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_billing_zipcode; ?></td>
        <td><input size="15" type="text" name="billing_zipcode" value="<?php echo $billing_zipcode; ?>" /></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_billing_phone; ?></td>
        <td><input size="15" type="text" name="billing_phone" value="<?php echo $billing_phone; ?>" /></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
