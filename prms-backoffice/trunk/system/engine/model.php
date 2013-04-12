<?php
abstract class Model {
    
	public function __get($key) {
		return Registry::get($key);
	}
	
	public function __set($key, $value) {
		Registry::set($key, $value);
	}
	
	public function d ($msg, $label = null, $vardump = false) {
	    require_once DIR_SYSTEM.'library/debugger.php';
	    $debugger = new Debugger();
	    return $debugger->d($msg, $label, $vardump);
	}
	
	public function x ($msg, $label = null, $vardump = false) {
	    require_once DIR_SYSTEM.'library/debugger.php';
	    $debugger = new Debugger();
	    return $debugger->x($msg, $label, $vardump);
	}
	
	
	public function get_pulldown_options ($data, $selected = null, $firstblank = false, $allow_numeric_keys = false, $css_styler = null) {
		
	    if (empty($data)) {
	        return array();
	    }
	    
		$out = "";
		
		if ($firstblank) {
			$out .= '<option value="">&nbsp;</option>';
		}
		
		$keys = array_keys($data);

		if ($keys[0] === 0 && $allow_numeric_keys == false) { // no keys were specified, so we put the array values as keys
			foreach ($data as $val) {
				$data2[$val] = $val;
			}
			
			unset($data);
			$data = $data2;
		}

		
		if (!is_array($selected))
			$selected = array($selected);		
		
		foreach ($data as $key => $val) :
			$out .= '<option style="'.$css_styler.'" value="'.$key.'" '.( in_array($key, $selected) ? 'selected="selected"' : false ).'>'.$val.'</option>'."\n";
							// NOTE: this function assumes that numeric keys mean that the display and submit values should be the same
		endforeach;
		
		return $out;
		
	}	
	
}
?>