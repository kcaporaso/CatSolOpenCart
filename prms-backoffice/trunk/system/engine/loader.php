<?php
final class Loader {
	public function __get($key) {
		return Registry::get($key);
	}

	public function __set($key, $value) {
		Registry::set($key, $value);
	}
	
	public function library($library) {
		$file = DIR_SYSTEM . 'library/' . $library . '.php';
		
		if (file_exists($file)) {
			include_once($file);
		} else {
			exit('Error: Could not load library ' . $library . '!');
		}
	}
	
	
	public function model ($model, $load_from_frontend=false) {
	    
	    if ($load_from_frontend) {
	        $app_dirname = DIR_FRONTOFFICE.'catalog/';
	    } else {
	        $app_dirname = DIR_APPLICATION;
	    }
	    
		$file  = $app_dirname . 'model/' . $model . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
		
		if (file_exists($file)) {
			include_once($file);
			
			Registry::set('model_' . str_replace('/', '_', $model), new $class());
		} else {
			exit('Error: Could not load model ' . $model . '!');
		}
		
	}
	
	 
	public function database($driver, $hostname, $username, $password, $database, $prefix = NULL, $charset = 'UTF8') {
		$file  = DIR_SYSTEM . 'database/' . $driver . '.php';
		$class = 'Database' . preg_replace('/[^a-zA-Z0-9]/', '', $driver);
		
		if (file_exists($file)) {
			include_once($file);
			
			Registry::set(str_replace('/', '_', $driver), new $class());
		} else {
			exit('Error: Could not load database ' . $driver . '!'); 
		}
	}
	
	public function helper($helper) {    
		$file = DIR_SYSTEM . 'helper/' . $helper . '.php';
	
		if (file_exists($file)) {
			include_once($file);
		} else {
			exit('Error: Could not load helper ' . $helper . '!');
		}
	}
	
	public function config($config) {
		$this->config->load($config);
	}
	
	public function language($language, $load_from_frontend=false) {
		$this->language->load($language, $load_from_frontend);
	}
} 
?>