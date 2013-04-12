<?php
final class HelperMedia {
    
    
	static public function present ($filename) {
	    
    	if (!file_exists(DIR_IMAGE . $filename)) {
    		return;
    	}
    	
    	$filepath = HelperMedia::get_filepath($filename);
        if (strpos($filename, '.swf')===false && strpos($filename, '.mpeg')===false) {
           $result = "<a href='{$filepath}' rel='shadowbox;height=480;width=640'>Demo</a>";
        } else {
           // if flash, wrap it.
           $media = "<a href='{$filepath}' rel='shadowbox;height=480;width=640'>Demo</a>";
           /*$media = '<object width="200" height="150">'
           $media .= '<param name="movie" value="'.$filepath.'">';
           $media .= '<embed src="'.$filepath.'" width="200" height="150">';
           $media .= '</embed></object>';
           $result = $media;*/
           /*$media = '<div id="flashcontent"></div>';
           $media .= '<script type="text/javascript">';
           $media .= 'var so = new SWFObject("' . $filepath . '","media","200","150","8",#FFFFFF");';
           $media .= 'so.addParam("wmode", "transparent");';
           $media .= 'so.addParam("allowScriptAccess", "always");';
           $media .= 'so.write("flashcontent");';
           $media .= '</script>';*/
           $result = $media;
        }

        return $result;
	}
	
	
	static public function get_filepath ($filename) {
	    	 
	    if ((isset($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] == 'on')) {
    		$filepath = HTTPS_IMAGE . $filename;
    	} else {
    		$filepath = HTTP_IMAGE . $filename;
    	}    	
    	
    	return $filepath;
		
	}	
    
	
}
?>
