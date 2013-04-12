<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
	<h1><?php echo $heading_title; ?></h1>
  	<div class="buttons">
  	    <a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle">Save</span><span class="button_right"></span></a>
  		<a onclick="location='/admin'" class="button"><span class="button_left button_cancel"></span><span class="button_middle">Cancel</span><span class="button_right"></span></a>
  	</div>
</div>
<form action="<?php /* echo $delete; */ ?>" method="post" enctype="multipart/form-data" id="form" name="this_form">
  <table class="list">
    <thead>
      <tr>        
        <td class="left"><?php if ($sort == 'name') { ?>
          <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
          <?php } ?></td>
        <!--td class="left"><?php if ($sort == 'iso_code_2') { ?>
          <a href="<?php echo $sort_iso_code_2; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_iso_code_2; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_iso_code_2; ?>"><?php echo $column_iso_code_2; ?></a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'iso_code_3') { ?>
          <a href="<?php echo $sort_iso_code_3; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_iso_code_3; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_iso_code_3; ?>"><?php echo $column_iso_code_3; ?></a>
          <?php } ?></td-->
        <td class="center"><?php if ($sort == 'included') { ?>
          <a href="<?php echo $sort_included; ?>" class="<?php echo strtolower($order); ?>">Do Business With?</a>
          <?php } else { ?>
          <a href="<?php echo $sort_included; ?>">Do Business With?</a>
          <?php } ?></td>
        <td width="1"></td>
      </tr>
    </thead>
    <tbody>
    
      <tr class="filter">

        <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
        <!--td></td>
        <td></td-->
        <td class="center"><select name="filter_included">
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
        <td align="right"><input type="button" value="<?php echo $button_filter; ?>" onclick="filter();" /></td>
        
      </tr>  
      
      <tr style="background-color: #DDDDDD;">
      	<td>
      	
      	</td>
      	<td align="center">
			<input type="checkbox" id="batch_checker" />&nbsp; Check/Uncheck All
      	</td>
      	<td>
      	
      	</td>
      </tr>  
    
      <?php if ($countries) { ?>
          <?php $class = 'odd'; ?>
          <?php foreach ($countries as $country) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <tr class="<?php echo $class; ?>">
                <td class="left"><?php echo $country['name']; ?></td>
                <!--td class="left"><?php echo $country['iso_code_2']; ?></td>
                <td class="left"><?php echo $country['iso_code_3']; ?></td-->
                <td class="center">
                	<?php 
                	    if ($country['checked']) {
                	        $country_checked = 'checked';
                	    } else {
                	        $country_checked = '';
                	    }
                	?>
                	<input type="hidden" 	name="country_ids[]" value="<?php echo $country['country_id']; ?>" />
                	<input type="checkbox" 	name="country_ids_selected[]" value="<?php echo $country['country_id']; ?>" style="margin: 0; padding: 0;" <?php echo $country_checked; ?> />
                </td>
                <td></td>
              </tr>
          <?php } ?>
      <?php } else { ?>
          <tr class="even">
            <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
          </tr>
      <?php } ?>
    </tbody>
  </table>
</form>
<div class="pagination"><?php echo $pagination; ?></div>
<script type="text/javascript"><!--

    function filter() {
    	
    	url = 'index.php?route=localisation/country/countrylistforstore&store_code=<?php echo $store_code ?>';
    	
    	var filter_name = $('input[name=\'filter_name\']').attr('value');
    	
    	if (filter_name) {
    		url += '&filter_name=' + encodeURIComponent(filter_name);
    	}
    	
    	var filter_included = $('select[name=\'filter_included\']').attr('value');
    	
    	if (filter_included != '*') {
    		url += '&filter_included=' + encodeURIComponent(filter_included);
    	}	
    
    	location = url;	
    }
    
    $(document).ready(function() 
        { 
            $("#batch_checker").click(function() 
            {
                var checked_status = this.checked; 
                $("input[name=country_ids_selected\\[\\]]").each(function() 
                { 
                    this.checked = checked_status; 
                }); 
            }); 
        }
    ); 
    
//--></script>
