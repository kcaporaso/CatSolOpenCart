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
          <span class="required">*</span> Discount<br />          
        </td>
        <td><input type="text" name="discount" value="<?php echo $discount; ?>" /><span class="help"><?php echo $help_discount; ?></span>
            <?php if ($error_discount): ?>
				<span class="error"><?php echo $error_discount; ?></span>
            <?php endif; ?>
        </td>
      </tr>
      
      <tr>
        <td width="25%">
          <span class="required">*</span> Start Date<br />
        </td>
        <td><input type="text" name="date_start" value="<?php echo $date_start; ?>" id="date_start" />
            <?php if ($error_date_start): ?>
				<span class="error"><?php echo $error_date_start; ?></span>
            <?php endif; ?>        
        </td>
      </tr>
      
      <tr>
        <td width="25%">
          <span class="required">*</span> End Date<br />
        </td>
        <td><input type="text" name="date_end" value="<?php echo $date_end; ?>" id="date_end" />
            <?php if ($error_date_end): ?>
				<span class="error"><?php echo $error_date_end; ?></span>
            <?php endif; ?>         
        </td>
      </tr>      
            
      <tr>
        <td>Status</td>
        <td>
          <select name="active_flag">
            <?php if ($active_flag) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select>
        </td>
      </tr>

    </table>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<link rel="stylesheet" type="text/css" href="view/stylesheet/datepicker.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date_start').datepicker({dateFormat: 'yy-mm-dd'});
});
$(document).ready(function() {
	$('#date_end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>