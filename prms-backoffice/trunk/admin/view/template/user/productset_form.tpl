<?php 

    if (!$_SESSION['user_is_admin']) {
        $css_display_none_if_not_admin = "display: none";
    }
    
    $html_disabled = "disabled";
?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      <tr>
        <td>User (Owner)</td>
        <td>
        	<select name="user_id" >
                <?php foreach ($users as $user) { ?>
                <?php if ($user['user_id'] == $user_id) { ?>
                	<option value="<?php echo $user['user_id']; ?>" selected="selected"><?php echo $user['name']; ?></option>
                <?php } else { ?>
                	<option value="<?php echo $user['user_id']; ?>"><?php echo $user['name']; ?></option>
                <?php } ?>
                <?php } ?>
          	</select>
          </td>
      </tr>    
      <tr>
			<td width="25%"><span class="required">*</span> Catalog Code</td>
			<td>
				<input type="text" name="code" value="<?php echo $code?>" size="4"  style="text-transform:uppercase" <?php echo ($routeop == 'update')? $html_disabled : ''; ?> />
				<?php if ($routeop == 'update'): ?>
					<input type="hidden" name="code" value="<?php echo $code; ?>" />
				<?php endif; ?>
                <br />
                <?php if ($error_code) { ?>
                	<span class="error"><?php echo $error_code; ?></span>
                <?php } ?>
          	</td>
      </tr>    
      <tr>
			<td width="25%"><span class="required">*</span> Name</td>
			<td><input type="text" name="name" value="<?php echo $name?>" size="60" />
    			<br />
    	        <?php if ($error_name) { ?>
    				<span class="error"><?php echo $error_name; ?></span>
    	        <?php } ?>
	        </td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>