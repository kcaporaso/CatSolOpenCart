<?php


class ControllerShippingSubtotalbased extends Controller {
    
    
	private $error = array();
	
	
	public function index() {  
	    
		$this->load->language('shipping/subtotalbased');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate($this->request->post))) {
			$this->model_setting_setting->editSetting('subtotalbased', $this->request->post);	

			$this->session->data['success'] = $this->language->get('text_success');
									
			$this->redirect($this->url->https('extension/shipping'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_rate'] = $this->language->get('entry_rate');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
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
       		'href'      => $this->url->https('shipping/subtotalbased'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->https('shipping/subtotalbased');
		
		$this->data['cancel'] = $this->url->https('extension/shipping'); 

		$this->load->model('localisation/geo_zone');
		
		$geo_zones = $this->model_localisation_geo_zone->getGeoZones($_SESSION['store_code']);
		
		foreach ($geo_zones as $geo_zone) {
		    
			if (isset($this->request->post['subtotalbased_' . $geo_zone['geo_zone_id'] . '_rate'])) {
				$this->data['subtotalbased_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->request->post['subtotalbased_' . $geo_zone['geo_zone_id'] . '_rate'];
			} else {
				$this->data['subtotalbased_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->config->get('subtotalbased_' . $geo_zone['geo_zone_id'] . '_rate');
			}		
			
			if (isset($this->request->post['subtotalbased_' . $geo_zone['geo_zone_id'] . '_status'])) {
				$this->data['subtotalbased_' . $geo_zone['geo_zone_id'] . '_status'] = $this->request->post['subtotalbased_' . $geo_zone['geo_zone_id'] . '_status'];
			} else {
				$this->data['subtotalbased_' . $geo_zone['geo_zone_id'] . '_status'] = $this->config->get('subtotalbased_' . $geo_zone['geo_zone_id'] . '_status');
			}
			
			if (isset($this->request->post['subtotalbased_' . $geo_zone['geo_zone_id'] . '_minimum_charge_flag'])) {
				$this->data['subtotalbased_' . $geo_zone['geo_zone_id'] . '_minimum_charge_flag'] = $this->request->post['subtotalbased_' . $geo_zone['geo_zone_id'] . '_minimum_charge_flag'];
			} else {
				$this->data['subtotalbased_' . $geo_zone['geo_zone_id'] . '_minimum_charge_flag'] = $this->config->get('subtotalbased_' . $geo_zone['geo_zone_id'] . '_minimum_charge_flag');
			}

			if (isset($this->request->post['subtotalbased_' . $geo_zone['geo_zone_id'] . '_minimum_charge_amount'])) {
				$this->data['subtotalbased_' . $geo_zone['geo_zone_id'] . '_minimum_charge_amount'] = $this->request->post['subtotalbased_' . $geo_zone['geo_zone_id'] . '_minimum_charge_amount'];
			} else {
				$this->data['subtotalbased_' . $geo_zone['geo_zone_id'] . '_minimum_charge_amount'] = $this->config->get('subtotalbased_' . $geo_zone['geo_zone_id'] . '_minimum_charge_amount');
			}
					
		}
		
		$this->data['geo_zones'] = $geo_zones;

		if (isset($this->request->post['subtotalbased_tax_class_id'])) {
			$this->data['subtotalbased_tax_class_id'] = $this->request->post['subtotalbased_tax_class_id'];
		} else {
			$this->data['subtotalbased_tax_class_id'] = $this->config->get('subtotalbased_tax_class_id');
		}
		
		if (isset($this->request->post['subtotalbased_status'])) {
			$this->data['subtotalbased_status'] = $this->request->post['subtotalbased_status'];
		} else {
			$this->data['subtotalbased_status'] = $this->config->get('subtotalbased_status');
		}
		
		if (isset($this->request->post['subtotalbased_sort_order'])) {
			$this->data['subtotalbased_sort_order'] = $this->request->post['subtotalbased_sort_order'];
		} else {
			$this->data['subtotalbased_sort_order'] = $this->config->get('subtotalbased_sort_order');
		}	
		
		$this->load->model('localisation/tax_class');
				
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses($_SESSION['store_code']);
								
		$this->id       = 'content';
		$this->template = 'shipping/subtotalbased.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();
 		
	}
		
	
	private function validate ($form_data) {
	    
		if (!$this->user->hasPermission('modify', 'shipping/subtotalbased')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		foreach ($form_data as $field_name => $field_value) {
    		if (strstr($field_name,'_minimum_charge_amount') && !is_numeric($field_value)) {
    		    $this->error['warning'] = "A Minimum Charge Amount must be numeric.";
    		}
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
		
	}
	
	
}
?>