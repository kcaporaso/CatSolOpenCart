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
  <?php if ($user_can_access_sitefeature): ?><div class="buttons"><a onclick="location='<?php echo $insert; ?>'" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_insert; ?></span><span class="button_right"></span></a><?php /* ?><a onclick="$('form').submit();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_delete; ?></span><span class="button_right"></span></a><? */ ?></div><?php endif; ?>
</div>
<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
  <table class="list">
    <thead>
      <tr>
        <?php /* ?><td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'delete\']').attr('checked', this.checked);" /></td><? */ ?>
        <td class="right"><?php if ($sort == 'P.productset_id') { ?>
          	<a href="<?php echo $sort_productset; ?>" class="<?php echo strtolower($order); ?>">ID</a>
          <?php } else { ?>
          	<a href="<?php echo $sort_productset; ?>">ID</a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'user_name') { ?>
          	<a href="<?php echo $sort_user; ?>" class="<?php echo strtolower($order); ?>">User (Owner)</a>
          <?php } else { ?>
          	<a href="<?php echo $sort_user; ?>">User (Owner)</a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'P.code') { ?>
          	<a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>">Code</a>
          <?php } else { ?>
          	<a href="<?php echo $sort_code; ?>">Code</a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'P.name') { ?>
          	<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Name</a>
          <?php } else { ?>
          	<a href="<?php echo $sort_name; ?>">Name</a>
          <?php } ?></td>
        <td class="right"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr class="filter">
        <?php /* ?><td></td><? */ ?>
        <td align="right"><input type="text" name="filter_productset_id" value="<?php echo $filter_productset_id; ?>" size="4" style="text-align: right;" /></td>

        <td><select name="filter_user_id">
            <option value="*"></option>
            <?php foreach ($users_with_productsets as $user) : ?>
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
        <td align="right"><input type="button" value="Filter" onclick="filter();" /></td>
      </tr>    
      <?php if ($productsets) { ?>
          <?php $class = 'odd'; ?>
          <?php foreach ($productsets as $productset) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <tr class="<?php echo $class; ?>">
                <?php /* ?>
                <td style="align: center;">
                  <?php if ($productset['delete']) { ?>
                  	<input type="checkbox" name="delete[]" value="<?php echo $productset['productset_id']; ?>" checked="checked" />
                  <?php } else { ?>
                  	<input type="checkbox" name="delete[]" value="<?php echo $productset['productset_id']; ?>" />
                  <?php } ?>
                </td>
                <? */ ?>
                <td class="right"><?php echo $productset['productset_id']; ?></td>
                <td class="left"><?php echo $productset['user_name']; ?></td>
                <td class="left"><?php echo $productset['code']; ?></td>
                <td class="left"><?php echo $productset['name']; ?></td>
                <td class="right">
                	<?php
                        if (!$user_can_access_sitefeature) {
                            $productset['access_type_code'] = 'R';
                        }
                	?>                	
					[&nbsp;<a href="<?php echo $productset['action'][$productset['access_type_code']]['href']; ?>"><?php echo $productset['action'][$productset['access_type_code']]['text']; ?></a>&nbsp;]
					[&nbsp;<a href="<?php echo $productset['action']['products']['href']; ?>" target="_productlistforproductset_<?php echo $productset['code']; ?>"><?php echo $productset['action']['products']['text']; ?></a>&nbsp;]
                </td>
              </tr>
          <?php } ?>
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
function filter() {
	url = 'index.php?route=user/productset';
	
	var filter_productset_id = $('input[name=\'filter_productset_id\']').attr('value');
	
	if (filter_productset_id) {
		url += '&filter_productset_id=' + encodeURIComponent(filter_productset_id);
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