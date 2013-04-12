<!-- Part of Events Calendar by Fido-X (http://www.fido-x.net) -->
<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
	<h1><?php echo $heading_title; ?></h1>
	<div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a><a tab="#tab_data"><?php echo $tab_data; ?></a><!--a tab="#tab_image"><?php echo $tab_image; ?></a--></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	<div id="tab_general" class="page">
    <table class="form">
      <?php foreach ($languages as $language) { ?>
        <tr>
          <td width="25%"><span class="required">*</span> <?php echo $entry_title; ?></td>
          <td><input name="calendar_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo @$calendar_description[$language['language_id']]['title']; ?>" />
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
            <?php if (@$error_title[$language['language_id']]) { ?>
            <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
            <?php } ?></td>
        </tr>
        <?php /* ?>
        <tr>
          <td><?php echo $entry_keyword; ?></td>
          <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" /></td>
        </tr>
        <?php */ ?>
        <!--tr>
          <td><?php echo $entry_start_message; ?></td>
          <td><input name="calendar_description[<?php echo $language['language_id']; ?>][start_message]" value="<?php echo @$calendar_description[$language['language_id']]['start_message']; ?>" />
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_interim_message; ?></td>
          <td><input name="calendar_description[<?php echo $language['language_id']; ?>][interim_message]" value="<?php echo @$calendar_description[$language['language_id']]['interim_message']; ?>" />
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_end_message; ?></td>
          <td><input name="calendar_description[<?php echo $language['language_id']; ?>][end_message]" value="<?php echo @$calendar_description[$language['language_id']]['end_message']; ?>" />
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></td>
        </tr-->
        <tr>
          <td><span class="required">*</span> <?php echo $entry_description; ?></td>
          <td><textarea name="calendar_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo @$calendar_description[$language['language_id']]['description']; ?></textarea>
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" />
            <?php if (@$error_description[$language['language_id']]) { ?>
            <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
            <?php } ?></td>
        </tr>
      <?php } ?>
    </table>
	</div>
	<div id="tab_data" class="page">
    <table class="form">
      <tr>
		  <td width="25%"><?php echo $entry_status; ?></td>
		  <td><select name="status">
          <?php if ($status) { ?>
				<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
				<option value="0"><?php echo $text_disabled; ?></option>
			  <?php } else { ?>
				<option value="1"><?php echo $text_enabled; ?></option>
				<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
			  <?php } ?>
		    </select></td>
		</tr>
		<tr>
		  <td><?php echo $entry_start_date; ?></td>
	     <td><input type="text" name="start_date" value="<?php echo $start_date; ?>" size="12" id="start_date" /></td>
	   </tr>
	   <!--tr>
		  <td><?php echo $entry_interim_date; ?></td>
	     <td><input type="text" name="interim_date" value="<?php echo $interim_date; ?>" size="12" id="interim_date" /></td>
	    </tr>
	    <tr>
		  <td><?php echo $entry_end_date; ?></td>
	     <td><input type="text" name="end_date" value="<?php echo $end_date; ?>" size="12" id="end_date" /></td>
	    </tr-->
	 </table>
	</div>
	<!--div id="tab_image" class="page">
    <table class="form">
      <tr>
        <td width="25%"><?php echo $entry_image; ?></td>
        <td><input type="file" id="upload" />
          <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" /></td>
      </tr>
      <tr>
        <td></td>
        <td><img src="<?php echo $preview; ?>" alt="" id="preview" style="border: 1px solid #EEEEEE;" /></td>
      </tr>
      <tr>
		  <td><?php echo $entry_image_size; ?></td>
		  <td><select name="image_size">
			 <?php if ($image_size) { ?>
				<option value="1" selected="selected"><?php echo $text_fullsize; ?></option>
				<option value="0"><?php echo $text_thumbnail; ?></option>
			 <?php } else { ?>
				<option value="1"><?php echo $text_fullsize; ?></option>
				<option value="0" selected="selected"><?php echo $text_thumbnail; ?></option>
			 <?php } ?>
		  </select></td>
		</tr>
	 </table>
	</div-->
</form>
<script type="text/javascript" src="view/javascript/fckeditor/fckeditor.js"></script>
<script type="text/javascript"><!--
var sBasePath = document.location.href.replace(/index\.php.*/, 'view/javascript/fckeditor/');
<?php foreach ($languages as $language) { ?>
var oFCKeditor<?php echo $language['language_id']; ?>          = new FCKeditor('description<?php echo $language['language_id']; ?>');
	oFCKeditor<?php echo $language['language_id']; ?>.BasePath = sBasePath;
	oFCKeditor<?php echo $language['language_id']; ?>.Value	   = document.getElementById('description<?php echo $language['language_id']; ?>').value;
	oFCKeditor<?php echo $language['language_id']; ?>.Width    = '520';
	oFCKeditor<?php echo $language['language_id']; ?>.Height   = '300';
	oFCKeditor<?php echo $language['language_id']; ?>.ReplaceTextarea();
<?php } ?>
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ajaxupload.3.1.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() { 
	/*setUploader('#upload', '#preview', '#image');*/
	$.tabs('.tabs a'); 
});	

function setUploader(upload, preview, image) {
	new AjaxUpload(upload, {
		action: 'index.php?route=catalog/image',
		name: 'image',
		autoSubmit: true,
		responseType: 'json',
		onChange: function(file, extension) {},
		onSubmit: function(file, extension) {
			$(upload).after('<img src="view/image/loading.gif" id="loading" />');
		},
		onComplete: function(file, json) {
			if (json.error) {
				alert(json.error);
			} else {
				$(preview).attr('src', json.src);
				$(image).attr('value', json.file);
			}
			$('#loading').remove();	
		}
	});
}
//--></script>
<link rel="stylesheet" type="text/css" href="view/stylesheet/datepicker.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#start_date').datepicker({dateFormat: 'yy-mm-dd'});
});
$(document).ready(function() {
	$('#interim_date').datepicker({dateFormat: 'yy-mm-dd'});
});
$(document).ready(function() {
	$('#end_date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
