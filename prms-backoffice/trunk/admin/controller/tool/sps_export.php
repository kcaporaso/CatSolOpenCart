<?php 

class ControllerToolSPSExport extends Controller { 
    
	private $error = array();
	
	public function index() {
	
		$this->load->language('tool/sps_export');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('tool/sps_export');
		
//KMC adding limit stuff.
      set_time_limit(0);
      ini_set('max_input_time', '0');
      ini_set('max_execution_time', '0');
/////

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['entry_restore'] = $this->language->get('entry_restore');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['button_import'] = $this->language->get('button_import');
		$this->data['button_export'] = $this->language->get('button_export');
		$this->data['button_template'] = $this->language->get('button_template');
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['error_warning'] = @$this->error['warning'];

      $this->data['export_action'] = $this->url->https('tool/sps_export/export_data');

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('tool/sps_import'),
			'text'      => 'Data Importer',
			'separator' => ' :: '
		);

		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('tool/sps_export'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		

		$this->id       = 'content';
		$this->template = 'tool/sps_export.tpl';
		$this->layout   = 'common/layout';
		$this->render();
		
	}


	public function export_data() {
	    
		if ($this->validate()) {

			// set appropriate memory and timeout limits
			ini_set("memory_limit","128M");
			set_time_limit( 1800 );

			// send the categories, products and options as a spreadsheet file
			$this->load->model('tool/sps_export');
			$this->model_tool_sps_export->download();

		} else {

			// return a HTTP 404 error
			return $this->forward('error/error_404', 'index');
		}
   }

	private function validate() {
	    
		if (!$this->user->hasPermission('modify', 'tool/sps_export')) {
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
