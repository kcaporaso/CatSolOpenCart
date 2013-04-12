<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
  	<h3>Hello 
     <?php echo $this->user->getFirstName() . ' ' . $this->user->getLastName();
     if ($this->user->isSPS()) {
        echo ' (' . $this->user->getRoleName() . ')';
     }
     ?></h3>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      <tr>
        <td colspan="2">
            <?php 
                if ($_SESSION['store_code']) {
                    echo "Your currently selected Store is <strong>{$_SESSION['store_code']}</strong>.";                    
                } else {
                    echo "You have not selected a Store to manage. Doing so will unhide some Store-specific tabs in the nav menu.";
                }
            ?>
            
        </td>
      </tr>
      <tr><td align="center" colspan="2">
      	<?php if (file_exists(DIR_BACKOFFICE.'image/stores/'.$_SESSION['store_code'].'/'.$this->config->get('config_logo'))): ?>
      		<img src="<?php echo HTTP_IMAGE.'stores/'.$_SESSION['store_code'].'/'.$this->config->get('config_logo') ?>" />
      	<?php endif; ?>
      </td>
      </tr>
      <tr><td colspan="2" align="center">
  	<div class="buttons" style="float:left;">
  		<a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle">Submit</span><span class="button_right"></span></a>
  	</div>
      </td></tr>
      <tr>
        <td width="15%">
                
        </td>
        <td>
            <?php 
                if ($_SESSION['store_code']) {
                    echo "To change your current Store selection, please";
                } else {
                    echo "Please";
                }
            ?>        
        	 select the Store you would like to manage :<br/><br/>
        	<table>
        		<?php $lone_store_checked = 'checked'; ?>
        		<?php foreach ($stores as $store): ?>
            		<?php 
            		    if (count($stores) == 1 || ($store['code'] == $_SESSION['store_code'])) {
            		        $this_store_checked = 'checked';
            		    } else {
            		        $this_store_checked = '';
            		    }
            		?>
            		<tr>
            			<td>
            				<input id="radio_store_code_<?php echo $store['code']; ?>" type="radio" name="store_code" value="<?php echo $store['code']; ?>" <?php echo $this_store_checked; ?> ></input>
            			</td>
            			<td>
            				<label for="radio_store_code_<?php echo $store['code']; ?>"><strong><?php echo $store ['code']; ?></strong> : <?php echo $store['name']; ?></label>
            			</td>
            		</tr>
        		<?php endforeach; ?>
        	</table>
        </td>
      </tr>
    </table>
  	<div class="buttons">
  		<a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle">Submit</span><span class="button_right"></span></a>
  	</div>
  </div>
</form>
