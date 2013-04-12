<?php
final class HelperImage {
    
	static public function resize ($filename, $width, $height, $abs=0) {
	    
    	if (!file_exists(DIR_IMAGE . $filename)) {
         $filename = 'no_image.jpg';
    		//return;
    	} 
    	
    	$old_image = $filename;
    	$new_image = 'cache/' . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.jpg';
    	
    	if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
    		$image = new Image(DIR_IMAGE . $old_image);
    		$image->resize($width, $height);
    		$image->save(DIR_IMAGE . $new_image);
    	}
    
      if ($abs) {
         return DIR_IMAGE . $new_image;
      } else {
    	   if ((isset($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] == 'on')) {
    		   return HTTPS_IMAGE . $new_image;
    	   } else {
    		   return HTTP_IMAGE . $new_image;
    	   }
      }
		
	}
	
	
	static public function resize_for_alt_product_thumb ($filename, $width, $height) {
	    
	    $subdir = DIR_IMAGE . 'alt_product_thumbs/';
	        
    	if (!file_exists($subdir . $filename)) {
    		return;
    	} 
    	
    	$old_image = $filename;
    	$new_image = 'cache/' . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.jpg';

    	if (!file_exists($subdir . $new_image) || (filemtime($subdir . $old_image) > filemtime($subdir . $new_image))) {
    		$image = new Image($subdir . $old_image);
    		$image->resize($width, $height);
    		$image->save($subdir . $new_image);
    	}
    
    	if ((isset($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] == 'on')) {
    		return HTTPS_IMAGE . 'alt_product_thumbs/' . $new_image;
    	} else {
    		return HTTP_IMAGE . 'alt_product_thumbs/' . $new_image;
    	}
		
	}	
	
}
?>
