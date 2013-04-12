<div>
    <?php if ($products) { ?>
    <table cellpadding="2" cellspacing="0" style="width: 150px;">
      <?php foreach ($products as $product) { ?>
      <tr>
        <td valign="top" width="1"><a target="_parent" href="<?php echo $product['href']; ?>"><img src="<?php echo $product['image']; ?>" alt="<?php /*echo $product['name'];*/ ?>" /></a></td>
        <td>&nbsp;</td>
        <td width="100%" valign="top"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><br />
          <?php if (!$product['special']) { ?>
          <span style="font-size: 11px; color: #900;"><?php echo $product['price']; ?></span>
          <?php } else { ?>
          <span style="font-size: 11px; color: #900; text-decoration: line-through;"><?php echo $product['price']; ?></span> <span style="font-size: 11px; color: #F00;"><?php echo $product['special']; ?></span>
          <?php } ?></td>
      </tr>
      <?php } ?>
    </table>
    <?php } ?>
  </div>
