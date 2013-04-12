<?php

final class Language {
    
  	private $code;
  	private $languages = array();
	private $data = array();
 
	
	public function __construct($code = FALSE) {
	    
		$this->config  = Registry::get('config');
		$this->db = Registry::get('db');
		$this->request = Registry::get('request');
		$this->session = Registry::get('session');

    	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language"); 

    	foreach ($query->rows as $result) {
      		$this->languages[$result['code']] = array(
        		'language_id' => $result['language_id'],
        		'name'        => $result['name'],
        		'code'        => $result['code'],
				'locale'      => $result['locale'],
				'directory'   => $result['directory'],
				'filename'    => $result['filename']
      		);
    	}
 		
		if ($code) {
			$this->code = $code; 
		} else {
    		if (array_key_exists(@$this->session->data['language'], $this->languages)) {
      			$this->set($this->session->data['language']);
    		} elseif (array_key_exists(@$this->request->cookie['language'], $this->languages)) {
      			$this->set($this->request->cookie['language']);
    		} elseif ($browser = $this->detect()) {
	    		$this->set($browser);
	  		} else {
        		$this->set($this->config->get('config_language'));
			}
		}
		
		$this->load($this->languages[$this->code]['filename']);	
		
	}
	

	public function set($language) {
	    
		if (isset($this->languages[$language])) {
    		$this->code = $language;
		}
		
    	if ((!isset($this->session->data['language'])) || ($this->session->data['language'] != $this->code)) {
      		$this->session->data['language'] = $this->code;
    	}

    	if ((!isset($this->request->cookie['language'])) || ($this->request->cookie['language'] != $this->code)) {	  
	  		setcookie('language', $this->code, time() + 60 * 60 * 24 * 30, '/', $_SERVER['HTTP_HOST']);
    	}	
    	
	}
	
	
  	public function get($key) {
  	    
   		return (isset($this->data[$key]) ? $this->data[$key] : $key);
   		
  	}
	
  	
	public function load ($filename, $load_from_frontend=false) {
	    
		if ($load_from_frontend) {
	        $lang_dirname = DIR_FRONTOFFICE.'catalog/language/';
	    } else {
	        $lang_dirname = DIR_LANGUAGE;
	    }	    
	    
		$file = $lang_dirname . $this->languages[$this->code]['directory'] . '/' . $filename . '.php';

    	if (file_exists($file)) {
	  		$_ = array();
	  
	  		require($file);
	  
      		$this->data = array_merge($this->data, $_);
    	} else {
      		exit('Error: Could not load language ' . $filename . '!');
    	}
    	
  	}
  	

	private function detect() {
	    
    	if (@$this->request->server['HTTP_ACCEPT_LANGUAGE']) { 
      		$browser_languages = explode(',', @$this->request->server['HTTP_ACCEPT_LANGUAGE']);
			
      		foreach ($browser_languages as $browser_language) {
        		foreach ($this->languages as $key => $language) {
					$locale = explode(',', $language['locale']);

					if (in_array($browser_language, $locale)) {
						return $key;
					}
        		}
      		}
    	}

    	return FALSE;	
    			
	}

	
	public function getId() {
	    
    	return $this->languages[$this->code]['language_id'];
    	
  	}
  	

  	public function getCode() {
  	    
    	return $this->code;
    	
  	}  	
  	
   public function clean_string($string, $pdf=0)
   {
      if (strpos($string, '&scaron;'))
      {
         $string =  str_replace('&scaron;', '', $string);
      }
      if (strpos($string, '&ordf;'))
      {
         $string = str_replace('&ordf;', '', $string);
      }
      if (strpos($string, '&Uacute;'))
      {
         $string = str_replace('&Uacute;', '/', $string);
      }
      if (strpos($string, '&Oacute;'))
      {
         $string = str_replace('&Oacute;', '&quot;', $string);
      }
      if (strpos($string, '&Ograve;'))
      {
         $string = str_replace('&Ograve;', '&quot;', $string);
      }
      if (strpos($string, '&ETH;'))
      {
         $string = str_replace('&ETH;', ':', $string);
      }
      if (strpos($string, '&Otilde;'))
      {
         $string = str_replace('&Otilde;', "'", $string);
      }
      if (strpos($string, '&Ntilde;'))
      {
         $string = str_replace('&Ntilde;', "-", $string);
      }
      if (strpos($string, '&uml;'))
      {
         $string = str_replace('&uml;', "&reg;", $string);
      }
      if (strpos($string, '&yen;'))
      {
         $string = str_replace('&yen;', "-", $string);
      }
      if (strpos($string, '&iexcl;'))
      {
         $string = str_replace('&iexcl;', "&deg;", $string);
      }
      if (strpos($string, '&OElig;'))
      {
         $string = str_replace('&OElig;', "&deg;", $string);
      }
      if (strpos($string, '&Acirc;')) {
         $string = str_replace('&Acirc;', "", $string);
      }
      if (strpos($string, '&rsquo;')) {
         $string = str_replace('&rsquo;', "'", $string);
      }

      if ($pdf) {
         if (strpos($string, '&copy;'))
         {
            $string = str_replace('&copy;', "(c)", $string);
         }
      }
      
      return $string;
   }

   public function get_html_trans($string) {
    //echo 'in: ' . $string . '<br/>';
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark
    $trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook
    $trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark
    $trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis
    $trans[chr(134)] = '&dagger;';    // Dagger
    $trans[chr(135)] = '&Dagger;';    // Double Dagger
    $trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent
    $trans[chr(137)] = '&permil;';    // Per Mille Sign
    $trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron
    $trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark
    $trans[chr(140)] = '&OElig;';    // Latin Capital Ligature OE
    $trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark
    $trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark
    $trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark
    $trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark
    $trans[chr(149)] = '&bull;';    // Bullet
    $trans[chr(150)] = '&ndash;';    // En Dash
    $trans[chr(151)] = '&mdash;';    // Em Dash
    $trans[chr(152)] = '&tilde;';    // Small Tilde
    $trans[chr(153)] = '&trade;';    // Trade Mark Sign
    $trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron
    $trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark
    $trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE
    $trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis
    ksort($trans);
    //print_r($trans);
    $out = strstr($string, $trans);
    //echo 'out:' . $out;
    return $out;
  }  	

  public function clean_store_name($in) {
      $wip = str_replace('&amp;', '&', $in);
      $out = str_replace('&#039;', "'", $wip);
      $out = str_replace('&iexcl;', "'", $out);
      return $out;
  }     

}
?>
