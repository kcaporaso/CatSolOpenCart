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
        <td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'delete\']').attr('checked', this.checked);" /></td>
        <td class="left"><?php echo $column_action; ?></td>
        <td class="left"><?php if ($sort == 'name') { ?>
          <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
          <?php } ?>
        </td>
        <td class="left"><?php if ($sort == 'active') { ?>
          <a href="<?php echo $sort_active; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_active; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_active; ?>"><?php echo $column_active; ?></a>
          <?php } ?>
        </td>
        <!--td class="left"><?php if ($sort == 'free_shipping') { ?>
          <a href="<?php echo $sort_free_shipping; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_free_shipping; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_free_shipping; ?>"><?php echo $column_free_shipping; ?></a>
          <?php } ?>
        </td>
        <td class="left"><?php if ($sort == 'free_freight_over') { ?>
          <a href="<?php echo $sort_free_freight_over; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_free_freight_over; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_free_freight_over; ?>"><?php echo $column_free_freight_over; ?></a>
          <?php } ?></td-->
        <td class="left"><?php if ($sort == 'create_date') { ?>
          <a href="<?php echo $sort_create_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_create_date; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_create_date; ?>"><?php echo $column_create_date; ?></a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'modified_date') { ?>
          <a href="<?php echo $sort_modified_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_modified_date; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_modified_date; ?>"><?php echo $column_modified_date; ?></a>
          <?php } ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($districts) { ?>
      <?php $class = 'odd'; ?>
      <?php foreach ($districts as $district) { ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
      <tr class="<?php echo $class; ?>">
        <td style="align: center;"><?php if ($district['delete']) { ?>
          <input type="checkbox" name="delete[]" value="<?php echo $district['id']; ?>" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="delete[]" value="<?php echo $district['id']; ?>" />
          <?php } ?></td>
        <td class="left"><?php foreach ($district['action'] as $action) { ?>
          <a href="<?php echo $action['href']; ?>"><img src="view/image/icons/world_<?php echo strtolower($action['text']); ?>.png" title="<?php echo $action['text']; ?>" border="0" /></a>&nbsp;
          <?php } ?></td>
        <td class="left"><?php echo $district['name']; ?></td>
        <td class="left"><?php if ($district['active']) { echo "Active"; } else { echo "InActive"; } ?></td>
        <!--td class="left"><?php echo $district['free_shipping']; ?></td>
        <td class="left"><?php echo $district['free_freight_over']; ?></td-->
        <td class="left"><?php echo $district['create_date']; ?></td>
        <td class="left"><?php echo $district['modified_date']; ?></td>
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
