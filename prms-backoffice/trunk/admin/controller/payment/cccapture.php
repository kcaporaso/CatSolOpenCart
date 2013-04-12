<?php 


class ControllerPaymentCCCapture extends Controller {
    
    
	private $error = array(); 

	
	public function index() {
	    
		$this->load->language('payment/cccapture');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			if(is_array($this->request->post['cccapture_card_types'])){
				$this->request->post['cccapture_card_types'] = implode(',',$this->request->post['cccapture_card_types']);
			} else {
				$this->request->post['cccapture_card_types'] = null;	
			}
			
			$this->model_setting_setting->editSetting('cccapture', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->https('extension/payment'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_authorization'] = $this->language->get('text_authorization');
		$this->data['text_sale'] = $this->language->get('text_sale');
		
		$this->data['entry_username'] = $this->language->get('entry_username');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_signature'] = $this->language->get('entry_signature');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_transaction'] = $this->language->get('entry_transaction');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_card_types'] = $this->language->get('entry_card_types');
		
		$this->data['card_visa'] = $this->language->get('card_visa');
		$this->data['card_amex'] = $this->language->get('card_amex');
		$this->data['card_discover'] = $this->language->get('card_discover');
		$this->data['card_mastercard'] = $this->language->get('card_mastercard');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['error_warning'] = @$this->error['warning'];
		$this->data['error_username'] = @$this->error['username'];
		$this->data['error_password'] = @$this->error['password'];
		$this->data['error_signature'] = @$this->error['signature'];

		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('extension/payment'),
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('payment/cccapture'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->https('payment/cccapture');
		
		$this->data['cancel'] = $this->url->https('extension/payment');
		
		if (isset($this->request->post['cccapture_order_status_id'])) {
			$this->data['cccapture_order_status_id'] = $this->request->post['cccapture_order_status_id'];
		} else {
			$this->data['cccapture_order_status_id'] = $this->config->get('cccapture_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['cccapture_geo_zone_id'])) {
			$this->data['cccapture_geo_zone_id'] = $this->request->post['cccapture_geo_zone_id'];
		} else {
			$this->data['cccapture_geo_zone_id'] = $this->config->get('cccapture_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones($_SESSION['store_code']);
		
		if (isset($this->request->post['cccapture_status'])) {
			$this->data['cccapture_status'] = $this->request->post['cccapture_status'];
		} else {
			$this->data['cccapture_status'] = $this->config->get('cccapture_status');
		}
		
		if (isset($this->request->post['cccapture_sort_order'])) {
			$this->data['cccapture_sort_order'] = $this->request->post['cccapture_sort_order'];
		} else {
			$this->data['cccapture_sort_order'] = $this->config->get('cccapture_sort_order');
		}
		
		if (isset($this->request->post['cccapture_card_types'])) {
			$this->data['cccapture_card_types'] = explode(',',$this->request->post['cccapture_card_types']);
		} else {
			$this->data['cccapture_card_types'] = explode(',',$this->config->get('cccapture_card_types'));
		}
		
		$this->id       = 'content';
		$this->template = 'payment/cccapture.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();
 		
	}

	
	private function validate() {
	    
		if (!$this->user->hasPermission('modify', 'payment/cccapture')) {
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