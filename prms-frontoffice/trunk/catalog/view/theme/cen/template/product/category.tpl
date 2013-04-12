
<div class="top">
  <?php if(!empty($categories)):?>
  <div id="select_category">
  	<select onchange="location=this.value;">
        <option value="">Select A Category</option>
    	<?php foreach($categories as $category):?>
        <option value="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></option>
        <?php endforeach; ?>
    </select>
  </div>
  <?php endif;?>
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <?php if ($description) { ?>
  <div style="margin-bottom: 15px;"><?php echo $description; ?></div>
  <?php } ?>
  <?php if (!empty($categories)) { 
     $insertTR = false;
     $insertEndTR = false;
  ?>
	<?php /* ?>
  <table class="list" style="padding-bottom:0px;">
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
  	<?php */ ?>
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
  <?php require_once DIR_FRONTOFFICE.'catalog/view/includes/CEN_product_list_common.php'; ?>
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } else { ?>
  <?php /*if ($have_featured_products) { ?> <div align="center" style="padding-top:0px;"><h2>Featured Products</h2></div> <?php } */ ?>
  <?php require_once DIR_FRONTOFFICE.'catalog/view/includes/CEN_product_list_common.php'; ?>
  <?php } ?>
</div>
<div class="bottom">&nbsp;</div>
