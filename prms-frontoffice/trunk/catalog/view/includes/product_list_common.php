<div class="featured_grid">
	<?php foreach($products as $product):?>
	<div class="featured_item">
		<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a>
                <?php if ($product['pvg_id']): ?>
                       <a href="<?php echo $product['href']?>" class="more_info">More info...</a>
				<?php elseif($product['options']): ?>
                       <a href="<?php echo $product['href']?>" class="more_info">Select Options</a>
				<?php else: ?>
                        <form action="<?php echo $cartlink;?>" id="product_<?php echo $product['product_id'];?>" method="POST" enctype="multipart/form-data">
                                <a onclick="$('#product_<?php echo $product['product_id']?>').submit();" id="add_to_cart" class="add_to_cart">Add to Cart</a>
                                <input type="hidden" name="quantity" value="1"/>
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>"/>
                        </form>
                <?php endif; /*pvg_id*/ ?>

		<p class="product_name"><a href="<?php echo $product['href']; ?>"><?php
	           if ($product['pvg_id']) {
	             //if (strstr($product['name'], ',')) {
	             //   echo  substr($product['name'], 0, strrpos($product['name'],','));
                if (isset($product['pvg_name'])) {
                   echo $product['pvg_name'];
	             } else {
	                echo $product['name'];
	             }
	           } else {
	             echo $product['name'];
	           }
		?></a></p>
	        <?php if ($products[$j]['gradelevels_display']): ?>
	                <p class="product_grade"><?php echo $product['gradelevels_display']; ?></p>
	        <?php endif; ?>
	        
		<?php if (!$product['pvg_id']): ?>
			<?php if (!$product['special']): ?>
				<p class="product_price"><?php echo $product['price']; ?></p>
			<?php else: ?>
 				<p class="product_price"><del><?php echo $product['price']; ?></del> <span class="important"><?php echo $product['special']; ?></span></p>
			<?php endif; ?>
		<?php endif;  /*pvg_id*/ ?>
        	
		<?php if ($product['rating']): ?>
                	<img src="catalog/view/theme/default/image/stars_<?php echo $product['rating'] . '.png'; ?>" alt="<?php echo $product['stars']; ?>" class="product_rating" />
        	<?php endif; ?>
      <?php if($product['description']): ?>
            <p class="product_description"><?php echo $product['description']; ?></p>
      <?php endif; ?>
	</div>
	<?php endforeach; ?>
</div>
<script type="text/javascript">
   $(document).ready(function(){
     var maxHeight = 0;
       $('.featured_grid .featured_item').each(function() {
         maxHeight = Math.max(maxHeight, $(this).outerHeight());
       });
       $('.featured_grid .featured_item').css({ height: maxHeight + 'px' });
   });
</script>
