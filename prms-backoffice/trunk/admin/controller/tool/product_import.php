<?php
class ControllerToolProductImport extends Controller { 
	private $error = array();
	
	public function index(){
		//Step 1: Upload Products
		$this->setupCommonViewVars();
		$this->data['heading_step1'] = $this->language->get('heading_step1');
		$this->data['entry_upload'] = $this->language->get('entry_upload');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['heading_upload_format'] = $this->language->get('heading_upload_format');
		$this->data['link_samplexls'] = $this->language->get('link_samplexls');
		$this->data['action'] = $this->url->https('tool/product_import/step2');
		$this->data['error_warning'] = @$this->error['warning'];


		// Render it up
		$this->id       = 'content';
		$this->template = 'tool/product_import.tpl';
		$this->layout   = 'common/layout';
		$this->render();
	}
	
	public function step2(){
		// Step 2: Save Product Data to session and prompt to Upload Images
		$this->setupCommonViewVars();
		$this->data['heading_step2'] = $this->language->get('heading_step2');
		$this->data['entry_step2'] = $this->language->get('entry_step2');
		$this->data['button_next'] = $this->language->get('button_next');
		$this->data['action_mediaupload'] = $this->url->https('tool/product_import/upload_media');
		$this->data['action_step3'] = $this->url->https('tool/product_import/step3');
		
		// Get upload and verify data
		$this->load->model('tool/product_import');
		// Have Post
		if (($this->request->server['REQUEST_METHOD'] === 'POST') && ($this->validate())) {
			if( $this->model_tool_product_import->isValidUpload($this->request->files['upload']) ){
				$file_parts = pathinfo($this->request->files['upload']['name']);
				switch($file_parts['extension']):
					case "xls":
						$this->session->data['uploaded_products'] = $this->model_tool_product_import->getUploadProductDataXLS($this->request->files['upload']);
						break;
					case "txt":
						// Fall back for anyone who cant use an excel file.  We prefer the excel, so this feature is not advertised.
						$this->session->data['uploaded_products'] = $this->model_tool_product_import->getUploadProductDataCSV($this->request->files['upload']);
						break;
					default:
						// Send errors to session and redirect to step1
						$this->error['warning'] = 'Incorrect Data File.  Please use Excel 97-2003 format Excel file!';
						$this->errorRedirect();
						break;
				endswitch;
			} else {
				// Send errors to session and redirect to step1
				$this->error['warning'] = 'Upload Failed!' . $this->model_tool_product_import->uploadHasErrors($this->request->files['upload']);
				$this->errorRedirect();
			}
		} elseif(isset($this->session->data['uploaded_products'])){
			// No post data, but have session data.  Assume they came back to upload more images. 
		} else {
			// No post data, No session data.  Send off to step 1
			$this->errorRedirect();
		}
		
		// Render it up
		$this->id       = 'content';
		$this->template = 'tool/product_import_step2.tpl';
		$this->layout   = 'common/layout';
		$this->render();
		
	}
	
	public function step3(){
		// Step 3: Verify
		$this->setupCommonViewVars();
		$this->load->model('tool/product_import');
		$this->data['heading_step3'] = $this->language->get('heading_step3');
		$this->data['entry_step3'] = $this->language->get('entry_step3');
		$this->data['action_step1'] = $this->url->https('tool/product_import');
		$this->data['action_step2'] = $this->url->https('tool/product_import/step2');
		$this->data['action_step4'] = $this->url->https('tool/product_import/finalize');
		$this->data['button_fixdata'] = $this->language->get('button_fixdata');
		$this->data['button_fiximg'] = $this->language->get('button_fiximg');
		$this->data['button_finish'] = $this->language->get('button_finish');
		$this->data['products'] = $this->session->data['uploaded_products'];
		$this->data['validation'] = $this->model_tool_product_import->validateImport( $this->session->data['uploaded_products']);
		$this->data['error_validation'] = $this->language->get('error_validation');
		// Render it up
		$this->id       = 'content';
		$this->template = 'tool/product_import_step3.tpl';
		$this->layout   = 'common/layout';
		$this->render();
	}
	
	public function finalize(){
		// Step 4: Finalize.  One last check and its off to the save.
		$this->load->model('tool/product_import');
		$valid = $this->model_tool_product_import->validateImport($this->session->data['uploaded_products']);
		if($valid['success'] === true){
			$this->model_tool_product_import->saveImport($this->session->data['uploaded_products']);
			unset($this->session->data['uploaded_products']);		
		} else {
			$this->redirect($this->url->http('tool/product_import/step3'));
		}
		$this->setupCommonViewVars();
		$this->data['heading_final'] = $this->language->get('heading_final');
		$this->data['entry_final'] = $this->language->get('entry_final');
		// Render it up
		$this->id       = 'content';
		$this->template = 'tool/product_import_final.tpl';
		$this->layout   = 'common/layout';
		$this->render();
	}
	
	public function upload_media(){
		// Have Post
		if (($this->request->server['REQUEST_METHOD'] === 'POST') && ($this->validate())) {

			$this->load->model('tool/product_import');
			// Ajax JSON response
			echo $this->model_tool_product_import->processUploadedMedia();
		}
		exit();
	}
	
	private function errorRedirect() {
		// Send errors to session and redirect to step1
		$this->session->data['warning'] = $this->error['warning'];
		$this->redirect($this->url->http('tool/product_import'));
		exit();
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'tool/product_import')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!array_key_exists('store_code',$_SESSION)){
			$this->error['warning'] = $this->language->get('error_nostore');
		}
		$this->load->model('user/user');
		$this->load->model('user/store');
		$user = $this->model_user_user->getUser($this->model_user_store->getOwnerUserIDFromCode($_SESSION['store_code']));
		//print_r($user);exit();
	    if(!($user['membershiptier_id'] >= 2)){
			$this->error['warning'] = $this->language->get('error_nogold');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
			
	}
	
	private function setupCommonViewVars() {
		// Errors across redirects
		if(isset($_SESSION['warning'])){
			$this->error['warning'] = $_SESSION['warning'];
			unset($_SESSION['warning']);
		}
		// Localization
		$this->load->language('tool/product_import');
		$this->document->title = $this->language->get('heading_title');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['button_import'] = $this->language->get('button_import');
		// Breadcrumbs
		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('tool/product_import'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		
	}
}
?>