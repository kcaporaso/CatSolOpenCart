<style>
	.audio_clip_span {
		margin-left: 40px;
		z-index: 99; 
		position: relative;
	}
</style>
<script type="text/javascript"  src="/catalog/view/javascript/thumbnailviewer2.js"  defer="defer" ></script>
<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <div style="width: 100%; margin-bottom: 30px;">
    <form id="authenticate" action="<?php echo $login; ?>" method="POST"><input type="hidden" name="redirect" id="redirect" value=""/></form>
    </form>
    <table style="width: 100%; border-collapse: collapse;">
      <tr>
        <td style="text-align: center; width: 250px; vertical-align: top;">
        	<div>
            	<a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="thickbox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php /*echo $heading_title;*/ ?>" id="image" style="margin-bottom: 3px;" /></a><br />
              	<span style="font-size: 11px;"><?php echo $text_enlarge; ?></span>
          	</div>
          	<div id="overlay_image" style="width: 250px; height: 0px; position:relative; top:-178px; left:0px; z-index:2; ">
          	</div>
        </td>
        <td style="padding-left: 15px; width: 296px; vertical-align: top;"><table width="100%">
<?php //echo 'pvgid: ' . $productvariantgroup_id; ?>
        	<?php if ($productvariantgroup_id == ''): ?>
                <tr>
                  <td><b><?php echo $text_price; ?></b><?php if ($price_beforetax_formatted_string || $special_beforetax_formatted_string): ?> <span style="color: #F00;">*</span><?php endif; ?></td>
                  <td><?php if (!$special) { 
                              if (!$have_category_discounts) { ?>
                      <?php       echo $price; ?>
                      <?php   } else { ?>
                                <!-- This is where we show category specific discounts per customer -->
                                <span style="text-decoration: line-through;"><?php echo $price; ?></span> <span style="color: #F00;"><?php echo $cat_discount_price; ?></span>
                      <?php   } ?>
                    <?php   } else { ?>
                    <span style="text-decoration: line-through;"><?php echo $price; ?></span> <span style="color: #F00;font-weight:bold;"><?php echo $special; ?></span>
                    <?php } ?></td>
                </tr>

                <?php
                if ($this->customer->isSPS() && $special) { 
                ?>
                   <tr>
                     <td><b style="color:#00f;font-size:90%;">You Save:</b></td>
                     <td style="color:#00f;font-size:90%;"><?php echo $savings; ?></td>
                   </tr>
                <?php 
                }
                ?>
                <?php if ($gradelevels_display): ?>
                    <tr>
                      <td><b>Grade Level:</b></td>
                      <td><?php echo $gradelevels_display; ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($this->config->get('config_stock_subtract')): ?>              
                    <!--tr>
                      <td><b><?php echo $text_availability; ?></b></td>
                      <td><?php echo $stock; ?></td>
                    </tr-->
                <?php endif; ?>
                <?php if ($ext_product_num): ?>
                <tr>
                  <td><b>Item Number:</b></td>
                  <td><?php echo $ext_product_num; ?></td>
                </tr>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($manufacturer) { ?>
            <tr>
              <td><b><?php echo $text_manufacturer; ?></b></td>
              <td><a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></td>
            </tr>
            <?php } ?>
            <?php if ($productvariantgroup_id != '' && !$gradelevels_are_different): ?>
            <tr>
              <td><b>Grade Level:</b></td>
              <td><?php echo $gradelevels_display; ?></td>
            </tr>
            <?php endif; ?>            
            <tr>
              <td><b><?php echo $text_average; ?></b></td>
              <td><?php if ($average) { ?>
                <img src="catalog/view/theme/default/image/stars_<?php echo $average . '.png'; ?>" alt="<?php echo $text_stars; ?>" style="margin-top: 2px;" />
                <?php } else { ?>
                <?php echo $text_no_rating; ?>
                <?php } ?></td>
            </tr>
            <?php if (!empty($pvg_name)) : ?>
            <tr>
              <td><b><?php echo $text_group; ?></b></td>
              <td><?php echo $pvg_name; ?></td>
            </tr>
            <?php endif; ?>
          </table>
          
          <?php if ($productvariantgroup_id == ''): ?>
          
          	  <br />
              <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="product">
              
                <?php if ($options) { ?>
                <b><?php echo $text_options; ?></b><br />
                <div style="background: #FBFAEA; border: 1px solid #EFEBAA; padding: 10px; margin-top: 3px; margin-bottom: 10px;">
                  <table style="width: 100%;">
                    <?php foreach ($options as $option) { ?>
                    <tr>
                      <td><?php echo $option['name']; ?>:</td>
                      <td><select name="option[<?php echo $option['option_id']; ?>]">
                          <?php foreach ($option['option_value'] as $option_value) { ?>
                          <option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?>
                          <?php if ($option_value['price']) { ?>
                          <?php echo $option_value['prefix']; ?><?php echo $option_value['price']; ?>
                          <?php } ?>
                          </option>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php } ?>
                  </table>
                </div>
                <?php } ?>                        
            
                <div class="product_addcart_container"><?php echo $text_qty; ?>
                  <input type="text" name="quantity" size="3" value="1" />
                  <a onclick="$('#product').submit();" id="add_to_cart" class="button-red"><span><?php echo 'Buy Now'?></span></a>
                  <?php if ($this->customer->isSPS()) { ?>
                  <a onclick="addToShoppingList(0);" id="add_to_shopping_list" class="button_addlist_shopping"><img border="0" src="catalog/view/theme/default/image/AddtoShoppingList.png" title="Add To Shopping List" /></a>
                  <div id="new_list_container" class="new_list_container">
                     Create new shopping list:<br/><input type="text" name="list_name" id="list_name" value="" style="200px;"/>
                     <a onclick="createNewList('<?php echo $product_id; ?>');" id="save_list" class="button-red" style="float:right;"><span>Create List</span></a>
                  </div>
                  <div id="existing_list_container" class="existing_list_container">
                     Add to an existing shopping list:<br/>
                     <select name="list_id" id="existing_list_select" style="width:150px;"></select>
                     <a onclick="updateExistingList('<?php echo $product_id; ?>');" id="update_list" class="button-red" style="float:right;"><span>Update List</span></a>
                  </div>
                  <?php } ?>
                  <?php if (!$this->customer->isSPS()) { ?>
                  <a onclick="addToWishList(0);" id="add_to_wish_list" class="button_addlist_wish"><img border="0" src="catalog/view/theme/default/image/AddtoWishList.png" title="Add To Wish List" /></a>
                  <div id="new_wish_list_container" class="new_list_container">
                     Create new wish list:<br/><input type="text" name="wish_list_name" id="wish_list_name" value="" style="200px;"/>
                     <a onclick="createNewWishList('<?php echo $product_id; ?>');" id="save_wish_list" class="button-red" style="float:right;"><span>Create List</span></a>
                  </div>
                  <div id="existing_wish_list_container" class="existing_list_container">
                     Add to an existing wish list:<br/>
                     <select name="wish_list_id" id="existing_wish_list_select" style="width:150px;"></select>
                     <a onclick="updateExistingWishList('<?php echo $product_id; ?>');" id="update_wish_list" class="button-red" style="float:right;"><span>Update List</span></a>
                  </div>
                  <?php } ?>

                </div>
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                            
              </form>
          
          <?php else: ?>
          <!-- PRODUCT VARIANTS LISTED HERE --> 
            <?php foreach ($product_variants as $product_variant): ?>
            
          	  <br />
              <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="product_<?php echo $product_variant['product_id'] ?>">
                <div class="product_addcart_container">

          <!-- Start Options -->
          <?php //KMC: SHOW THE OPTION FOR VARIANT GROUP PRODUCTS TOO!!! 
		  		// SJQ: Modified for seperate options per product in group.
		  ?>
          <?php if ($product_variant['options']) { ?>
          <b><?php echo $text_options; ?></b><br />
          <div style="background: #FBFAEA; border: 1px solid #EFEBAA; padding:0 5px; margin-top: 3px; margin-bottom: 5px;">
           <table style="width: 100%;">
         	 <?php foreach ($product_variant['options'] as $option) { ?>
         	 <tr>
         		<td><?php echo $option['name']; ?>:</td>
         		<td><select name="option[<?php echo $option['option_id']; ?>]" style="width:100%;">
         			 <?php foreach ($option['option_value'] as $option_value) { ?>
         			 <option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?>
         			 <?php if ($option_value['price']) { ?>
         			 <?php echo $option_value['prefix']; ?><?php echo $option_value['price']; ?>
         			 <?php } ?>
         			 </option>
         			 <?php } ?>
         		  </select></td>
         	 </tr>
         	 <?php } ?>
           </table>
           </div>
           <?php } ?>
           <!-- End Options -->

                
                	<table class="product_variant_table">
                		<?php if ($product_variant['product_variation'] != ''): ?>
                 		<tr>
                			<td>
                				<b><?php echo $product_variant['product_variation']; ?>:</b>
                			</td>
                			<td>	
                				<span class="product_variant_type"><?php echo $product_variant['product_variant']; ?></span>
                			</td>
                		</tr>
                		<?php endif; ?>
                		<?php if ($gradelevels_are_different): ?>	
                 		<tr>
                			<td>
                				<b>Grade Level:</b>
                			</td>
                			<td>	
                				<span class="product_variant_gradelevel"><?php echo $product_variant['gradelevels_display']; ?></span>
                			</td>
                		</tr>
                		<?php endif; ?>             	
                		<tr>
                			<td>
                				<b>Price:</b>
                			</td>
                			<td>
                				<?php if (!$product_variant['special']): ?>	
                           <?php  if (!$have_category_discounts):  ?>
                					<span class="product_variant_price"><?php echo $product_variant['price']; ?></span>
                      <?php   else:  ?>
                                <!-- This is where we show category specific discounts per customer -->
                                <del class="product_variant_price"><?php echo $price; ?></del> <span class="product_variant_price_discount"><?php echo $cat_discount_price; ?></span>
                      <?php   endif;  ?>

                				<?php else: ?>
                					<del class="product_variant_price"><?php echo $product_variant['price']; ?></del> <span class="product_variant_price_discount"><?php echo $product_variant['special']; ?></span>
                				<?php endif; ?>
                			</td>
                		</tr>

                     <?php if ($product_variant['savings']) { ?>
                     <tr>
                        <td><strong class="product_variant_savings_text">Your Savings:</strong></td>
                        <td><span class="product_variant_savings_price"><?php echo $product_variant['savings']; ?></span></td>
                     </tr>
                     <?php } ?>

                		<?php if ($this->config->get('config_stock_subtract')): ?>
                    		<!--tr>
                    			<td>
                    				<b>Availability:</b>
                    			</td>
                    			<td>
                    				<?php echo $product_variant['stock']; ?>
                    			</td>
                    		</tr-->
                		<?php endif; ?>
                		<tr>
                			<td>
                				<b>Item Number:</b>
                			</td>
                			<td>
                				<span class="product_variant_item_number"><?php echo $product_variant['ext_product_num']; ?></span>
                			</td>
                		</tr>
                		<tr>
                			<td align="center" colspan="9">
                           <?php echo $text_qty; ?>
                           <input type="text" name="quantity" size="3" value="1" />
                           <a onclick="$('#product_<?php echo $product_variant['product_id'] ?>').submit();" id="add_to_cart" class="button-red"><span><?php echo 'Buy Now'; //$button_add_to_cart; ?></span></a>
                             <?php if ($this->customer->isSPS()) { ?>
                              <a onclick="addToShoppingList(<?php echo $product_variant['product_id']; ?>);" id="add_to_shopping_list_<?php echo $product_variant['product_id']; ?>" class="button_addlist_shopping"><img border="0" src="catalog/view/theme/default/image/AddtoShoppingList.png" title="Add To Shopping List" /></a>
                              <div id="new_list_container_variant_<?php echo $product_variant['product_id'];?>" class="new_list_container">
                              Create new shopping list:<br/><input type="text" name="list_name" id="list_name" value="" style="200px;"/>
                              <a onclick="createNewList('<?php echo $product_variant['product_id']; ?>');" id="save_list" class="button-red" style="float:right;"><span>Create List</span></a>
                              </div>
                              <div id="existing_list_container_variant_<?php echo $product_variant['product_id'];?>" class="existing_list_container">
                              Add to an existing shopping list:<br/>
                              <select name="list_id" id="existing_list_select_variant_<?php echo $product_variant['product_id']; ?>" style="width:150px;"></select>
                              <a onclick="updateExistingListVariant('<?php echo $product_variant['product_id']; ?>');" id="update_list" class="button-red" style="float:right;"><span>Update List</span></a>
                              </div>
                            <?php } ?>
                            <?php if (!$this->customer->isSPS()) { ?>
                            <a onclick="addToWishList(<?php echo $product_variant['product_id']; ?>);" id="add_to_wish_list_<?php echo $product_variant['product_id']; ?>" class="button_addlist_wish"><img border="0" src="catalog/view/theme/default/image/AddtoWishList.png" title="Add To Wish List" /></a>
                              <div id="new_wish_list_container_variant_<?php echo $product_variant['product_id'];?>" class="existing_list_container">
                              Create new wish list:<br/><input type="text" name="wish_list_name" id="wish_list_name" value="" style="200px;"/>
                              <a onclick="createNewWishListVariant('<?php echo $product_variant['product_id']; ?>');" id="save_wish_list" class="button-red" style="float:right;"><span>Create List</span></a>
                              </div>
                              <div id="existing_wish_list_container_variant_<?php echo $product_variant['product_id'];?>" class="existing_list_container">
                              Add to an existing wish list:<br/>
                              <select name="wish_list_id" id="existing_wish_list_select_variant_<?php echo $product_variant['product_id']; ?>" style="width:150px;"></select>
                              <a onclick="updateExistingWishListVariant('<?php echo $product_variant['product_id']; ?>');" id="update_list" class="button-red" style="float:right;"><span>Update List</span></a>
                              </div>
                            <?php } ?>
                			</td>
                		</tr>                		
                	</table>

                </div>
                <input type="hidden" name="product_id" value="<?php echo $product_variant['product_id']; ?>" />
                            
              </form>      
    
            <?php endforeach; ?>                  
          
          <?php endif; ?>
          
          <?php if($config_share_facebook || $config_share_twitter): ?>
          	<div id="social">
            	<?php if($config_share_facebook): ?>
                	<div class="share"><a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script></div>
                <?php endif; ?>
            	<?php if($config_share_twitter): ?>
                	<div class="share"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
                <?php endif; ?>
            </div>
          <?php endif; ?>
          <?php if ($extra_shipping) { ?>
            <div id="extra_shipping"><img border="0" src="/catalog/view/common/AddFreight.png" title="This item requires additional freight charges." alt="This item requires additional freight charges."/></div>
          <?php } ?>
          
          <?php if ($productvariantgroup_id == '' && ($price_beforetax_formatted_string || $special_beforetax_formatted_string)): ?>
          <br>
          <table align="right">
			<tr>
				<td style="text-align: right !important;">
					<span style="color: #F00;">*</span> Price breakdown :<br>
					<?php $price_beforetax_formatted_string_opentag = ($special)? '<span style="text-decoration: line-through;">' : ''; ?>
					<?php $price_beforetax_formatted_string_closetag = ($special)? '</span>' : ''; ?>
                    <?php echo ($price_beforetax_formatted_string)? $price_beforetax_formatted_string_opentag.$price_beforetax_formatted_string.$price_beforetax_formatted_string_closetag : ''; ?>
                    <?php echo ($special_beforetax_formatted_string)? '<span style="color: #F00;">'.$special_beforetax_formatted_string.'</span>' : ''; ?>					
				</td>
			</tr>
          </table>
          <?php endif; ?>
          
          <br>
          <table align="left">
          	<?php if ($safetywarning_choking_flag): ?>
    			<tr>
    				<td>
    					<a href="/catalog/view/common/safetywarning_choking_large.gif"  rel="enlargeimage::mouseover" rev="overlay_image"><img src="/catalog/view/common/safetywarning_choking_small.gif" /></a>		
    				</td>
    			</tr>
			<?php endif; ?>
          	<?php if ($safetywarning_balloon_flag): ?>
    			<tr>
    				<td>
    					<a href="/catalog/view/common/safetywarning_balloon_large.gif"  rel="enlargeimage::mouseover" rev="overlay_image"><img src="/catalog/view/common/safetywarning_balloon_small.gif" /></a>		
    				</td>
    			</tr>
			<?php endif; ?>
          	<?php if ($safetywarning_marbles_flag): ?>
    			<tr>
    				<td>
    					<a href="/catalog/view/common/safetywarning_marbles_large.gif"  rel="enlargeimage::mouseover" rev="overlay_image"><img src="/catalog/view/common/safetywarning_marbles_small.gif" /></a>		
    				</td>
    			</tr>
			<?php endif; ?>
          	<?php if ($safetywarning_smallball_flag): ?>
    			<tr>
    				<td>
    					<a href="/catalog/view/common/safetywarning_smallball_large.gif"  rel="enlargeimage::mouseover" rev="overlay_image"><img src="/catalog/view/common/safetywarning_smallball_small.gif" /></a>	
    				</td>
    			</tr>
			<?php endif; ?>
          </table>          
          
        </td>
      </tr>
    </table>
  </div>
  <div class="tabs"><a tab="#tab_description"><?php echo $tab_description; ?></a><a tab="#tab_image"><?php echo $tab_image; ?></a><a tab="#tab_media"><?php echo $tab_media; ?></a><a tab="#tab_review"><?php echo $tab_review; ?></a><a tab="#tab_related"><?php echo $tab_related; ?></a></div>
  <div id="tab_description" class="page">
      <?php echo $description; ?>      
  </div>
  <div id="tab_review" class="page">
    <div id="review"></div>
    <div class="heading" id="review_title"><?php echo $text_write; ?></div>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><b><?php echo $entry_name; ?></b><br />
      <input type="text" name="name" value="" />
      <br />
      <br />
      <b><?php echo $entry_review; ?></b>
      <textarea name="text" style="width: 99%;" rows="8"></textarea>
      <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
      <br />
      <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
      <input type="radio" name="rating" value="1" style="margin: 0;" />
      &nbsp;
      <input type="radio" name="rating" value="2" style="margin: 0;" />
      &nbsp;
      <input type="radio" name="rating" value="3" style="margin: 0;" />
      &nbsp;
      <input type="radio" name="rating" value="4" style="margin: 0;" />
      &nbsp;
      <input type="radio" name="rating" value="5" style="margin: 0;" />
      &nbsp; <span><?php echo $entry_good; ?></span><br />
      <br />
      <b><?php echo $entry_captcha; ?></b><br />
      <input type="text" name="captcha" value="" />
      <br />
      <img src="index.php?route=product/product/captcha" id="captcha" /></div>
    <div class="buttons">
      <table>
        <tr>
          <td align="right"><a onclick="review();" class="button"><span><?php echo $button_continue; ?></span></a></td>
        </tr>
      </table>
    </div>
  </div>
  <div id="tab_image" class="page">
        <?php if ($images) { ?>
              <div style="display: inline-block;">
                    <?php foreach ($images as $image): ?>
                      <div style="display: inline-block; float: left; text-align: center; margin-left: 5px; margin-right: 5px; margin-bottom: 10px; width:150px; height:150px;">
                      	<a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="thickbox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" style="border: 1px solid #DDDDDD; margin-bottom: 3px;" /></a>
                      </div>
                    <?php endforeach; ?>
              </div>
        <?php } else { ?>
        	<div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><?php echo $text_no_images; ?></div>
        <?php } ?>    
    
  </div>
  <div id="tab_media" class="page">
  <?php if($product_medias): ?>
   <div style="display: inline-block;">
      <?php foreach ((array)$product_medias as $product_media): ?>
         <div style="display: inline-block; float: left; text-align: center; margin-left: 5px; margin-right: 5px; margin-bottom: 10px; width:150px; height:150px; border: 1px solid #DDDDDD ">
            <div style='display:block; margin:0 auto; margin-top: 26px; text-align:center;'>
               <?php echo $product_media; ?><br />
               <span style="font-size: 11px;">Click for Demo</span>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
  <?php else: ?>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><?php echo $text_no_media; ?></div>
  <?php endif; ?>
  </div>
  <div id="tab_related" class="page">
    <?php if ($products) { ?>
		<?php if (defined('BENDER')) { ?>
		<?php require_once DIR_FRONTOFFICE.'catalog/view/includes/product_list_common.php'; ?>
		<?php } else { ?>
		<?php require_once DIR_FRONTOFFICE.'catalog/view/includes/retail_product_list_common.php'; ?>
		<?php } ?>
    <?php } else { ?>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><?php echo $text_no_related; ?></div>
    <?php } ?>
  </div>
</div>
<div class="bottom">&nbsp;</div>
<script type="text/javascript"><!--

$('#new_list_container').hide();
$('#new_wish_list_container').hide();
$('#existing_list_container').hide();
$('#existing_wish_list_container').hide();
$('*[id^=new_list_container_variant]').hide();
$('*[id^=existing_list_container_variant]').hide();
$('*[id^=new_wish_list_container_variant]').hide();
$('*[id^=existing_wish_list_container_variant]').hide();

$('#review .pagination a').live('click', function() {
	$('#review').slideUp('slow');
		
	$('#review').load(this.href);
	
	$('#review').slideDown('slow');
	
	return false;
});			

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

function review() {
	$.ajax({
		type: 'post',
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#review_button').attr('disabled', 'disabled');
			$('#review_title').after('<div class="wait"><img src="catalog/view/theme/default/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#review_button').attr('disabled', '');
			$('.wait').remove();
		},
		success: function(data) {
			if (data.error) {
				$('#review_title').after('<div class="warning">' + data.error + '</div>');
			}
			
			if (data.success) {
				$('#review_title').after('<div class="success">' + data.success + '</div>');
								
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
}

function createNewList(product_id) {

   $.post(
		'<?php echo $create_shopping_list; ?>', 
		$('#product').serialize(), 
		function (result) {
//alert(result);
         //JSON.parse(result, function(key, value) {
         //   if (typeof value != "object") {
               //alert(key + ': ' + value);
         //      if (value == 'success') {
                  $('#existing_list_container').slideUp(100);
                  $('#new_list_container').html('<strong>Shopping List Created!</strong>');
                  $('#new_list_container').show('fast');
         //      }
         //   }
         //});
    	}
   );
}

function createNewWishListVariant(product_id) {

   $.post(
		'<?php echo $create_wish_list; ?>', 
		$('#product_'+product_id).serialize(), 
		function (result) {
//alert(result);
         //JSON.parse(result, function(key, value) {
         //   if (typeof value != "object") {
               //alert(key + ': ' + value);
         //      if (value == 'success') {
                  $('#existing_wish_list_container_variant_'+product_id).slideUp(100);
                  $('#new_wish_list_container_variant_'+product_id).html('<strong>Wish List Created!</strong>');
                  $('#new_wish_list_container_variant_'+product_id).show('fast');
         //      }
         //   }
         //});
    	}
   );
}
function createNewWishList(product_id) {

   $.post(
		'<?php echo $create_wish_list; ?>', 
		$('#product').serialize(), 
		function (result) {
//alert(result);
         //JSON.parse(result, function(key, value) {
         //   if (typeof value != "object") {
               //alert(key + ': ' + value);
         //      if (value == 'success') {
                  $('#existing_wish_list_container').slideUp(100);
                  $('#new_wish_list_container').html('<strong>Wish List Created!</strong>');
                  $('#new_wish_list_container').show('fast');
         //      }
         //   }
         //});
    	}
   );
}

function updateExistingListVariant(productid) {

//   alert(productid);
//   alert($('#product_'+productid).serialize());
   $.post(
      '<?php echo $update_shopping_list; ?>', 
   	$('#product_'+productid).serialize(), 
   	function (result) {
   //alert(result);
         //JSON.parse(result, function(key, value) {
         //   if (typeof value != "object") {
               //alert(key + ': ' + value);
         //      if (value == 'success') {
                  $('#new_list_container_variant_'+productid).slideUp(100);
                  $('#existing_list_container_variant_'+productid).html('<strong>Shopping List Updated!</strong>');
         //      }
         //   }
         //});
       }
   );

}

function updateExistingList(productid) {

   $.post(
      '<?php echo $update_shopping_list; ?>', 
   	$('#product').serialize(), 
   	function (result) {
   //alert(result);
      //JSON.parse(result, function(key, value) {
      //   if (typeof value != "object") {
            //alert(key + ': ' + value);
      //      if (value == 'success') {
               $('#new_list_container').slideUp(100);
               $('#existing_list_container').html('<strong>Shopping List Updated!</strong>');
      //     }
      //  }
      //});
      }
   );
}

function updateExistingWishListVariant(productid) {
   $.post(
      '<?php echo $update_wish_list; ?>', 
   	$('#product_'+productid).serialize(), 
   	function (result) {
   //alert(result);
      //JSON.parse(result, function(key, value) {
      //   if (typeof value != "object") {
            //alert(key + ': ' + value);
      //      if (value == 'success') {
               $('#new_wish_list_container_variant_'+productid).slideUp(100);
               $('#existing_wish_list_container_variant_'+productid).html('<strong>Wish List Updated!</strong>');
      //      }
      //   }
      //});
      }
   );
}

function updateExistingWishList(productid) {
   $.post(
      '<?php echo $update_wish_list; ?>', 
   	$('#product').serialize(), 
   	function (result) {
   //alert(result);
      //JSON.parse(result, function(key, value) {
      //   if (typeof value != "object") {
            //alert(key + ': ' + value);
      //      if (value == 'success') {
               $('#new_wish_list_container').slideUp(100);
               $('#existing_wish_list_container').html('<strong>Wish List Updated!</strong>');
      //      }
      //   }
      //});
      }
   );
}

function buildNewListArea(productid) {
   if (productid == 0) {
      $('#new_list_container').show('fast');
      $('#add_to_shopping_list').hide();
   } else {
      $('#new_list_container_variant_'+productid).show('fast');
      $('#add_to_shopping_list_variant_'+productid).hide();
   }
}

function redirectToAuthenticate(productid) {
   url = '<?php echo $uri; ?>';
   //alert(url);
   $('#redirect').val(url);
   $('#authenticate').submit();
}

function addToShoppingList(productid) {
//alert(productid);
   var html = '';
   var logged = '<?php echo $is_logged; ?>';
   if (logged.length == 0) { redirectToAuthenticate(productid); }
   $.post(
		'<?php echo $get_shopping_lists; ?>', 
		$('#product').serialize(), 
		function (result) {
//alert(result);
         listobj = eval( '(' + result + ')');
         for (i=0; i<listobj.length; i++) {
            html += '<option value="' + listobj[i].title + '">' + listobj[i].text + '</option>';
         }
         //JSON.parse(result, function(key, value) {
         //   if (key) {
         //      if (typeof value != "object") {
                  //listObj[key] = value;
                  //alert(listObj[key] + ' : ' + value);
         //         html += '<option value="' + key + '">' + value + '</option>';
         //      }
         //   } else {
         //   } 
         //});
         buildNewListArea(productid);
         if (html.length > 0) {
            if (productid == 0) {
               $('#existing_list_select').html(html);
               $('#existing_list_container').show('fast');
            } else {
               $('#existing_list_select_variant_'+productid).html(html);
               $('#existing_list_container_variant_'+productid).slideDown('fast');
               $('#add_to_shopping_list_'+productid).slideUp('fast');
            }
         }
    	}
   );
}

function buildNewWishListArea(productid) {
   if (productid == 0) {
      $('#new_wish_list_container').show('fast');
      $('#add_to_wish_list').hide();
   } else {
      $('#new_wish_list_container_variant_'+productid).show('fast');
      $('#add_to_wish_list_variant_'+productid).hide();
   }
}

function addToWishList(productid) {
//alert(productid);
   var html = '';
   var listObj = new Array();
   var logged = '<?php echo $is_logged; ?>';
   if (logged.length == 0) { redirectToAuthenticate(productid); }
   $.post(
		'<?php echo $get_wish_lists; ?>', 
		$('#product').serialize(), 
		function (result) {
      listobj = eval( '(' + result + ')');
      for (i=0; i<listobj.length; i++) {
         html += '<option value="' + listobj[i].title + '">' + listobj[i].text + '</option>';
      }
         /*JSON.parse(result, function(key, value) {
            if (key) {
               if (typeof value != "object") {
                  //listObj[key] = value;
                  //alert(listObj[key] + ' : ' + value);
                  html += '<option value="' + key + '">' + value + '</option>';
               }
            } else {
              buildNewWishListArea(productid);
            } 
         });
*/
         buildNewWishListArea(productid);
         if (html.length > 0) {
            if (productid == 0) {
               $('#existing_wish_list_select').html(html);
               $('#existing_wish_list_container').show('fast');
               $('#add_to_wish_list').slideUp('fast');
            } else {
               $('#existing_wish_list_select_variant_'+productid).html(html);
               $('#existing_wish_list_container_variant_'+productid).slideDown('fast');
               $('#add_to_wish_list_'+productid).slideUp('fast');
            }
         }

         /*
         if (lists.length == 0) {
            //alert('no lists found');
            // spit out an input field w/ button for saving new list.
         } else {
            buildExistingListArea(lists);
         }
         */
    	}
   );
}

//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<script type="text/javascript" src="/catalog/view/javascript/audio_player.js"></script>
<script type="text/javascript" src="/catalog/view/javascript/video_player.js"></script>
<script type="text/javascript"><!--
	load_video_player('<?php echo HTTP_IMAGE ?>');
//--></script>
