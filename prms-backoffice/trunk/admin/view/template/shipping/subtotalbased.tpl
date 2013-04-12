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
      <?php foreach ($geo_zones as $geo_zone) { ?>
      <tr>
        <td width="25%"><?php echo $geo_zone['name']; ?>:</td>
        <td>
        	<textarea name="subtotalbased_<?php echo $geo_zone['geo_zone_id']; ?>_rate" cols="40" rows="5"><?php echo ${'subtotalbased_' . $geo_zone['geo_zone_id'] . '_rate'}; ?></textarea>
        	<br/>
            <?php echo $entry_rate; ?>
        </td>
      </tr>
      <tr>
        <td><?php echo $geo_zone['name']; ?> <?php echo $entry_status; ?></td>
        <td><select name="subtotalbased_<?php echo $geo_zone['geo_zone_id']; ?>_status">
            <?php if (${'subtotalbased_' . $geo_zone['geo_zone_id'] . '_status'}) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          	</select>
          	<br />
          	<script type="text/javascript">
          		function toggle_minimum_charge_field_readonly (geozone_id) {
          			if ($("#subtotalbased_"+geozone_id+"_minimum_charge_flag").attr('checked')) {
          				$("#div_"+geozone_id+"_minimum_charge_amount").show();
          				$("#help_checked_"+geozone_id+"_minimum_charge").show();
          				$("#help_unchecked_"+geozone_id+"_minimum_charge").hide();
          			} else {
          				$("#div_"+geozone_id+"_minimum_charge_amount").hide();
          				$("#help_unchecked_"+geozone_id+"_minimum_charge").show();
          				$("#help_checked_"+geozone_id+"_minimum_charge").hide();
          			}
          		}	
          	</script>
          	<table style="border-collapse: collapse; padding:0; margin:0;">
          		<tr>
          			<td>
                      	Use Minimum Shipping Charge
                      	<input type="hidden" name="subtotalbased_<?php echo $geo_zone['geo_zone_id']; ?>_minimum_charge_flag" value="0" />
                      	<input type="checkbox" name="subtotalbased_<?php echo $geo_zone['geo_zone_id']; ?>_minimum_charge_flag" value="1" 
                      		<?php echo (${'subtotalbased_' . $geo_zone['geo_zone_id'] . '_minimum_charge_flag'})? 'checked':''; ?>
                      		id="subtotalbased_<?php echo $geo_zone['geo_zone_id']; ?>_minimum_charge_flag" 
                      		onClick="toggle_minimum_charge_field_readonly('<?php echo $geo_zone['geo_zone_id']; ?>');"
                      	/>
          			</td>
          			<!--td>
          				<span class="help" style="display:none;" id="help_checked_<?php echo $geo_zone['geo_zone_id']; ?>_minimum_charge">Use the greater of : the value computed above,<br />and the minimum charge specified here :</span>
          				<span class="help" style="display:none;" id="help_unchecked_<?php echo $geo_zone['geo_zone_id']; ?>_minimum_charge">Use the greater of : the value computed above,<br />and a specified minimum charge.</span>
          			</td-->
                  <td>
                	<?php 
                	    $minimum_charge_amount[$geo_zone['geo_zone_id']] = ${'subtotalbased_' . $geo_zone['geo_zone_id'] . '_minimum_charge_amount'};
                	?>
                	<div style="margin-left:10px;" id="div_<?php echo $geo_zone['geo_zone_id']; ?>_minimum_charge_amount">
                    	$<input type="text" size="5" name="subtotalbased_<?php echo $geo_zone['geo_zone_id']; ?>_minimum_charge_amount" 
                    		id="subtotalbased_<?php echo $geo_zone['geo_zone_id']; ?>_minimum_charge_amount" 
                    		value="<?php echo number_format($minimum_charge_amount[$geo_zone['geo_zone_id']], 2); ?>" 
                    	/>          	
                	</div>
                  </td>
          		</tr>
          	</table>
			<script type="text/javascript">
				toggle_minimum_charge_field_readonly('<?php echo $geo_zone['geo_zone_id']; ?>');
			</script>
        </td>
      </tr>
      <tr>
      	<td colspan="9"><div style="border-bottom: 1px dashed #000000; margin-top:6px; margin-bottom:6px;"></td>
      </tr>
      <?php } ?>   
      <tr>
        <td>Overall Status:</td>
        <td><select name="subtotalbased_status">
            <?php if ($subtotalbased_status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>        
      <tr>
        <td>
            <?php echo $entry_tax; ?>
            <span class="help">
            	Specifies tax to be applied on top of the shipping charge. Leave as "none" if not applicable.
        	</span>        
        </td>
        <td><select name="subtotalbased_tax_class_id">
            <option value="0"><?php echo $text_none; ?></option>
            <?php foreach ($tax_classes as $tax_class) { ?>
            <?php if ($tax_class['tax_class_id'] == $subtotalbased_tax_class_id) { ?>
            <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_sort_order; ?></td>
        <td><input type="text" name="subtotalbased_sort_order" value="<?php echo $subtotalbased_sort_order; ?>" size="1" /></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
