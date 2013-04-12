<?php
class ControllerCatalogMedia extends Controller {
    
    
	private $error = array();
	
	
	public function index() {	
	    
		$this->load->language('catalog/media');
		
		$data = array();
		
		if ($this->validate()) {
			$filename = basename($this->request->files['media-upload']['name']);
    		
			if (@move_uploaded_file($this->request->files['media-upload']['tmp_name'], DIR_IMAGE . $filename)) {
				@unlink($this->request->files['media-upload']['tmp_name']);
	  		
				$this->load->helper('media');
			
				$data['file'] = $filename;
				$data['src'] = HelperMedia::get_filepath($filename);
			}
		} else {
			$data['error'] = $this->error['message'];
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($data));
		
	}	
	
	
	private function validate() {
	    
		if (!$this->user->hasPermission('modify', 'catalog/media')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->files['media-upload'])) {
			if (is_uploaded_file($this->request->files['media-upload']['tmp_name'])) {
	  			if ((strlen(utf8_decode($this->request->files['media-upload']['name'])) < 3) || (strlen(utf8_decode($this->request->files['media-upload']['name'])) > 255)) {
        			$this->error['message'] = $this->language->get('error_filename');
	  			}
       
		    	$allowed = array(
		      		'audio/mp3',
		    	    'audio/mpeg',
		      		'video/x-ms-wmv',
					'video/avi'
		    	);
				
				if (!in_array($this->request->files['media-upload']['type'], $allowed)) {	
          			$this->error['message'] = $this->language->get('error_filetype');
        		}
										
				if ($this->request->files['media-upload']['error'] != UPLOAD_ERR_OK) {
					$this->error['message'] = $this->language->get('error_upload_' . $this->request->files['media-upload']['error']);
				}
			}
		} else {
			$this->error['message'] = $this->language->get('error_required');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
		
	}
	
	
}
?>