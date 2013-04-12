<div class="heading">
  <h1>Catalog <?php echo $code ?></h1>
  <div class="buttons"><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      <tr>
        <td>User (Owner)</td>
        <td>
        	<?php echo $user_name ?>
          </td>
      </tr>    
      <tr>
			<td width="25%">Catalog Code</td>
			<td>
				<?php echo $code ?>
				<br />
          	</td>
      </tr>    
      <tr>
			<td width="25%">Name</td>
			<td><?php echo $name ?>
    			<br />
	        </td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
