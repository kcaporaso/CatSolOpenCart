<?php
// Heading
$_['heading_title']		= 'Product Import Wizard';
$_['heading_step1']		= 'Step 1 :: Upload Products';
$_['heading_step2']		= 'Step 2 :: Upload Product Images';
$_['heading_step3']		= 'Step 3 :: Verify Upload';
$_['heading_final']		= 'Finished';
$_['heading_upload_format'] = 'Product Data File Columns';

// Entry
$_['entry_upload']		= 'Choose product data for upload:';
$_['entry_description'] = 'This wizard will help you to import all your custom products and related data.  To help you get started we have provided a sample Excel file.  Click the button below to download.  If you have included a product you already imported, it will be assumed that you are wanting to update the existing product.';
$_['entry_step2'] = 'Now it is time to see what your products look like.  Please provide us with images for your products below.  If you use the image on more then one product, you only have to upload it once.  Previously uploaded images are retained, you do not need to reupload them again.  Once all images are finished uploading, click next.  We currently only support images with the following extenstions, .png .jpg .gif  Others will have to be converted before you can upload them.';
$_['entry_step3'] = 'Please verify the information below and make sure it matches what you expect.  Extra long name and descriptions are truncated to save space.  Hover over them to see the entire contents.  You see problems in your data, check to see if they exist in your original excel sheet, correct them, then click "Go Back and Fix Data" to upload the new file.  Existing images from the upload will be saved and will not have to be reuploaded.  Hover over thumbs to preview full sized image.  If you are missing a product image, please click "Upload More Images" and upload the image.  Existing images will be kept, you do not have to reupload them.  If everything is correct click "Save My Products" to continue.';
$_['entry_final']		= 'Product Import Complete.';


// Buttons
$_['button_import']		= 'Import';
$_['button_next']		= 'Next';
$_['button_fixdata']	= 'Go Back and Fix Data';
$_['button_fiximg']		= 'Upload More Images';
$_['button_finish']		= 'Save My Products';

// Links
$_['link_samplexls']	= 'Download Sample Excel Worksheet';

// Error
$_['error_permission'] = 'Warning: You do not have permission to import products!';
$_['error_nostore'] = 'Warning: You must choose a store before importing products!';
$_['error_nogold'] = 'Warning: You must be a gold dealer for import privlidges!';
$_['error_validation'] = 'Warning: Some of your products do not pass the validation process.  Failures are noted below.  Hover over the red for detailed explanation.  Unable to process import until these issues are corrected.';

?>
