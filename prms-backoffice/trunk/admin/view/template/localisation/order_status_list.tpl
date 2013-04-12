<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="notify">You currently have read-only access to Order Statuses.</div>
<div class="notify">Customers will see only the Order Status phrase in square brackets.</div>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <?php /* ?><div class="buttons"><a onclick="location='<?php echo $insert; ?>'" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_insert; ?></span><span class="button_right"></span></a><a onclick="$('form').submit();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_delete; ?></span><span class="button_right"></span></a></div><?php */ ?>
</div>
<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
  <table class="list">
    <thead>
      <tr>
        <?php /* ?><td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'delete\']').attr('checked', this.checked);" /></td><?php */ ?>
        <td class="left"><?php if ($sort == 'name') { ?>
          <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
          <?php } ?></td>
        <?php /* ?><td class="right"><?php echo $column_action; ?></td><?php */ ?>
      </tr>
    </thead>
    <tbody>
      <?php if ($order_statuses) { ?>
      <?php $class = 'odd'; ?>
      <?php foreach ($order_statuses as $order_status) { ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
      <tr class="<?php echo $class; ?>">
        <?php /* ?><td style="align: center;"><?php if ($order_status['delete']) { ?>
          <input type="checkbox" name="delete[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="delete[]" value="<?php echo $order_status['order_status_id']; ?>" />
          <?php } ?></td><?php */ ?>
        <td class="left"><?php echo $order_status['name']; ?></td>
        <?php /* ?><td class="right"><?php foreach ($order_status['action'] as $action) { ?>
          [&nbsp;<a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>&nbsp;]
          <?php } ?></td><?php */ ?>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr class="even">
        <td class="center" colspan="3"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</form>
<div class="pagination"><?php echo $pagination; ?></div>
