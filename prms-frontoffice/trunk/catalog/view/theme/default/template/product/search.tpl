<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <?php if ($search_requested): ?>
      <div class="heading"><?php echo $text_search; ?></div>
      <?php if (isset($products)) { ?>
            <div class="sort">
                <div class="div1"><a href="<?php echo($viewallurl) ?>" name="viewall"><?php echo $text_view_all;  ?></a></div>
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
    		<div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-top: 3px; margin-bottom: 15px;"><?php echo $text_empty; ?></div>
      <?php }?>
  <?php endif; ?>

<?php if($_SESSION['store_code'] != 'IPA'): // NASTY AND I HATE IT!! ?>
<div class="heading"><?php echo $text_critea; ?></div>
<?php endif; ?>
  <div id="content_search" style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-top: 3px; margin-bottom: 10px;">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="powersearch_form">
    <table>
      <tr>
        <td align="right">Product Name, Item Number or Meta-Keywords :</td>
        <td>
          <input size="50" type="text" name="keyword" value="<?php echo $keyword; ?>" id="keyword" />
      </tr>
      <tr>
      	<td align="right"></td>
        <td>&nbsp;&nbsp;<?php if ($description) { ?>
          <input type="checkbox" name="description" id="description" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="description" id="description" />
          <?php } ?>
          Also search in product descriptions.</td>
      </tr>
      <?php if ($powersearch_flag): ?>
		<tr>
			<td align="right">
				Grade Level :
			</td>
			<td>
                <select name="params[gradelevel_gradeweight]" style="width: 100%;" id="gradelevel_gradeweight">
                  <option value="">--- ANY ---</option>
                  <?php foreach ((array)$gradelevels as $gradelevel) { ?>
                  <?php if ($gradelevel['gradeweight'] == $_SESSION['powersearch']['params']['gradelevel_gradeweight']) { ?>
                  <option value="<?php echo $gradelevel['gradeweight']; ?>" selected="selected"><?php echo $gradelevel['display_name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $gradelevel['gradeweight']; ?>"><?php echo $gradelevel['display_name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
			</td>			
    	</tr>
    	<tr>
    		<td align="right">
    			Product Category :
    		</td>
    		<td>
                <select name="params[category_path]" style="width: 100%;" id="category_path">
                  <option value="">--- ANY ---</option>
                  <?php echo $categories_dropdown; ?>
                </select>    		
    		</td>
    	</tr>
    	<tr>
    		<td align="right">
    			Brand : 
    		</td>
    		<td>
                <select name="params[manufacturer_id]" style="width: 100%;" id="manufacturer_id">
                  <option value="">--- ANY ---</option>
                  <?php foreach ((array)$manufacturers as $manufacturer) { ?>
                  <?php if ($manufacturer['manufacturer_id'] == $_SESSION['powersearch']['params']['manufacturer_id']) { ?>
                  <option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
    		</td>
    	</tr>      
      <?php endif; ?>
    </table>
    </form>    
  </div>
  <div class="buttons">
    <table>
      <tr>
      	<td align="left">
      		<?php echo $alt_search_link; ?>
      	</td>
        <td align="right">
            <div >
            	<?php if ($powersearch_flag): ?><a onclick="location.href ='<?php echo $clear_action; ?>'; " class="button"><span>Clear</span></a><?php endif; ?>
            	<a onclick="document.getElementById('powersearch_form').submit();" class="button"><span>Search</span></a>
            </div>
        </td>          
      </tr>
    </table>
  </div>
  
</div>
<div class="bottom">&nbsp;</div>
<script type="text/javascript"><!--
$('#content_search input').keydown(function(e) {
	if (e.keyCode == 13) {
		contentSearch();
	}
});
/*
function contentSearch() {
	
	url = 'index.php?route=product/search';
	
	var keyword = $('#keyword').attr('value');
	
	if (keyword) {
		url += '&keyword=' + encodeURIComponent(keyword);
	}
	
	if ($('#description').attr('checked')) {
		url += '&description=1';
	}

	location = url;
	
}
*/
//--></script>
