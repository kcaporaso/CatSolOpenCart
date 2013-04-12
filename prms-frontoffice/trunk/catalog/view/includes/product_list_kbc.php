      

       <?php if (isset($products)) { 
       $end = end($products);
       ?>
       
       <?php foreach($products as $product) { ?>
       <?php if ($product == $end) { ?> 
        <div id="featured_right"> 
       <?php } else { ?>
        <div id="featured_left">
       <?php } ?>
        <div style="text-align:center;"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>


        <div class="featured_title" style="text-align:center"><a href="<?php echo $product['href']; ?>"><?php 
           if ($product['pvg_id']) {
             echo  substr($product['name'], 0, strrpos($product['name'],','));
           } else {  
             echo $product['name'];
           } 
          ?></a></div>
        <?php if ($product['gradelevels_display']): ?>
         <div class="featured_descript"><span style="color: #666; font-style:italic;"><?php echo $product['gradelevels_display']; ?></span></div>
        <?php endif; ?>

        <?php if (!$product['special']) { ?>
        <?php   if (!$product['cat_discount_price']) { ?>
           <div class="price" style="text-align:center;"><span style="color: #900; font-weight: bold;"><?php echo $product['price']; ?></span></div>
        <?php   } else { ?>
           <div class="price" style="text-align:center;"><span style="color: #900; font-weight: bold; text-decoration: line-through;"><?php echo $product['price']; ?></span> <span style="color: #F00;"><?php echo $product['cat_discount_price']; ?></span></div>
        <?php   } ?>

        <?php } else { ?>
         <div class="price" style="text-align:center;"><span style="color: #900; font-weight: bold; text-decoration: line-through;"><?php echo $product['price']; ?></span> <span style="color: #F00;"><?php echo $product['special']; ?></span></div>
        <?php } ?>
        <?php if ($product['pvg_id']) { ?>
        <form action="<?php echo $cartlink;?>" id="product_<?php echo $product['product_id'];?>" method="POST" enctype="multipart/form-data">
          <div class="buy_now_btn" style="text-align:center;"><a onclick="$('#product_<?php echo $product['product_id']?>').submit();" id="add_to_cart"><img src="catalog/view/theme/default/image/BuyNow.png" border="0"/></a></div>
          <input type="hidden" name="quantity" value="1"/>
          <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>"/>
        </form> 
        </div>
        <?php } /*pvg_id*/ ?>
        <?php } ?>
        <?php } ?>

   <!--div><img src="images/featured_1.jpg" width="145" height="77" alt="Featured item" /></div>
   <div class="featured_title">Stacking Shape Pegboards</div>
   <div class="featured_descrip">Teach geometric shapes and reinforce fine motor skills. Five shapes (sqaure, circle,...</div>
   <div class="price"><span>$15.99</span> $12.99 Save $3.00</div>
   <div class="buy_now_btn"><img src="images/buy_now_btn.gif" width="84" height="17" alt="Buy now" /></div>
   </div>  
                       
   <div id="featured_left">
   <div><img src="images/featured_1.jpg" width="145" height="77" alt="Featured item" /></div>
   <div class="featured_title">Stacking Shape Pegboards</div>
   <div class="featured_descrip">Teach geometric shapes and reinforce fine motor skills. Five shapes (sqaure, circle,...</div>
   <div class="price"><span>$15.99</span> $12.99 Save $3.00</div>
   <div class="buy_now_btn"><img src="images/buy_now_btn.gif" width="84" height="17" alt="Buy now" /></div>
   </div>  
                       
   <div id="featured_right">
   <div><img src="images/featured_2.jpg" width="152" height="77" alt="Featured Item" /></div>
   <div class="featured_title">Ladybugs Bulletin Board Set</div>
   <div class="featured_descrip">Includes a big ladybug (approximately 23 1/2&quot; x 16 1/4&quot; assembled). a big...</div>
   <div class="price"><span>$15.99</span> $12.99 Save $3.00</div>
   <div class="buy_now_btn"><img src="images/buy_now_btn.gif" width="84" height="17" alt="Buy now" /></div-->


