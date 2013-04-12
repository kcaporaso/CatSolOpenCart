<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="location='<?php echo $insert; ?>'" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_insert; ?></span><span class="button_right"></span></a><a onclick="$('form').submit();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_delete; ?></span><span class="button_right"></span></a></div>
</div>
<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
  <table class="list">
    <thead>
      <tr>
        <td width="1" align="center"><input type="checkbox" onclick="$('input[name*=\'delete\']').attr('checked', this.checked);" /></td>

        <td class="right"><?php if ($sort == 'id') { ?>
          	<a href="<?php echo $sort_id; ?>" class="<?php echo strtolower($order); ?>">ID</a>
          <?php } else { ?>
          	<a href="<?php echo $sort_id; ?>">ID</a>
          <?php } ?>
        </td> 
        
        <td class="left"><?php if ($sort == 'name') { ?>
          	<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Name</a>
          <?php } else { ?>
          	<a href="<?php echo $sort_name; ?>">Name</a>
          <?php } ?>
        </td> 
        
        <td class="left">
          	Local Pickup Fee
        </td>         
                      
        <td class="right"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($storelocations) { ?>
          <?php $class = 'odd'; ?>
          <?php foreach ($storelocations as $storelocation) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <tr class="<?php echo $class; ?>">
              
                <td align="center">
                  <?php if ($storelocation['delete']) { ?>
                    <input type="checkbox" name="delete[]" value="<?php echo $storelocation['id']; ?>" checked="checked" />
                  <?php } else { ?>
                    <input type="checkbox" name="delete[]" value="<?php echo $storelocation['id']; ?>" />
                  <?php } ?>
                </td>
                
                <td class="right">
                	<?php echo $storelocation['id']; ?>
                </td>                
                
                <td class="left">
                	<?php echo $storelocation['name']; ?>
                </td>

                <td class="left">
                	$<?php echo $storelocation['localpickup_fee']; ?>
                </td>                
                
                <td class="right"><?php foreach ($storelocation['action'] as $action) { ?>
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
