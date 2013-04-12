<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="location='<?php echo $insert; ?>'" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_insert; ?></span><span class="button_right"></span></a><a onclick="$('form').submit();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_delete; ?></span><span class="button_right"></span></a>
  <a onclick="location='<?php echo $productpricingurl; ?>'" class="button"><span class="button_left button_cancel"></span><span class="button_middle">Cancel</span><span class="button_right"></span></a>
</div>
</div>
<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
  <table class="list">
    <thead>
      <tr>
        <td width="1" align="center"><input type="checkbox" onclick="$('input[name*=\'delete\']').attr('checked', this.checked);" /></td>
        
        <td class="left"><?php if ($sort == 'discount') { ?>
          	<a href="<?php echo $sort_discount; ?>" class="<?php echo strtolower($order); ?>">Discount</a>
          <?php } else { ?>
          	<a href="<?php echo $sort_discount; ?>">Discount</a>
          <?php } ?>
        </td>
        
        <td class="left"><?php if ($sort == 'date_start') { ?>
          	<a href="<?php echo $sort_date_start; ?>" class="<?php echo strtolower($order); ?>">Start Date</a>
          <?php } else { ?>
          	<a href="<?php echo $sort_date_start; ?>">Start Date</a>
          <?php } ?>
        </td>
        
        <td class="left"><?php if ($sort == 'date_end') { ?>
          	<a href="<?php echo $sort_date_end; ?>" class="<?php echo strtolower($order); ?>">End Date</a>
          <?php } else { ?>
          	<a href="<?php echo $sort_date_end; ?>">End Date</a>
          <?php } ?>
        </td>
        
        <td class="left"><?php if ($sort == 'active_flag') { ?>
          	<a href="<?php echo $sort_active_flag; ?>" class="<?php echo strtolower($order); ?>">Status</a>
          <?php } else { ?>
          	<a href="<?php echo $sort_active_flag; ?>">Status</a>
          <?php } ?>
        </td>  
                      
        <td class="right"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($globalspecials) { ?>
          <?php $class = 'odd'; ?>
          <?php foreach ($globalspecials as $globalspecial) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <tr class="<?php echo $class; ?>">
              
                <td align="center">
                  <?php if ($globalspecial['delete']) { ?>
                    <input type="checkbox" name="delete[]" value="<?php echo $globalspecial['id']; ?>" checked="checked" />
                  <?php } else { ?>
                    <input type="checkbox" name="delete[]" value="<?php echo $globalspecial['id']; ?>" />
                  <?php } ?>
                </td>
                
                <td class="left">
                	<?php echo $globalspecial['discount']; ?>
                </td>
                
                <td class="left">
                	<?php echo $globalspecial['date_start']; ?>
                </td>
                
                <td class="left">
                	<?php echo $globalspecial['date_end']; ?>
                </td>
                
                <td class="left">
                	<?php echo ($globalspecial['active_flag']=='1')? 'Enabled' : 'Disabled'; ?>
                </td>
                
                <td class="right"><?php foreach ($globalspecial['action'] as $action) { ?>
                  [&nbsp;<a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>&nbsp;]
                  <?php } ?>
                </td>
                  
              </tr>
          <?php } ?>
      <?php } else { ?>
          <tr class="even">
            <td class="center" colspan="9"><?php echo $text_no_results; ?></td>
          </tr>
      <?php } ?>
    </tbody>
  </table>
</form>
<div class="pagination"><?php echo $pagination; ?></div>
