<?php
class ControllerModuleSearch extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/search');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('search', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->https('extension/module'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		
		$this->data['entry_ajax'] = $this->language->get('entry_ajax');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['error_warning'] = @$this->error['warning'];

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('extension/module'),
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('module/search'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->https('module/search');
		
		$this->data['cancel'] = $this->url->https('extension/module');

		if (isset($this->request->post['search_position'])) {
			$this->data['search_position'] = $this->request->post['search_position'];
		} else {
			$this->data['search_position'] = $this->config->get('search_position');
		}
		
		if (isset($this->request->post['search_status'])) {
			$this->data['search_status'] = $this->request->post['search_status'];
		} else {
			$this->data['search_status'] = $this->config->get('search_status');
		}
		
		if (isset($this->request->post['search_sort_order'])) {
			$this->data['search_sort_order'] = $this->request->post['search_sort_order'];
		} else {
			$this->data['search_sort_order'] = $this->config->get('search_sort_order');
		}				
		
		$this->id       = 'content';
		$this->template = 'module/search.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/search')) {
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
