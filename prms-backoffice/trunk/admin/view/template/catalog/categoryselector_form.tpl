<style>
    body { /* hacks to isolate explorer here */
    	overflow-x: hidden;
    	background-color: White !important;
    }
    html[xmlns] { /* hacks to isolate mozilla here */
    	height: 100%;
    	overflow: -moz-scrollbars-vertical;
    }	
</style>
<?php if ($success): ?>
	<div id="notification_success" style="width:420px;" class="success"><?php echo $success; ?></div>
<?php endif; ?>
<form action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data" id="categoryselector_form">

    <table class="form" style="width:444px;" >
    	
      	<tr>
      		<td colspan="9">
      			<select multiple size="12" name="category_ids[]">
      				<option <?php echo (!$coupon_category_ids)? 'selected' : ''; ?> >[ no restriction ]</option>
      				<?php echo $category_dropdown_options; ?>
      			</select>
      		</td>
      	</tr>
         
        <tr>
        	<td colspan="9">
        		You must click "Save" here to make any changes permanent.<br><br>
	        	<div class="buttons"><a onclick="$('#categoryselector_form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a></div>
	        	<input type="hidden" name="lookup_type" value="<?php echo $lookup_type; ?>" />
	        	<input type="hidden" name="object_name" value="<?php echo $object_name; ?>" />
	        	<input type="hidden" name="object_record_id" value="<?php echo $object_record_id; ?>" />
        	</td>
       	</tr>		
	        
    </table>

</form>
<script type="text/javascript"><!--

$(document).ready(function() {

	$("#notification_success").fadeOut(3777);
	
});

//--></script>