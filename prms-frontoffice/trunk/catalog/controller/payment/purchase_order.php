<?php
class ControllerPaymentPurchaseOrder extends Controller {
	protected function index() {
      if ($this->customer->isSPS()) {
    	   $this->data['button_confirm'] = $this->language->get('sps_button_confirm');
      } else {
    	   $this->data['button_confirm'] = $this->language->get('button_confirm');
      }
		$this->data['button_back'] = $this->language->get('button_back');

		$this->data['action'] = $this->url->https('payment/purchase_order/callback');
		
		$this->data['error'] = (isset($this->session->data['error'])) ? $this->session->data['error'] : NULL;
		unset($this->session->data['error']);
		
		$this->load->language('payment/purchase_order');
		$this->data['entry_po_number'] = $this->language->get('entry_po_number');
		
		$this->data['continue'] = $this->url->https('checkout/success');
		$this->data['back'] = $this->url->https('checkout/payment');
 	 		
		$this->id       = 'payment';

      // Pick up some default info for SPS system.
      if ($this->customer->isSPS()) {
         $this->data['schoolname'] = $this->customer->getSPS()->getSchoolname();
      }

		//$this->template = $this->config->get('config_template') . 'payment/purchase_order.tpl';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/purchase_order.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/purchase_order.tpl';
        } else {
            $this->template = 'default/template/payment/purchase_order.tpl';
        }
		
		$this->render();		 
	}
	
	public function callback() {
		
		$this->load->language('payment/purchase_order');
		if ($this->request->post['purchase_order_number'] != '') {
			$this->load->model('payment/purchase_order');
			//$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('purchase_order_order_status_id'));
			$this->model_payment_purchase_order->confirm($this->session->data['order_id'], $this->config->get('purchase_order_order_status_id'), '', $this->request->post['purchase_order_number']);
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `payment_method` = '" . $this->language->get('text_title') . " (" . $this->request->post['purchase_order_number'] . ")', `po_school_name`='" . $this->request->post['purchase_order_schoolname'] . "', `po_account_number` = '" . $this->request->post['purchase_order_accountnumber'] ."' WHERE `order_id` = '" . $this->session->data['order_id'] . "'");
			$this->redirect($this->url->https('checkout/success'));
		} else {
			$this->session->data['error'] = $this->language->get('error_po_number');
			$this->redirect($this->url->https('checkout/confirm'));
		}
	}
	
}
?>
