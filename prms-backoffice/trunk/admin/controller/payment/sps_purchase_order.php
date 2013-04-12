<?php 
class ControllerPaymentSPSPurchaseOrder extends Controller {
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('payment/sps_purchase_order');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('sps_purchase_order', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->https('extension/payment'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
				
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {$this->data['error_warning'] = $this->error['warning'];}

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
       		'href'      => $this->url->https('payment/sps_purchase_order'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->https('payment/sps_purchase_order');

		$this->data['cancel'] = $this->url->https('extension/payment');	
		
		if (isset($this->request->post['sps_purchase_order_order_status_id'])) {
			$this->data['sps_purchase_order_order_status_id'] = $this->request->post['sps_purchase_order_order_status_id'];
		} else {
			$this->data['sps_purchase_order_order_status_id'] = $this->config->get('sps_purchase_order_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['sps_purchase_order_status'])) {
			$this->data['sps_purchase_order_status'] = $this->request->post['sps_purchase_order_status'];
		} else {
			$this->data['sps_purchase_order_status'] = $this->config->get('sps_purchase_order_status');
		}
		
		if (isset($this->request->post['sps_purchase_order_sort_order'])) {
			$this->data['sps_purchase_order_sort_order'] = $this->request->post['sps_purchase_order_sort_order'];
		} else {
			$this->data['sps_purchase_order_sort_order'] = $this->config->get('sps_purchase_order_sort_order');
		}
		
		$this->id       = 'content';
		$this->template = 'payment/sps_purchase_order.tpl';
		//Q: pre-1.3.3 Backwards compatibility check
        if ($this->config->get('config_guest_checkout')) {
            $this->children = array(
                'common/header',
                'common/menu',
                'common/footer'
            );
            $this->response->setOutput($this->render(TRUE));
        } else {
            $this->layout   = 'common/layout';
            $this->render();
        }
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/sps_purchase_order')) {
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