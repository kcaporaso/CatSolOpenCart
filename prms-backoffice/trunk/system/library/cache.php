<?php
//ini_set("memory_limit", -1);
final class Cache { 
	private $expire = 3600;

  	public function __construct() {
		$files = glob(DIR_CACHE . 'cache.*');
    	
		if ($files) {
			foreach ($files as $file) {
      			$time = end(explode('.', basename($file)));

      			if ($time < time()) {
					@unlink($file);
      			}
    		}
		}
  	}

	public function get($key) {
		$files = glob(DIR_CACHE . 'cache.' . $key . '.*');
		
		if ($files) {
    		foreach ($files as $file) {
      			$handle = fopen($file, 'r');
//echo 'file:' . $file . '<br/>';
//echo 'fs:' . filesize($file) . '<br/>';
               if (filesize($file) > 0) {
      			   $cache  = fread($handle, filesize($file));
	            } 
      			fclose($handle);

	      		return unserialize($cache);
   		 	}
		}
  	}

  	public function set($key, $value) {
    	$this->delete($key);
		
		$file = DIR_CACHE . 'cache.' . $key . '.' . (time() + $this->expire);
    	
		$handle = fopen($file, 'w');

    	fwrite($handle, serialize($value));
		
    	fclose($handle);
  	}
	
  	public function delete($key) {
		$files = glob(DIR_CACHE . 'cache.' . $key . '.*');
		
		if ($files) {
    		foreach ($files as $file) {
      			@unlink($file);
    		}
		}
  	}
}
?>
