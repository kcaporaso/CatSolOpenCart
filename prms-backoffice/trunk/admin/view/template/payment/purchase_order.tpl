<?php echo (isset($header)) ? $header : '' ?>
<?php if (isset($error_warning)) { ?>
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
        <td width="25%"><?php echo $entry_order_status; ?></td>
        <td><select name="purchase_order_order_status_id">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $purchase_order_order_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_geo_zone; ?></td>
        <td><select name="purchase_order_geo_zone_id">
            <option value="0"><?php echo $text_all_zones; ?></option>
            <?php foreach ($geo_zones as $geo_zone) { ?>
            <?php if ($geo_zone['geo_zone_id'] == $purchase_order_geo_zone_id) { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_status; ?></td>
        <td><select name="purchase_order_status">
            <?php if ($purchase_order_status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <?php if (!$this->user->isSPS()) { ?>
      <?php if (file_exists(DIR_APPLICATION . 'model/customer/customer_group.php')) { ?>
      <tr>
        <td><?php echo $entry_customer_group; ?></td>
        <td>
          <table>
	        <tr>
	          <td>
	            <div class="scrollbox">
	              <?php $j=1; ?>
	              <?php foreach ($customer_groups as $k => $v) { ?>
	              <?php $name = $v['name']; ?>
	              <?php $id = $v['id']; ?>
	              <?php if($j != 1) {$j = 1;}else{$j = 0;} ?>
				  <?php if($j == 0) {$class = 'even';}elseif($j == 1){$class = 'odd';} ?>
	              <div class="<?php echo $class;?>">
      			    <input type="checkbox" name="purchase_order_customer_group_<?php echo $id; ?>" value="1"<?php echo (isset(${"purchase_order_customer_group_$id"})&& ${"purchase_order_customer_group_$id"}) ?' checked="checked"':'' ?> />
	                <?php echo $name; ?>
	              </div>
	              <?php } ?>
	            </div>
	          </td>
	        </tr>
	      </table>
        </td>
      </tr>
      <?php } 
         }
      ?>
      <tr>
        <td><?php echo $entry_sort_order; ?></td>
        <td><input type="text" name="purchase_order_sort_order" value="<?php echo $purchase_order_sort_order; ?>" size="1" /></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<?php echo (isset($footer)) ? $footer : '' ?>
