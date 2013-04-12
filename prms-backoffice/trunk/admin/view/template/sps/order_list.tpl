<?php 

    if ($_SESSION['tried_adding_nonstandard_products_but_disallowed']) {
        
        echo '<script type="text/javascript">alert("Unfortunately, some Products you added are not in the system, and have been discarded.");</script>';
        
        unset($_SESSION['tried_adding_nonstandard_products_but_disallowed']);
        
    }
    
?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div id="month_picker"><?php echo $text_now_showing; ?></div>
<form action="" method="post" enctype="multipart/form-data" id="form">
  <table class="list">
    <thead>
      <tr>
        <td class="left"><?php echo $column_action; ?></td>
        <td class="right"><?php if ($sort == 'o.order_id') { ?>
          <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_order; ?>"><?php echo $column_order; ?></a>
          <?php } ?></td>
        <td class="left" width="140"><?php if ($sort == 'name') { ?>
          <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
          <?php } ?></td>
        <td class="left" width="180">District</td>
        <td class="left" width="180">School</td>
        <td class="left"><?php if ($sort == 'status') { ?>
          <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'o.date_added') { ?>
          <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
          <?php } ?></td>
        <td class="right"><?php if ($sort == 'o.total') { ?>
          <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
          <?php } ?></td>
      </tr>
    </thead>
    <tbody>
      <tr class="filter">
        <!--td></td-->
        <td align="right"><a href="javascript:;" id="filter_button" title="<?php echo "Search" ?>" onclick="filter();" ><img src="view/image/icons/magnifier.png" border="0"  /></a></td>
        <td align="right"><input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="10" style="text-align: right;" /></td>
        <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" size="12" /></td>
        <td><input type="text" name="filter_district_name" value="<?php echo $filter_district_name; ?>" size="10" /></td>
        <td><input type="text" name="filter_school_name" value="<?php echo $filter_school_name; ?>" size="10" /></td>
        <td><select name="filter_order_status_id">
            <option value="*"></option>
            <?php if ($filter_order_status_id == '0') { ?>
            <option value="0" selected="selected"><?php echo $text_no_status; ?></option>
            <?php } else { ?>
            <option value="0"><?php echo $text_no_status; ?></option>
            <?php } ?>
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
        <td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="9" id="date" /></td>
        <td align="right"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" size="6" style="text-align: right;" /></td>
      </tr>
      <?php if ($orders) { ?>
      <?php $class = 'odd'; ?>
      <?php foreach ($orders as $order) { ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
      <tr class="<?php echo $class; ?>">
        <td class="right"><?php foreach ($order['action'] as $action) { ?>
          <a href="<?php echo $action['href']; ?>"><img src="view/image/icons/page_<?php echo strtolower($action['text']); ?>.png" title="<?php echo $action['text']; ?>" border="0" /></a>
          <?php } ?></td>
        <td class="right"><?php echo $order['order_id']; ?></td>
        <td class="left"><a href="mailto:<?php echo $order['email']; ?>"><?php echo $order['name']; ?></a></td>
        <td class="left"><?php echo $order['districtname']; ?></td>
        <td class="left"><?php echo $order['schoolname']; ?></td>
        <td class="left">
           <?php echo $order['status']; ?>
           <?php 
           if (!empty($order['waiting_on_user'])) {
           ?>
              <br/><strong>Waiting on:</strong> <?php echo $order['waiting_on_user']; ?>
           <?php
           }
           ?>
        </td>
        <td class="left"><?php echo $order['date_added']; ?></td>
        <td class="right"><?php echo $order['total']; ?></td>
      </tr>
      <?php } ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
	  <tr class="<?php echo $class; ?>">
	  	<td colspan="5"></td>
	  	<td class="right"><strong>Orders Shown:</strong> <?php echo $text_total_results; ?></td>
		<td class="right"><strong>Total:</strong></td>
	  	<td class="right"><?php echo $text_grand_total; ?></td>
	  </tr>
      <?php } else { ?>
      <tr class="even">
        <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</form>
<div class="pagination"><?php echo $pagination; ?></div>
<script type="text/javascript"><!--
function filter(data) {
	url = 'index.php?route=sps/order';
	
	var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_school_name = $('input[name=\'filter_school_name\']').attr('value');
	if (filter_school_name) {
		url += '&filter_school_name=' + encodeURIComponent(filter_school_name);
	}

	var filter_district_name = $('input[name=\'filter_district_name\']').attr('value');
	if (filter_district_name) {
		url += '&filter_district_name=' + encodeURIComponent(filter_district_name);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
	
	if (filter_order_status_id != '*') {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	

	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	var filter_total = $('input[name=\'filter_total\']').attr('value');

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}	
		
	if(data) {
		url += '&month=' + encodeURIComponent(data['month']);
		url += '&year=' +  encodeURIComponent(data['year']);
	} else {
		url += '&month=' + encodeURIComponent('<?php echo date('m',$date_filter); ?>');
		url += '&year=' +  encodeURIComponent('<?php echo date('Y',$date_filter); ?>');
	}

	location = url;
}
//--></script>
<link rel="stylesheet" type="text/css" href="view/stylesheet/datepicker.css" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/monthpicker.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/monthpicker/monthpicker.min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'mm/dd/yy'});

   $(':input').keyup(function(e) {
      if (e.keyCode == 13) {
         filter();
      }
   });

/*   $('input:select').keyup(function(e) {
      if (e.keyCode == 13) {
         filter();
      }
   });
   */
	$('#month_picker').addClass('MonthPicker').monthpicker({
		elements: [
			{tpl:"year",opt:{
	
				range: "2010~<?php echo date('Y'); ?>",
				value: <?php echo date('Y',$date_filter); ?>
			}},
			{tpl:"month",opt:{
	
				value: <?php echo date('m',$date_filter); ?>
			}}
	
		],
		onChanged: filter
	});

});
//--></script>
