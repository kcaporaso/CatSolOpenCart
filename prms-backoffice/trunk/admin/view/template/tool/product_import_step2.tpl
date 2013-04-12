<div class="heading">
	<img src="view/image/wand.png" style="float:left;" width="44" height="44" /><h1><?php echo($heading_title); ?></h1>
</div>

<div class="tabs"></div>

<div class="page">
	<h2><?php echo($heading_step2); ?></h2>
	<p><?php echo($entry_step2); ?></p>
	<div class="blue" style="width:180px;">       
		<span id="file-uploader"></span>
		<noscript>          
			<p>Please enable JavaScript to use file uploader.</p>
		</noscript>         
	</div>
	<div id="uploadProgress"></div>
	<div class="buttons">
		<a href="<?php echo $action_step3; ?>" class="button">
			<span class="button_left button_next"></span>
			<span class="button_middle"><?php echo $button_next; ?></span>
			<span class="button_right"></span>
		</a>
	</div>
</div>

<script type="text/javascript" src="view/javascript/SWFUpload/swfupload.js"></script>
<script type="text/javascript" src="view/javascript/SWFUpload/json2.min.js"></script>
<script type="text/javascript" src="view/javascript/SWFUpload/handlers.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	swfu = new SWFUpload({
	
		// Flash Settings
		flash_url : "view/javascript/SWFUpload/swfupload.swf",
		flash9_url : "view/javascript/SWFUpload/swfupload_fp9.swf",
		upload_url: "<?php echo $action_mediaupload; ?>",
		file_post_name : "qqfile",
		//debug: true,
		
		// Button Settings
		button_image_url : "view/image/SmallSpyGlassWithTransperancy_17x18.png",
		button_placeholder_id : "file-uploader",
		button_width: 180,
		button_height: 18,
		button_text : '<span class="button">Select Images <span class="buttonSmall">(2 MB Max)</span></span>',
		button_text_style : '.button { font-family: Helvetica, Arial, sans-serif; font-size: 12pt; } .buttonSmall { font-size: 10pt; }',
		button_text_top_padding: 0,
		button_text_left_padding: 18,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		
		// File Upload Settings
		file_size_limit : "2 MB",	// 2MB
		file_types : "*.jpg;*.gif;*.png",
		file_types_description : "Web Image Files",
		file_upload_limit : 0,

		// Event Handler Settings - these functions as defined in Handlers.js
		// The handlers are not part of SWFUpload
		swfupload_preload_handler : preLoad,
		swfupload_load_failed_handler : loadFailed,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,

		custom_settings : {
			upload_target : "uploadProgress"
		}

	
	});
	
});
</script>