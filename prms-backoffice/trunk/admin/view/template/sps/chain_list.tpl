<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
  <h2><?php echo $heading_title; ?>'s:&nbsp;</h2>
    <form action="<?php echo $filter; ?>" method="post" id="filter_form">
    <h2>Filter by District:&nbsp;<select name="district_filter">
       <option value="all" <?php if ($district_filter == 'all') { echo 'selected="selected"'; }?>>All</option>
    <?php 
    foreach ($districts as $district) {  ?>
       <option value="<?php echo $district['id']?>" <?php if ($district_filter == $district['id']) { echo 'selected="selected"'; } ?>><?php echo $district['name'];?></option>
    <?php
    } 
    ?> 
    </select></h2>
    </form>
  <div class="buttons">
  <a onclick="$('#filter_form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo "Filter"; ?></span><span class="button_right"></span></a>
<a onclick="location='<?php echo $insert; ?>'" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_insert; ?></span><span class="button_right"></span></a><a onclick="$('form').submit();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_delete; ?></span><span class="button_right"></span></a></div>
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
          <?php } ?>
        </td>
        <td class="left"><?php if ($sort == 'school') { ?>
          <a href="<?php echo $sort_school; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_school; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_school; ?>"><?php echo $column_school; ?></a>
          <?php } ?>
        </td>
        <td class="left"><?php if ($sort == 'active') { ?>
          <a href="<?php echo $sort_active; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_active; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_active; ?>"><?php echo $column_active; ?></a>
          <?php } ?>
        </td>
        <td class="left"><?php echo $column_user_id_1; ?></a></td>
        <td class="left"><?php echo $column_user_id_2; ?></a></td>
        <?php /*  Grr I hate doing this, But cant get anyone to listen to reason.  "Removing" field.
		<td class="left"><?php if ($sort == 'create_date') { ?>
          <a href="<?php echo $sort_create_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_create_date; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_create_date; ?>"><?php echo $column_create_date; ?></a>
          <?php } ?></td>
        */ ?>
		<td class="left"><?php if ($sort == 'modified_date') { ?>
          <a href="<?php echo $sort_modified_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_modified_date; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_modified_date; ?>"><?php echo $column_modified_date; ?></a>
          <?php } ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($chains) { ?>
      <?php $class = 'odd'; ?>
      <?php foreach ($chains as $chain) { ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
      <tr class="<?php echo $class; ?>">
        <td style="align: center;"><?php if ($chain['delete']) { ?>
          <input type="checkbox" name="delete[]" value="<?php echo $chain['id']; ?>" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="delete[]" value="<?php echo $chain['id']; ?>" />
          <?php } ?></td>
        <td class="right"><?php foreach ($chain['action'] as $action) { ?>
          <a href="<?php echo $action['href']; ?>"><img src="view/image/icons/link_<?php echo strtolower($action['text']); ?>.png" title="<?php echo $action['text']; ?>" border="0" /></a>&nbsp;
          <?php } ?></td>
        <td class="left"><?php echo $chain['name']; ?></td>
        <td class="left"><?php echo $chain['school']; ?><!--div style="position:relative;float:right;"><a href="<?php echo $edit_school_url . '&school_id=' . $chain['school_id'] . '&cancel_page=chain'; ?> ">Edit School</a></div--></td>
        <td class="left"><?php echo $chain['active']; ?></td>
        <td class="left"><?php echo $chain['user_id_1']; ?></td>
        <td class="left"><?php echo $chain['user_id_2']; ?></td>
        <?php /* <td class="left"><?php echo $chain['create_date']; ?></td>  */ ?>
        <td class="left"><?php echo $chain['modified_date']; ?></td>
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
