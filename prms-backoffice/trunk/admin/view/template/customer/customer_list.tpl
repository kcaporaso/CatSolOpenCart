<?php 
$this->load->model('user/membershiptier');
$can_access_sitefeature_Discounts = $this->model_user_membershiptier->user_can_access_sitefeature($this->user->getID(), 'DPC');
?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="location='<?php echo $insert; ?>'" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_insert; ?></span><span class="button_right"></span></a><a onclick="$('form').submit();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_delete; ?></span><span class="button_right"></span></a><a href="<?php echo $download_customers; ?>" class="button"><span class="button_left button_backup"></span><span class="button_middle"><?php echo $button_download; ?></span><span class="button_right"></span></a></div>
</div>
<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
  <table class="list">
    <thead>
      <tr>
        <td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'delete\']').attr('checked', this.checked);" /></td>
        <td class="right"><?php echo $column_action; ?></td>
        <td class="left"><?php if ($sort == 'name') { ?>
          <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'status') { ?>
          <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
          <?php } ?></td>
		
		<?php if ($can_access_sitefeature_Discounts && $this->user->isSPS()): ?>
		<td class="left"><?php if ($sort == 'discount_1') { ?>
          <a href="<?php echo $sort_discount_1; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_discount_1; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_discount_1; ?>"><?php echo $column_discount_1; ?></a>
          <?php } ?></td>
        <?php endif; ?>
		
		<td class="left"><?php if ($sort == 'date_added') { ?>
          <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
          <?php } ?></td>
      </tr>
    </thead>
    <tbody>
      <tr class="filter">
        <td></td>
        <td align="right"><a herf="javascript:;" title="<?php echo $button_filter; ?>" onclick="filter();" ><img src="view/image/icons/magnifier.png" border="0" /></a></td>
        <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
        <td><select name="filter_status">
            <option value="*"></option>
            <?php if ($filter_status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <?php } ?>
            <?php if (!is_null($filter_status) && !$filter_status) { ?>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select></td>
		<?php if ($can_access_sitefeature_Discounts && $this->user->isSPS()) { ?><td><input type="text" name="filter_discount_1" value="<?php echo $filter_discount_1; ?>" size="4" /></td><?php } ?>
        <td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" id="date" /></td>
      </tr>
      <?php if ($customers) { ?>
      <?php $class = 'odd'; ?>
      <?php foreach ($customers as $customer) { ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
      <tr class="<?php echo $class; ?>">
        <td style="align: center;"><?php if ($customer['delete']) { ?>
          <input type="checkbox" name="delete[]" value="<?php echo $customer['customer_id']; ?>" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="delete[]" value="<?php echo $customer['customer_id']; ?>" />
          <?php } ?></td>
        <td class="right"><?php foreach ($customer['action'] as $action) { ?>
          <a href="<?php echo $action['href']; ?>"><img src="view/image/icons/user_<?php echo strtolower($action['text']); ?>.png" title="<?php echo $action['text']; ?>" border="0" /></a>&nbsp;
          <?php } ?></td>
        <td class="left"><a href="mailto:<?php echo $customer['email']; ?>"><?php echo $customer['name']; ?></a></td>
        <td class="left"><?php echo $customer['status']; ?></td>
		<?php if ($can_access_sitefeature_Discounts && $this->user->isSPS()) { ?><td class="left"><?php echo $customer['discount_1']; ?></td><?php } ?>
        <td class="left"><?php echo $customer['date_added']; ?></td>
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
	url = 'index.php?route=customer/customer';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}	

	var filter_discount_1 = $('input[name=\'filter_discount_1\']').attr('value');
	
	if (filter_discount_1) {
		url += '&filter_discount_1=' + encodeURIComponent(filter_discount_1);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	location = url;
}
//--></script>
<link rel="stylesheet" type="text/css" href="view/stylesheet/datepicker.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
