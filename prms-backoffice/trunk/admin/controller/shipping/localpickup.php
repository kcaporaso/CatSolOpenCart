<?php

class ControllerShippingLocalpickup extends Controller {
    
	private $error = array(); 
	
	public function index() {   
	    
		$this->load->language('shipping/localpickup');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
		    
			$this->model_setting_setting->editSetting('localpickup', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->https('extension/shipping'));
			
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		
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
       		'href'      => $this->url->https('extension/shipping'),
       		'text'      => $this->language->get('text_shipping'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('shipping/localpickup'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->https('shipping/localpickup');
		
		$this->data['cancel'] = $this->url->https('extension/shipping');
	
		
		if (isset($this->request->post['localpickup_status'])) {
			$this->data['localpickup_status'] = $this->request->post['localpickup_status'];
		} else {
			$this->data['localpickup_status'] = $this->config->get('localpickup_status');
		}
		
		if (isset($this->request->post['localpickup_sort_order'])) {
			$this->data['localpickup_sort_order'] = $this->request->post['localpickup_sort_order'];
		} else {
			$this->data['localpickup_sort_order'] = $this->config->get('localpickup_sort_order');
		}				

		$this->id       = 'content';
		$this->template = 'shipping/localpickup.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();
	}
	
	
	private function validate() {
	    
		if (!$this->user->hasPermission('modify', 'shipping/localpickup')) {
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