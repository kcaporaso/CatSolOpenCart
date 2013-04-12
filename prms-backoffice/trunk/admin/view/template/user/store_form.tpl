<?php 
    if (!$_SESSION['user_is_admin']) {
        $css_display_none_if_not_admin = "display: none";
    }
?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1>Store <?php echo $code ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">      
      <tr style="<?php echo $css_display_none_if_not_admin ?>">
        <td>User (Owner)</td>
        <td>
        	<?php if ($_SESSION['user_is_admin'] && $operation=='insert'): ?>
        		<select name="user_id">
        			<?php echo $users_dropdown_options ?>
        		</select>
        	<?php else: ?>
        		<input disabled type="text" name="user_name" value="<?php echo $user_name; ?>" />
        	<?php endif; ?>
        </td>
      </tr>      
      <tr>
        <td>Store Code</td>
        <td>
        	<?php if ($_SESSION['user_is_admin'] && $operation=='insert'): ?>
        		<input type="text" name="code" value="<?php echo $code; ?>" />
        	<?php else: ?>
        		<input disabled type="text" name="code" value="<?php echo $code; ?>" />
        	<?php endif; ?>
           <span class="help">(Temp catalog will be at:  storecode.catsolonline.com<br/>
            Home page will be at:  catalogsolutionsprogramming.com/storecode)</span>
        </td>        
      </tr>
      <tr>
        <td>Store Name</td>
        <td><input size="40" type="text" name="name" value="<?php echo $name; ?>" /></td>
      </tr>
      <tr>
        <td>Storefront URL</td>
        <td><input size="40" type="text" name="storefront_url" value="<?php echo $storefront_url; ?>" />&nbsp;e.g: catalog.domainname.com<strong>/</strong> </td>
      </tr>      
      <tr>
        <td>Final DOMAIN (for Apache)</td>
        <td>
        	 <?php if ($_SESSION['user_is_admin'] && $operation=='insert'): ?>
           <input size="40" type="text" name="final_domain" value="<?php echo $final_domain; ?>" />
        	<?php else: ?>
           <input disabled size="40" type="text" name="final_domain" value="<?php echo $final_domain; ?>" />
        	<?php endif; ?>
           &nbsp;e.g: domainname.com <span class="help">[ <span style="color:red">DO NOT include www, catalog, admin at the beginning of the domain name</span> ]</span>
        </td>
      </tr> 

      <tr>
        <td>Catalogs</td>
        <td><div class="scrollbox">
            <?php $class = 'odd'; ?>
            <?php unset($_SESSION['user/store_form/restricted_checked_productsets']); ?>
            <?php foreach ($productsets as $productset) { ?>
            	<?php $is_restricted_productset = (in_array($productset['productset_id'], $restricted_productset_ids))? 'disabled' : '' ; ?>
                <?php $checked_if_so = (in_array($productset['productset_id'], $store_productsets))? 'checked="checked"' : ''; ?>
                <?php
                    if ($is_restricted_productset) {
                        if ($checked_if_so) {                        
                            $_SESSION['user/store_form/restricted_checked_productsets'][] = $productset['productset_id'];
                        } else {
                            continue;
                        }
                    }
                ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
					<input type="checkbox" name="store_productsets[]" value="<?php echo $productset['productset_id']; ?>" <?php echo $checked_if_so; ?> <?php echo $is_restricted_productset; ?> />
                    <?php echo "<strong>{$productset['code']}</strong> : ". $productset['name']; ?>
                </div>
            <?php } ?>
          </div></td>
      </tr>
  
    </table>
  </div>
</form>
