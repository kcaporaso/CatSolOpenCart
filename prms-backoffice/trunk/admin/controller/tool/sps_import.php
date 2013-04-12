<?php 

class ControllerToolSPSImport extends Controller { 
    
	private $error = array();
	
	
	public function index() {
	
      //var_dump($this->request->post);exit;
	   $import_types = $this->request->post['import_type'];
	      
      if ($import_types) {
   	   unset($_SESSION['sps_importer']['import_type']);
   	        
   	        
         foreach ($import_types as $type) {
      	   if ($type == 'states') {
   	         $_SESSION['sps_importer']['import_type']['states_checked'] = 'checked';
   	         $_SESSION['sps_importer']['import_type']['states_selected'] = true;
            } 
            if ($type == 'districts') {
   	         $_SESSION['sps_importer']['import_type']['districts_checked'] = 'checked';
   	         $_SESSION['sps_importer']['import_type']['districts_selected'] = true;
            } 
            if ($type == 'schools') {
   	         $_SESSION['sps_importer']['import_type']['schools_checked'] = 'checked';	            
   	         $_SESSION['sps_importer']['import_type']['schools_selected'] = true;
            } 
            if ($type == 'users') {
   	         $_SESSION['sps_importer']['import_type']['users_checked'] = 'checked';	            
   	         $_SESSION['sps_importer']['import_type']['users_selected'] = true;
   	      }
            if ($type == 'chains') {
   	         $_SESSION['sps_importer']['import_type']['chains_checked'] = 'checked';	            
   	         $_SESSION['sps_importer']['import_type']['chains_selected'] = true;
   	      }
   	   } 
      }
/*	   if (!$_SESSION['sps_importer']['import_type']) {
	        $_SESSION['sps_importer']['import_type']['import_type_products_A_checked'] = 'checked';
	        $_SESSION['sps_importer']['import_type']['selected'] = 'products_A';
	   }
*/

		$this->load->language('tool/sps_import');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('tool/sps_import');
		
//      $this->load->model('store/product');
//      $this->model_store_product->createUnjunctionedProductRecords($_SESSION['store_code']);		

//KMC adding limit stuff.
      set_time_limit(0);
      ini_set('max_input_time', '0');
      ini_set('max_execution_time', '0');
/////

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
		    
        if ((isset( $this->request->files['upload'] )) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
			    
            // KMC for the export.tpl, to help track uploading.
            $_SESSION['uploaded_filename'] = $this->request->files['upload']['name'];

            // This is our CYA sql dump.
            $command = "/usr/bin/mysqldump -u prmsdb --password=h4eWs3Dd2 spsdb sps_state sps_district sps_school sps_user sps_chain >/var/www/html/catsolonline.com/prms-backoffice/trunk/db_migrations/db_backup_".date('m-d-Y-H-i-s');
            system($command);

				$file = $this->request->files['upload']['tmp_name'];
				$import_result = $this->model_tool_sps_import->upload($file, $import_types);

				if (!$import_result) {
				   $this->error['warning'] = $this->language->get('error_upload');
				} elseif (!empty($import_result['errors'])) {
				   $this->error['warning'] = $import_result['errors'];
				} else {
               //var_dump($import_result);
               //exit;
				   $this->data['success'] = $this->language->get('text_success');
               $this->data['results'] = $import_result;
				   //$this->redirect($this->url->http('tool/sps_import'));
				}
        } else { 
           echo ' too large to upload or empty filename?';exit; }
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['entry_restore'] = $this->language->get('entry_restore');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['button_import'] = $this->language->get('button_import');
		$this->data['button_export'] = $this->language->get('button_export');
		$this->data['button_template'] = $this->language->get('button_template');
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['error_warning'] = @$this->error['warning'];

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('tool/sps_import'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->data['action'] = $this->url->https('tool/sps_import');
      $this->data['export_action'] = $this->url->https('tool/sps_export');

		$this->id       = 'content';
		$this->template = 'tool/sps_import.tpl';
		$this->layout   = 'common/layout';
		$this->render();
		
	}


	private function validate() {
	    
		if (!$this->user->hasPermission('modify', 'tool/sps_import')) {
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
