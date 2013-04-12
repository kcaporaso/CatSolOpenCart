<style>
	iframe {
		border:1px solid #B0B0B0;
	}
</style>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="mainform">
  <div id="tab_general" class="page">
    <table class="form">
      <?php foreach ($languages as $language) { ?>
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_name; ?></td>
        <td><input name="coupon_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo @$coupon_description[$language['language_id']]['name']; ?>" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
          <br />
          <?php if (@$error_name[$language['language_id']]) { ?>
          <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_description; ?></td>
        <td><textarea name="coupon_description[<?php echo $language['language_id']; ?>][description]" cols="40" rows="5"><?php echo @$coupon_description[$language['language_id']]['description']; ?></textarea> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" />
          <br />
          <?php if (@$error_description[$language['language_id']]) { ?>
          <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
          <?php } ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_code; ?></td>
        <td>
        	<input type="text" name="code" value="<?php echo $code; ?>" />
          	<br />
            <?php if ($error_code) { ?>
            	<span class="error"><?php echo $error_code; ?></span>
            <?php } ?>
            <?php if ($error_code_exists): ?>
            	<span class="error"><?php echo $error_code_exists; ?></span>
            <?php endif; ?>
        </td>
      </tr>

      <tr>
        <td><?php echo $entry_date_start; ?></td>
        <td><input type="text" name="date_start" value="<?php echo $date_start; ?>" size="12" id="date_start" /></td>
      </tr>
      
      <tr>
        <td><?php echo $entry_date_end; ?></td>
        <td><input type="text" name="date_end" value="<?php echo $date_end; ?>" size="12" id="date_end" /></td>
      </tr>
      
      <tr>
      	<td colspan="9">
      	
      	</td>
      </tr>
      
      <tr>
        <td>Eligible Products:
        	<span class="help">A Product is eligible under this Coupon if it meets this criteria.</span>
        </td>
        <td>
        	<?php //if ($this->request->get['coupon_id']): ?>
        	
        		<div class="warning" id="save_warning_eligible_products" style="display:none;">If you change the Eligible Products Mode. Don't forget<br>to click on Save above to make this change permanent.</div>
            <table>
            <tr><td>
        		<input onClick="handle_qualifying_products_mode_selection('ALL')" type="radio" name="qualifying_products_mode" value="ALL" id="qualifying_products_mode_ALL" <?php echo ($qualifying_products_mode=='ALL')? 'checked' : ''; ?> /> <label for="qualifying_products_mode_ALL">ALL Products</label> 
        		&nbsp;&nbsp;&nbsp;
            </td><td>
        		<input onClick="handle_qualifying_products_mode_selection('BY_PRODUCT')" type="radio" name="qualifying_products_mode" value="BY_PRODUCT" id="qualifying_products_mode_BY_PRODUCT" <?php echo ($qualifying_products_mode=='BY_PRODUCT')? 'checked' : ''; ?> /> <label for="qualifying_products_mode_BY_PRODUCT">as Individual Products</label>
        		&nbsp;&nbsp;&nbsp;
            </td><td>
        		<input onClick="handle_qualifying_products_mode_selection('BY_CAT_N_MANU')" type="radio" name="qualifying_products_mode" value="BY_CAT_N_MANU" id="qualifying_products_mode_BY_CAT_N_MANU" <?php echo ($qualifying_products_mode=='BY_CAT_N_MANU')? 'checked' : ''; ?> /> <label for="qualifying_products_mode_BY_CAT_N_MANU">by Category and Manufacturer</label>
            </td>
            </tr>
            <!--tr>
            <td>
        		  <input onClick="handle_qualifying_products_mode_selection('BUY_X_GET_Y_FREE')" type="radio" name="qualifying_products_mode" value="BY_X_GET_Y_FREE" id="qualifying_products_mode_BUY_X_GET_Y_FREE" <?php echo ($qualifying_products_mode=='BUY_X_GET_Y_FREE')? 'checked' : ''; ?> /> <label for="qualifying_products_mode_BUY_X_GET_Y_FREE">Buy X Get Y Free</label>

            </td>
            </tr-->
            </table>
        		<br><br>        		
        		
            	<iframe scrolling="auto" width="540" height="260" src="<?php echo $coupon_products_iframe_src ?>" id="qualifying_products_BY_PRODUCT" ></iframe>

            	<div style="border:1px solid #B0B0B0; width: 580px; height: 660px; text-align:center;" id="qualifying_products_BY_CAT_N_MANU">
            	
            		<br><br>
                	<iframe scrolling="no" width="500" height="280" src="<?php echo $coupon_categories_iframe_src ?>"></iframe>
                	
                	<div align="center"><strong><br>AND<br><br></strong></div>
                	
                	<iframe scrolling="auto" width="500" height="280" src="<?php echo $coupon_manufacturers_iframe_src ?>">
                	
                	</iframe>
            	
            	</div>

               <iframe scrolling="auto" width="540" height="260" src="<?php echo $coupon_buy_x_get_y_free_iframe_src ?>" id="qualifying_products_BUY_X_GET_Y_FREE"></iframe>


        	<?php //else: ?>
        		<!--span class="help">This is a new unsaved Coupon. Please save it first, then Edit it to select Eligible Products.</span-->
        	<?php //endif; ?>
        	
        	<br>
         <?php /* not sure if this is needed ?>	
        	<div class="scrollbox">
            <?php echo count($products); ?>
                <?php $class = 'odd'; ?>
                <?php foreach ($products as $product) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array($product['product_id'], $coupon_product)) { ?>
                  <input type="checkbox" name="coupon_product[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                  <?php echo $product['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="coupon_product[]" value="<?php echo $product['product_id']; ?>" />
                  <?php echo $product['name']; ?>
                  <?php } ?>
                </div>
                <?php } ?>
          	</div>          
            <?php */ ?>
        </td>
      </tr>
            
      <tr>
        <td><?php echo $entry_type; ?></td>
        <td><select name="type">
            <?php if ($type == 'P') { ?>
            <option value="P" selected="selected"><?php echo $text_percent; ?></option>
            <?php } else { ?>
            <option value="P"><?php echo $text_percent; ?></option>
            <?php } ?>
            <?php if ($type == 'F') { ?>
            <option value="F" selected="selected"><?php echo $text_amount; ?></option>
            <?php } else { ?>
            <option value="F"><?php echo $text_amount; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_discount; ?></td>
        <td><input type="text" name="discount" value="<?php echo $discount; ?>" /></td>
      </tr>
      <tr>
        <td>Minimum Order Amount:</td>
        <td><input type="text" name="total" value="<?php echo $total; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_shipping; ?></td>
        <td><?php if ($shipping) { ?>
          <input type="radio" name="shipping" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="shipping" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="shipping" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="shipping" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
      </tr>

      <tr>
        <td><?php echo $entry_uses_total; ?></td>
        <td><input type="text" name="uses_total" value="<?php echo $uses_total; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_uses_customer; ?></td>
        <td><input type="text" name="uses_customer" value="<?php echo $uses_customer; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_status; ?></td>
        <td><select name="status">
            <?php if ($status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>
    </table>
  </div>
</form>
<link rel="stylesheet" type="text/css" href="view/stylesheet/datepicker.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.min.js"></script>
<script type="text/javascript"><!--

function handle_qualifying_products_mode_selection (mode) {

	show_hide_qualifying_products_panes(mode);

	show_save_warning_eligible_products(mode);
	
}

function show_hide_qualifying_products_panes (mode) {

	if (mode == 'ALL') {
		$('#qualifying_products_BY_PRODUCT').hide();
		$('#qualifying_products_BY_CAT_N_MANU').hide();
		$('#qualifying_products_BUY_X_GET_Y_FREE').hide();
	} else if (mode == 'BY_PRODUCT') {
		$('#qualifying_products_BY_PRODUCT').show();
		$('#qualifying_products_BY_CAT_N_MANU').hide();
		$('#qualifying_products_BUY_X_GET_Y_FREE').hide();
	} else if (mode == 'BY_CAT_N_MANU') {
		$('#qualifying_products_BY_CAT_N_MANU').show();
		$('#qualifying_products_BY_PRODUCT').hide();
		$('#qualifying_products_BUY_X_GET_Y_FREE').hide();
	} else if (mode == 'BUY_X_GET_Y_FREE') {
		$('#qualifying_products_BUY_X_GET_Y_FREE').show();
		$('#qualifying_products_BY_PRODUCT').hide();
		$('#qualifying_products_BY_CAT_N_MANU').hide();
   }
	
}

function show_save_warning_eligible_products (mode) {

	if (mode == '<?php echo $qualifying_products_mode; ?>') {
		$('#save_warning_eligible_products').hide();
	} else {
		$('#save_warning_eligible_products').show();
	}
	
}

$(document).ready(function() {
	
	$('#date_start').datepicker({dateFormat: 'yy-mm-dd'});	
	$('#date_end').datepicker({dateFormat: 'yy-mm-dd'});

	handle_qualifying_products_mode_selection('<?php echo $qualifying_products_mode; ?>');
		
});


//--></script>
<script type="text/javascript"><!--

$.tabs('.tabs a');

//--></script>

