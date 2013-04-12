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
        <td width="25%">
          <span class="required">*</span> <?php echo $entry_group_name; ?>
        </td>
        <td><input type="text" name="group_name" value="<?php echo $group_name; ?>" />
          <br />
          <?php if ($error_group_name) { ?>
          <span class="error"><?php echo $error_group_name; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_tax_class; ?></td>
        <td>
          <select name="group_tax_class_id">
            <?php foreach ($tax_classes as $tax_class) { ?>
              <?php if ($tax_class['tax_class_id'] == $group_tax_class_id) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
              <?php } else { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
              <?php } ?>
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td width="25%">
          <?php echo $entry_discount; ?><br />
          <span class="help"><?php echo $help_discount; ?></span>
        </td>
        <td><input type="text" name="group_discount" value="<?php echo $group_discount; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_status; ?></td>
        <td>
          <select name="status">
            <?php if ($status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td width="25%">
          Default for new Customers?
        </td>
        <td>
        	<input type="radio" name="default_flag" value="0"  <?php echo ($default_flag=='0' || (!isset($default_flag)) || $routeop=='insert')? 'checked' : ''; ?> /> No
        	&nbsp;&nbsp;&nbsp;
        	<input type="radio" name="default_flag" value="1"  <?php echo ((boolean)$default_flag)? 'checked' : ''; ?> /> Yes
        </td>
      </tr>      
    </table>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
