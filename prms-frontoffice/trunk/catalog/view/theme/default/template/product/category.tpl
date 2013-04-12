
<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<?php if (!empty($categories)): ?>
<div class="subcategory_list">
   <ul>
   <?php foreach($categories as $category): ?>
      <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
   <?php endforeach; ?>
   </ul>
</div>
<?php endif; ?>
<div class="middle">
  <?php if ($description) { ?>
  <div style="margin-bottom: 15px;"><?php echo $description; ?></div>
  <?php } ?>
  <?php if (!empty($categories)) { 
     $insertTR = false;
     $insertEndTR = false;
  ?>

  <table class="list" style="padding-bottom:0px;display:none;">
    		<?php foreach ($categories as $category_index => $category): ?>
    			<?php 
               $mod = ($category_index) % 4;
               if ($mod == 0)  {
            ?>
               <tr>
    			<?php } else if ($mod == 4) { $insertEndTR = true; }?>

    			   <td style="text-align:left;"><a style="font-size:8pt;" href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></td>

            <?php if ($insertEndTR) { ?>
             </tr>
            <?php 
                $insertEndTR=false; 
             } ?>
    		<?php endforeach; ?>
  </table>
  <?php } ?>
  <?php if ($products && !$have_featured_products) { ?>
  <div class="sort">
    <div class="div1">
      <a href="<?php echo($viewallurl) ?>" name="viewall"><?php echo $text_view_all;  ?></a>
    </div>
    <div class="div1">
      <select name="sort" onchange="location=this.value">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if (($sort . '-' . $order) == $sorts['value']) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="div2"><?php echo $text_sort; ?></div>
   </div>
  <?php if (defined('BENDER')) { ?>
  <?php require_once DIR_FRONTOFFICE.'catalog/view/includes/product_list_common.php'; ?>
  <?php } else { ?>
  <?php require_once DIR_FRONTOFFICE.'catalog/view/includes/retail_product_list_common.php'; ?>
  <?php } ?>
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } else { ?>
  <?php if ($have_featured_products) { ?> <div class="heading"><h2>Featured Products</h2></div> <?php } ?>
  <?php if (defined('BENDER')) { ?>
  <?php require_once DIR_FRONTOFFICE.'catalog/view/includes/product_list_common.php'; ?>
  <?php } else { ?>
  <?php require_once DIR_FRONTOFFICE.'catalog/view/includes/retail_product_list_common.php'; ?>
  <?php } ?>
  <?php } ?>
</div>
<div class="bottom">&nbsp;</div>
<script type="text/javascript">
/*$(document).ready(function(){
   $('.subcategory_list ul').hide();
   $('<a class="important"><strong>View All Subcategories</strong></a>').addClass('slidetoggle').click(function(){
      $('.subcategory_list ul').slideToggle();
   }).insertAfter('.subcategory_list ul');
});*/
</script>
<?php if($have_featured_products): ?>
<script type="text/javascript">
 $(document).ready(function(){
  if($('.featured_grid')){
    products = $('.featured_item').clone();
    $('<div id="featured_product_carousel"></div>').data('featured',{index:0, slides:products} ).insertBefore('.featured_grid');
    rotateFeatured();
  }

 });

 function rotateFeatured(){
  featuredIndex = $('#featured_product_carousel').data('featured').index;
  featuredSlide = $('#featured_product_carousel').data('featured').slides[featuredIndex];

  if(featuredIndex === $('#featured_product_carousel').data('featured').slides.length ){
   featuredIndex = 0;
   featuredSlide = $('#featured_product_carousel').data('featured').slides[featuredIndex];
  }

  $('#featured_product_carousel .featured_item').remove();
  $(featuredSlide).clone().hide().removeAttr('style').prependTo('#featured_product_carousel');
  //$('#featured_product_carousel a.add_to_cart').text('Buy Now!');
  $('#featured_product_carousel .featured_item').fadeIn(1200);

  $('#featured_product_carousel').data('featured').index = ++featuredIndex;
  setTimeout(rotateFeatured, 6500);
 }
</script>

<?php endif; ?>
