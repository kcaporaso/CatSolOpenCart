<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if (!$has_ownership_access): ?>
<div class="notify">You currently have read-only access to this Catalog and will not be able to save any changes.</div>
<?php endif; ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($user_can_access_sitefeature): ?>
      <div class="buttons">
      	<?php if ($has_ownership_access): ?><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle">Save</span><span class="button_right"></span></a><?php endif; ?>
      	<a onclick="window.close()" class="button"><span class="button_left button_cancel"></span><span class="button_middle">Cancel</span><span class="button_right"></span></a>
      </div>
  <?php endif; ?>
</div>
<div class="pagination"><?php echo $pagination; ?></div>
  <table class="list small" id="maintable">
    <thead>
      <tr><td colspan="8" style="background-color:white;color:black;text-align:center;font-weight:bold;font-size:10pt;">NOTE: Click Column Headers to Sort</td></tr> 
      <tr>
        <?php /* ?><td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'delete\']').attr('checked', this.checked);" /></td><?php */ ?>
        <td class="right"><?php if ($sort == 'p.product_id') { ?>
          <a href="<?php echo $sort_product; ?>" class="<?php echo strtolower($order); ?>">ID</a>
          <?php } else { ?>
          <a href="<?php echo $sort_product; ?>">ID</a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'user_name') { ?>
          	<a href="<?php echo $sort_user; ?>" class="<?php echo strtolower($order); ?>">User (Owner)</a>
          <?php } else { ?>
          	<a href="<?php echo $sort_user; ?>">User (Owner)</a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'pd.name') { ?>
          <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'p.ext_product_num') { ?>
          <a href="<?php echo $sort_ext_product_num; ?>" class="<?php echo strtolower($order); ?>">Item #</a>
          <?php } else { ?>
          <a href="<?php echo $sort_ext_product_num; ?>">Item #</a>
          <?php } ?></td>          
        <td class="left"><?php if ($sort == 'manufacturer_name') { ?>
          <a href="<?php echo $sort_manufacturer; ?>" class="<?php echo strtolower($order); ?>">Manufacturer</a>
          <?php } else { ?>
          <a href="<?php echo $sort_manufacturer; ?>">Manufacturer</a>
          <?php } ?></td>
        <!--td class="left"><?php if ($sort == 'productvariantgroup_name') { ?>
          <a href="<?php echo $sort_productvariantgroup; ?>" class="<?php echo strtolower($order); ?>">Variant Group</a>
          <?php } else { ?>
          <a href="<?php echo $sort_productvariantgroup; ?>">Variant Group</a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'min_gradelevel_name') { ?>
          <a href="<?php echo $sort_min_gradelevel; ?>" class="<?php echo strtolower($order); ?>">Min Grade Level</a>
          <?php } else { ?>
          <a href="<?php echo $sort_min_gradelevel; ?>">Min Grade Level</a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'max_gradelevel_name') { ?>
          <a href="<?php echo $sort_max_gradelevel; ?>" class="<?php echo strtolower($order); ?>">Max Grade Level</a>
          <?php } else { ?>
          <a href="<?php echo $sort_max_gradelevel; ?>">Max Grade Level</a>
          <?php } ?></td-->          
        <td class="left"><?php if ($sort == 'p.price') { ?>
          <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>">MSRP</a>
          <?php } else { ?>
          <a href="<?php echo $sort_price; ?>">MSRP</a>
          <?php } ?></td>          
        <td class="center"><?php if ($sort == 'included') { ?>
          <a href="<?php echo $sort_included; ?>" class="<?php echo strtolower($order); ?>">Included?</a>
          <?php } else { ?>
          <a href="<?php echo $sort_included; ?>">Included?</a>
          <?php } ?></td>
        <td class="right"><?php echo $column_action; ?></td>
      </tr>
      
    </thead>
    
    <tbody>
    
      <tr class="filter">
        <?php /* ?><td></td><?php */ ?>
        
        <td align="right"><input type="text" name="filter_product_id" value="<?php echo $filter_product_id; ?>" size="4"  style="text-align:right;"  /></td>
        
        <td><select name="filter_user_id">
            <option value="*"></option>
            <?php foreach ($users_with_products as $user) : ?>
                <?php if ($user['user_id'] == $filter_user_id) { ?>
                	<option value="<?php echo $user['user_id']; ?>" selected="selected"><?php echo $user['name']; ?></option>
                <?php } else { ?>
                	<option value="<?php echo $user['user_id']; ?>"><?php echo $user['name']; ?></option>
                <?php } ?>
            <?php endforeach ?>
          </select>
        </td>        
        <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
        <td><input type="text" name="filter_ext_product_num" value="<?php echo $filter_ext_product_num; ?>" /></td>
        <td><input type="text" name="filter_manufacturer_name" value="<?php echo $filter_manufacturer_name; ?>" /></td>
        <!--td><input type="text" name="filter_productvariantgroup_name" value="<?php echo $filter_productvariantgroup_name; ?>" /></td> 
        
        <td>
        	<select name="filter_min_gradelevel_id">
            	<option value="*"></option>
                <?php echo ($min_gradelevels_dropdown); ?>
          	</select>
        </td>
        
        <td>
        	<select name="filter_max_gradelevel_id">
            	<option value="*"></option>
                <?php echo ($max_gradelevels_dropdown); ?>
          	</select>
        </td--> 
               
        <td></td>
        <td><select name="filter_included">
            <option value="*"></option>
            <?php if ($filter_included): ?>
            	<option value="1" selected="selected">Checked</option>
            <?php else: ?>
            	<option value="1">Checked</option>
            <?php endif; ?>
            <?php if (!is_null($filter_included) && !$filter_included): ?>
            	<option value="0" selected="selected">Unchecked</option>
            <?php else: ?>
            	<option value="0">Unchecked</option>
            <?php endif; ?>
          </select></td>
        <td align="right"><input style="background-color:lightgreen;padding:3px;" type="button" value="<?php echo $button_filter; ?>" onclick="filter();" /></td>
        
      </tr>
      
      <?php if ($products): ?>
      
		<?php if ($has_ownership_access): ?><form action="<?php echo $this->url->https('catalog/product/productlistforproductset&productset_code='.$productset_code) ?>" method="post" enctype="multipart/form-data" id="form"><?php endif; ?>
      
        <?php $class = 'odd'; ?>
        <?php foreach ($products as $product): ?>
          
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
          
              <tr class="<?php echo $class; ?>">
              
              	<td class="right"><?php echo $product['product_id']; ?></td>
                <?php /* ?><td style="align: center;"><?php if ($product['delete']) { ?>
                  <input type="checkbox" name="delete[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                  <?php } else { ?>
                  <input type="checkbox" name="delete[]" value="<?php echo $product['product_id']; ?>" />
                  <?php } ?></td><?php */ ?>
                <td class="left"><?php echo $product['user_name']; ?></td>
                <td class="left"><?php echo $product['name']; ?></td>
                <td class="left"><?php echo $product['ext_product_num']; ?></td>
                <td class="left"><?php echo $product['manufacturer_name']; ?></td>
                <!--td class="left"><?php echo $product['productvariantgroup_name']; ?></td>
                <td class="left"><?php echo $product['min_gradelevel_name']; ?></td>
                <td class="left"><?php echo $product['max_gradelevel_name']; ?></td-->
                <td class="right"><?php echo $product['price']; ?></td>
                <td class="center">
                	<?php 
                	    if ($product['checked']) {
                	        $product_checked = 'checked';
                	    } else {
                	        $product_checked = '';
                	    }
                	    
                	    if (!$has_ownership_access) {
                	        $disabled_html = 'disabled';
                	    } elseif ($product['access_type_code'] == 'R') {
                	        $disabled_html = 'disabled';
                	    } elseif ($product['access_type_code'] == 'W') {
                	        $disabled_html = '';
                	    }
                	?>
                	<input type="hidden" name="product_ids[]" value="<?php echo $product['product_id']; ?>" />
                	<?php if ($disabled_html!='' && $product_checked): ?><input type="hidden" name="product_ids_selected[]" value="<?php echo $product['product_id']; ?>" /><?php endif; ?>
                	<input type="checkbox" name="product_ids_selected[]" value="<?php echo $product['product_id']; ?>" style="margin: 0; padding: 0;" <?php echo $product_checked; ?> <?php echo $disabled_html ?> />
                </td>
                <td class="right">
                	<?php
                        if (!$user_can_access_sitefeature) {
                            $product['access_type_code'] = 'R';
                        }
                	?>
                	[&nbsp;<a href="<?php echo $product['action'][$product['access_type_code']]['href']; ?>"><?php echo $product['action'][$product['access_type_code']]['text']; ?></a>&nbsp;]
                </td>
                
              </tr>
              
        <?php endforeach; ?>
          
		<?php if ($has_ownership_access): ?></form><?php endif; ?>       
          
      <?php else: ?>
      
          <tr class="even">
            <td class="center" colspan="99"><?php echo $text_no_results; ?></td>
          </tr>
      
      <?php endif; ?>
      
    </tbody>
    
  </table>
