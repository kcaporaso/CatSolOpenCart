<?php 
    if (!$_SESSION['user_is_admin']) {
        $css_display_none_if_not_admin = "display: none";
    }
?>
<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($notify): ?>
	<div class="notify"><?php echo $notify; ?></div>
<?php endif; ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <?php /*?><div class="buttons"><a onclick="$('form').submit();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_delete; ?></span><span class="button_right"></span></a></div><?*/?>
  <div class="buttons"><a onclick="location.href='<?php echo $add_action; ?>'" class="button"><span class="button_left button_insert"></span><span class="button_middle">Insert</span><span class="button_right"></span></a></div>
  <!--div class="buttons"><a onclick="location.href='<?php echo $update_all_action; ?>'" class="button"><span class="button_left button_restore"></span><span class="button_middle">Update All <span style="color:red;">(CAUTION)</span></span><span class="button_right"></span></a></div-->
</div>
<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
  <table class="list">
    <thead>
      <tr>
        <td width="1" style="align: center;"><?php /*?><input type="checkbox" onclick="$('input[name*=\'delete\']').attr('checked', this.checked);" /><?*/?></td>
        <td class="right"><?php if ($sort == 'store_id') { ?>
          <a href="<?php echo $sort_store; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_store; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_store; ?>"><?php echo $column_store; ?></a>
          <?php } ?></td>

        <td style="<?php echo $css_display_none_if_not_admin ?>" class="left"><?php if ($sort == 'user_id') { ?>
          <a href="<?php echo $sort_user; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_user; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_user; ?>"><?php echo $column_user; ?></a>
          <?php } ?></td>

        <td class="left"><?php if ($sort == 'code') { ?>
          <a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?>&nbsp;[Catalogs][Linked]</a>
          <?php } else { ?>
          <a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?>&nbsp;[Catalogs][Linked]</a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'name') { ?>
          <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
          <?php } ?></td>
        <td>
        	Storefront URL
        </td>
        <td class="right"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr class="filter">
        <td></td>
        <td align="right"><input type="text" name="filter_store_id" value="<?php echo $filter_store_id; ?>" size="4" style="text-align: right;" /></td>

        <td style="<?php echo $css_display_none_if_not_admin ?>"><select name="filter_user_id">
            <option value="*"></option>
            <?php /* ?>
            <?php if ($filter_user_id == '0') { ?>
            	<option value="0" selected="selected"><?php echo $text_no_users; ?></option>
            <?php } else { ?>
            	<option value="0"><?php echo $text_no_users; ?></option>
            <?php } ?>
            <? */ ?>
            <?php foreach ($users_with_stores as $user) : ?>
                <?php if ($user['user_id'] == $filter_user_id) { ?>
                	<option value="<?php echo $user['user_id']; ?>" selected="selected"><?php echo $user['name']; ?></option>
                <?php } else { ?>
                	<option value="<?php echo $user['user_id']; ?>"><?php echo $user['name']; ?></option>
                <?php } ?>
            <?php endforeach ?>
          </select>
        </td>

        <td><input type="text" name="filter_code" value="<?php echo $filter_code; ?>" /></td>
        <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
        <td></td>
        <td align="right"><input type="button" value="<?php echo $button_filter; ?>" onclick="filter();" /></td>
      </tr>
      <?php if ($stores) { ?>
          <?php $class = 'odd'; ?>
          <?php foreach ($stores as $store) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <tr class="<?php echo $class; ?>">
                <td style="align: center;"></td>
                <td class="right"><?php if ($updatedid == $store['store_id']) { echo '<strong>&gt;&gt;</strong>&nbsp;'; } ?><a name="<?php echo $store['store_id']; ?>"></a><?php echo $store['store_id']; ?></td>

                <td style="<?php echo $css_display_none_if_not_admin ?>" class="left"><?php echo $store['user_name']; ?></td>

                <td class="left"><?php echo $store['code']; ?><div style="float:right">[ <?php echo $store['productsets']; ?>][<?php if ($store['catcount']) { echo 'Y'; } else { echo '<strong>N</strong>'; } ;?>]</div></td>
                <td class="left"><?php echo $store['name']; ?></td>
                <td class="left"><a target="_blank" href="http://<?php echo $store['storefront_url'];?>"><?php echo $store['storefront_url']; ?></a></td>
                <td class="right"><?php foreach ($store['action'] as $action) { ?>
                  [&nbsp;<a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>&nbsp;]
                  <?php } ?></td>
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
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=user/store';
	
	var filter_store_id = $('input[name=\'filter_store_id\']').attr('value');
	
	if (filter_store_id) {
		url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
	}

	var filter_user_id = $('select[name=\'filter_user_id\']').attr('value');
	
	if (filter_user_id != '*') {
		url += '&filter_user_id=' + encodeURIComponent(filter_user_id);
	}	

	var filter_code = $('input[name=\'filter_code\']').attr('value');
	
	if (filter_code) {
		url += '&filter_code=' + encodeURIComponent(filter_code);
	}	
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}	

	location = url;
}
//--></script>
