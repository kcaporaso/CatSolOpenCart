<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1>Manufacturer <?php echo $manufacturer_id; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_name; ?></td>
        <td><input name="name" value="<?php echo $name; ?>" />
          <br />
          <?php if ($error_name) { ?>
          <span class="error"><?php echo $error_name; ?></span>
          <?php } ?>
          <span class="help">Note : do not "recycle" this Manufacturer by naming it something else. You may only correct for typos. Keep in mind that a Manufacturer may be referenced in old Products etc. If you need a new Manufacturer please create a new one.</span>
         </td>
      </tr>
      <tr>
        <td><?php echo $entry_keyword; ?></td>
        <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_image; ?></td>
        <td><input type="file" id="upload" />
          <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" /></td>
      </tr>
      <tr>
        <td></td>
        <td><img src="<?php echo $preview; ?>" alt="" id="preview" style="border: 1px solid #EEEEEE;" /></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript" src="view/javascript/jquery/ajaxupload.3.1.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() { 
	new AjaxUpload('#upload', {
		action: 'index.php?route=catalog/image',
		name: 'image',
		autoSubmit: true,
		responseType: 'json',
		onChange: function(file, extension) {},
		onSubmit: function(file, extension) {
			$('#upload').after('<img src="view/image/loading.gif" id="loading" />');
		},
		onComplete: function(file, json) {
			if (json.error) {
				alert(json.error);
			} else {
				$('#preview').attr('src', json.src);

				$('#image').attr('value', json.file);
			}
			
			$('#loading').remove();	
		}
	});
});	
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
