<table class="list" cellspasing="0" cellpadding="0" width="100%" border="0" align="center">
    <?php for ($i = 0; $i < sizeof($products); $i = $i + 1) { ?>
    <tr>
      <?php for ($j = $i; $j < ($i + 1); $j++) { ?>
      <td align="center"><?php if (isset($products[$j])) { ?>
        <a target="_parent" href="<?php echo $products[$j]['href']; ?>"><img src="<?php echo $products[$j]['thumb']; ?>" title="<?php echo $products[$j]['name']; ?>" alt="<?php echo $products[$j]['name']; ?>" /></a><br/>


        <a target="_parent" href="<?php echo $products[$j]['href']; ?>"> <?php 
           if ($products[$j]['pvg_id']) {
             echo  substr($products[$j]['name'], 0, strrpos($products[$j]['name'],','));
           } else { 
             echo $products[$j]['name'];
           } 
          ?>
         </a><br />
        <?php if ($products[$j]['gradelevels_display']): ?>
        	<span style="color: #666; font-style:italic;"><?php echo $products[$j]['gradelevels_display']; ?></span><br />
        <?php endif; ?>
<?php /* ?>        
        <?php if ($products[$j]['ext_product_num']): ?>
        	<span style="color: #999; font-size: 11px;">Item # <?php echo $products[$j]['ext_product_num']; ?></span><br />
        <?php endif; ?>
<?php */ ?>        
        <?php if (!$products[$j]['special']) { ?>
        <?php   if (!$products[$j]['cat_discount_price']) { ?>
        	  <span style="color: #900; font-weight: bold;"><?php echo $products[$j]['price']; ?></span><br />
        <?php   } else { ?>
        	  <span style="color: #900; font-weight: bold; text-decoration: line-through;"><?php echo $products[$j]['price']; ?></span> <span style="color: #F00;"><?php echo $products[$j]['cat_discount_price']; ?></span>
        <?php   } ?>

        <?php } else { ?>
        	<span style="color: #900; font-weight: bold; text-decoration: line-through;"><?php echo $products[$j]['price']; ?></span> <span style="color: #F00;"><?php echo $products[$j]['special']; ?></span>
        <?php } ?>
        <?php if ($products[$j]['rating']) { ?>
        	<img src="catalog/view/theme/default/image/stars_<?php echo $products[$j]['rating'] . '.png'; ?>" alt="<?php echo $products[$j]['stars']; ?>" />
        <?php } ?>
        <?php if (!$products[$j]['pvg_id']) { ?>
   <br/>
        <form action="<?php echo $cartlink;?>" id="product_<?php echo $products[$j]['product_id'];?>" method="POST" enctype="multipart/form-data">
          <a onclick="$('#product_<?php echo $products[$j]['product_id']?>').submit();" id="add_to_cart"><img src="catalog/view/theme/default/image/BuyNow.png" border="0"/></a>
          <input type="hidden" name="quantity" value="1"/>
          <input type="hidden" name="product_id" value="<?php echo $products[$j]['product_id']; ?>"/>
        </form>
        <?php } /*pvg_id*/ ?>
        <?php } ?>
        </td>
      <?php } ?>
    </tr>
    <?php } ?>
  </table>
