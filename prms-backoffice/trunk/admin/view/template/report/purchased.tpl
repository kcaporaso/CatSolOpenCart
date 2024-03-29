<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
</div>
<table class="list">
  <thead>
    <tr>
      <td class="left"><?php echo $column_name; ?></td>
      <td class="left"><?php echo $column_model; ?></td>
      <td class="right"><?php echo $column_quantity; ?></td>
      <td class="right"><?php echo $column_total; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($products) { ?>
    <?php $class = 'odd'; ?>
    <?php foreach ($products as $product) { ?>
    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
    <tr class="<?php echo $class; ?>">
      <td class="left"><?php echo $product['name']; ?></td>
      <td class="left"><?php echo $product['model']; ?></td>
      <td class="right"><?php echo $product['quantity']; ?></td>
      <td class="right"><?php echo $product['total']; ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr class="even">
      <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<div class="pagination"><?php echo $pagination; ?></div>
