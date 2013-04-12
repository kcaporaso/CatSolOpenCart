<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <div><?php echo $welcome; ?></div>
  <? /*  ?><div><?php echo @$events; ?></div><? */ // enable to show Event on Home Page if on current day ?>
  <div class="heading">
  <?php if ($_SESSION['store_code'] != 'IPA') { ?>
  <?php echo $text_featured; ?>
  <?php } ?>
  </div>
  <?php if (defined('BENDER')) { ?>
  <?php require_once DIR_FRONTOFFICE.'catalog/view/includes/product_list_common.php'; ?>
  <?php } else { ?>
  <?php require_once DIR_FRONTOFFICE.'catalog/view/includes/retail_product_list_common.php'; ?>
  <?php } ?>
</div>
<div class="bottom">&nbsp;</div>
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

  $('#featured_product_carousel .featured_item').fadeOut('fast',function(){$(this).remove();});
  $(featuredSlide).clone().hide().removeAttr('style').prependTo('#featured_product_carousel');
  //$('#featured_product_carousel a.add_to_cart').text('Buy Now!');
  $('#featured_product_carousel .featured_item').fadeIn(1200);

  $('#featured_product_carousel').data('featured').index = ++featuredIndex;
  setTimeout(rotateFeatured, 6500);
 }
</script>
