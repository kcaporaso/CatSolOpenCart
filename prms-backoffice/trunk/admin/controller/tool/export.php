<?php 

class ControllerToolExport extends Controller { 
    
    
	private $error = array();
	
	
	public function index() {
	
	    if ($import_type = $this->request->post['import_type']) {
	        
	        unset($_SESSION['products_importer']['import_type']);
	        
	        $_SESSION['products_importer']['import_type']['selected'] = $import_type;
	        
	        if ($import_type == 'products_A') {
	            
	            $_SESSION['products_importer']['import_type']['import_type_products_A_checked'] = 'checked';
	            
	        } elseif ($import_type == 'products_B') {
	            
	            $_SESSION['products_importer']['import_type']['import_type_products_B_checked'] = 'checked';

	        } elseif ($import_type == 'products_C') {
	            
	            $_SESSION['products_importer']['import_type']['import_type_products_C_checked'] = 'checked';	            

	        } elseif ($import_type == 'products_D') {
	            
	            $_SESSION['products_importer']['import_type']['import_type_products_D_checked'] = 'checked';	            
	            	            
	        } elseif ($import_type == 'options') {
	            	            
	            $_SESSION['products_importer']['import_type']['import_type_options_checked'] = 'checked';
	            
	        }
	        
	   }
	    
	   if (!$_SESSION['products_importer']['import_type']) {
	        $_SESSION['products_importer']['import_type']['import_type_products_A_checked'] = 'checked';
	        $_SESSION['products_importer']['import_type']['selected'] = 'products_A';
	   }

      // KMC Checking for core dataset or not.
      if ($core_dataset = $this->request->post['core_dataset'])
      {
         unset($_SESSION['core_dataset']);
         if ($core_dataset == 'Yes') {
            $core_dataset = true;
            $_SESSION['core_dataset']['Yes'] = 'checked';
            $_SESSION['core_dataset']['No'] = '';
         } else {
            $core_dataset = false;
            $_SESSION['core_dataset']['No'] = 'checked';
            $_SESSION['core_dataset']['Yes'] = '';
         }
      }
//echo 'core_dataset:' . $_SESSION['core_dataset'];	    
//exit;
		$this->load->language('tool/export');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('tool/export');
		
      $this->load->model('store/product');
      $this->model_store_product->createUnjunctionedProductRecords($_SESSION['store_code']);		

//KMC adding limit stuff.
      set_time_limit(0);
      ini_set('max_input_time', '0');
      ini_set('max_execution_time', '0');
/////

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
		    
        if ((isset( $this->request->files['upload'] )) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
			    
            // KMC for the export.tpl, to help track uploading.
            $_SESSION['uploaded_filename'] = $this->request->files['upload']['name'];

				$file = $this->request->files['upload']['tmp_name'];
				
				$import_result = $this->model_tool_export->upload($file, $core_dataset);
				if (!$import_result) {
				    $this->error['warning'] = $this->language->get('error_upload');
				} elseif (!empty($import_result['errors'])) {
				    $this->error['warning'] = $import_result;
				} else {
				    
					$this->session->data['success'] = $this->language->get('text_success');
					$this->redirect($this->url->http('tool/export'));

				}
				
			} else { echo ' too large to upload ?';exit; }
			
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['entry_restore'] = $this->language->get('entry_restore');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['button_import'] = $this->language->get('button_import');
		$this->data['button_export'] = $this->language->get('button_export');
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['error_warning'] = @$this->error['warning'];

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('tool/export'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->data['action'] = $this->url->https('tool/export');
		$this->data['success'] = @$this->session->data['success'];
		unset($this->session->data['success']);
		$this->data['export'] = $this->url->https('tool/export/download');
		$this->id       = 'content';
		$this->template = 'tool/export.tpl';
		$this->layout   = 'common/layout';
		$this->render();
		
	}


	public function download() {
	    
		if ($this->validate()) {

			// set appropriate memory and timeout limits
			ini_set("memory_limit","128M");
			set_time_limit( 1800 );

			// send the categories, products and options as a spreadsheet file
			$this->load->model('tool/export');
			$this->model_tool_export->download();

		} else {

			// return a HTTP 404 error
			return $this->forward('error/error_404', 'index');
		}
		
	}


	private function validate() {
	    
		if (!$this->user->hasPermission('modify', 'tool/export')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
			
	}
	
	
}
?>