<?php /* ?></form><?php */ ?>
<div class="pagination"><?php echo $pagination; ?></div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/product/productlistforproductset&productset_code=<?php echo $productset_code ?>';

	var filter_product_id = $('input[name=\'filter_product_id\']').attr('value');
	
	if (filter_product_id) {
		url += '&filter_product_id=' + encodeURIComponent(filter_product_id);
	}
	
	var filter_user_id = $('select[name=\'filter_user_id\']').attr('value');
	
	if (filter_user_id != '*') {
		url += '&filter_user_id=' + encodeURIComponent(filter_user_id);
	}	
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_ext_product_num = $('input[name=\'filter_ext_product_num\']').attr('value');
	
	if (filter_ext_product_num) {
		url += '&filter_ext_product_num=' + encodeURIComponent(filter_ext_product_num);
	}
	
	var filter_manufacturer_name = $('input[name=\'filter_manufacturer_name\']').attr('value');
	
	if (filter_manufacturer_name) {
		url += '&filter_manufacturer_name=' + encodeURIComponent(filter_manufacturer_name);
	}
	
	/*var filter_productvariantgroup_name = $('input[name=\'filter_productvariantgroup_name\']').attr('value');
	
	if (filter_productvariantgroup_name) {
		url += '&filter_productvariantgroup_name=' + encodeURIComponent(filter_productvariantgroup_name);
	}
	
	var filter_min_gradelevel_id = $('select[name=\'filter_min_gradelevel_id\']').attr('value');
	
	if (filter_min_gradelevel_id != '*') {
		url += '&filter_min_gradelevel_id=' + encodeURIComponent(filter_min_gradelevel_id);
	}	
	
	var filter_max_gradelevel_id = $('select[name=\'filter_max_gradelevel_id\']').attr('value');
	
	if (filter_max_gradelevel_id != '*') {
		url += '&filter_max_gradelevel_id=' + encodeURIComponent(filter_max_gradelevel_id);
	}	
   */
	
	var filter_included = $('select[name=\'filter_included\']').attr('value');
	
	if (filter_included != '*') {
		url += '&filter_included=' + encodeURIComponent(filter_included);
	}	

	location = url;
}

if (screen.width >= 1280) {
	$('#maintable').css("margin-left", "-60px");
}
//--></script>
