<table class="list" id="products">
    <?php for ($i = 0; $i < sizeof($products); $i = $i + 4) { ?>
    <tr>
      <?php for ($j = $i; $j < ($i + 4); $j++) { ?>
      <td width="25%"><?php if (isset($products[$j])) { ?>
        <div class="prodthumb"><a href="<?php echo $products[$j]['href']; ?>"><img src="<?php echo $products[$j]['thumb']; ?>" title="<?php echo $products[$j]['name']; ?>" alt="<?php /* echo $products[$j]['name'];*/ ?>" /></a></div>


        <div class="name"><a href="<?php echo $products[$j]['href']; ?>"> <?php 
           if ($products[$j]['pvg_id']) {
             if (strstr($products[$j]['name'], ',')) {
                echo  substr($products[$j]['name'], 0, strrpos($products[$j]['name'],','));
             } else {
                echo $products[$j]['name'];
             }
           } else { 
             echo $products[$j]['name'];
           } 
          ?>
         </a></div>
        <?php if ($products[$j]['gradelevels_display']): ?>
        	<span style="color: #666; font-style:italic;"><?php echo $products[$j]['gradelevels_display']; ?></span><br />
        <?php endif; ?>
<?php /* ?>        
        <?php if ($products[$j]['ext_product_num']): ?>
        	<span style="color: #999; font-size: 11px;">Item # <?php echo $products[$j]['ext_product_num']; ?></span><br />
        <?php endif; ?>
<?php */ 
        if ($products[$j]['pvg_id']) { ?>
           <a href="<?php echo $products[$j]['href']?>" style="color:red;">More info...</a>
        <?php } else { ?>        
        <?php if (!$products[$j]['special']) { ?>
        	  <div class="price"><span><?php echo $products[$j]['price']; ?></span></div>
        <?php } else { ?>
				<div class="price"><del><?php echo $products[$j]['price']; ?></del> <span class="special"><?php echo $products[$j]['special']; ?></span></div>
        <?php } ?>
        <?php }  /*pvg_id*/ ?>
        <?php if ($products[$j]['rating']) { ?>
        	<img src="catalog/view/theme/default/image/stars_<?php echo $products[$j]['rating'] . '.png'; ?>" alt="<?php echo $products[$j]['stars']; ?>" />
        <?php } ?>

        <?php if (!$products[$j]['pvg_id']) { ?>
        <form action="<?php echo $cartlink;?>" id="product_<?php echo $products[$j]['product_id'];?>" method="POST" enctype="multipart/form-data">
          <br/><a onclick="$('#product_<?php echo $products[$j]['product_id']?>').submit();" id="add_to_cart"><img src="catalog/view/theme/default/image/BuyNow.png" border="0"/></a>
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
