<?php
class ControllerPaymentSPSPurchaseOrder extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('sps_button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->data['action'] = $this->url->https('payment/sps_purchase_order/callback');
		
		$this->data['error'] = (isset($this->session->data['error'])) ? $this->session->data['error'] : NULL;
		unset($this->session->data['error']);
		
		$this->load->language('payment/sps_purchase_order');
		$this->data['entry_po_number'] = $this->language->get('entry_po_number');
		
		$this->data['continue'] = $this->url->https('checkout/success');
		$this->data['back'] = $this->url->https('checkout/payment');
 	 		
		$this->id       = 'payment';

      // Pick up some default info for SPS system.
      $this->data['schoolname'] = $this->customer->getSPS()->getSchoolname();

      $this->data['purchase_order_accountnumber'] = '';
      $this->data['purchase_order_number'] = '';

      // We might be fixing an order, so check for po details already in the order.
      if (isset($this->session->data['fix_order'])) {
         $this->load->model('sps/order');
         $order_details = $this->model_sps_order->getOrder($_SESSION['store_code'], $this->session->data['fix_order']);
         if (count($order_details)) {
            //var_dump($order_details);
            $this->data['purchase_order_number'] = $order_details['po_number'];
            $this->data['purchase_order_accountnumber'] = $order_details['po_account_number'];
         }
      }

		//$this->template = $this->config->get('config_template') . 'payment/purchase_order.tpl';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/sps_purchase_order.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/sps_purchase_order.tpl';
        } else {
            $this->template = 'default/template/payment/sps_purchase_order.tpl';
        }
		
		$this->render();		 
	}
	
	public function callback() {
		
		$this->load->language('payment/sps_purchase_order');
		if ($this->request->post['purchase_order_number'] != '') {
			$this->load->model('payment/sps_purchase_order');
			//$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('purchase_order_order_status_id'));
			$this->model_payment_sps_purchase_order->confirm($this->session->data['order_id'], $this->config->get('sps_purchase_order_order_status_id'), '', $this->request->post['purchase_order_number']);
			$this->db->query("UPDATE `" . DB_PREFIX . "sps_order` SET `payment_method` = '" . $this->language->get('text_title') . "(" . $this->request->post['purchase_order_number'] . ")', `po_school_name`='" . $this->request->post['purchase_order_schoolname'] . "', `po_account_number` = '" . $this->request->post['purchase_order_accountnumber'] ."', `po_number`='" . $this->request->post['purchase_order_number'] . "' WHERE `order_id` = '" . $this->session->data['order_id'] . "'");

         if (isset($this->session->data['fix_order'])) {
            $this->load->model('sps/order');
            $this->model_sps_order->updateOrderStatusToPending($_SESSION['store_code'], $this->session->data['order_id']);
         }
			$this->redirect($this->url->https('checkout/success'));
		} else {
			$this->session->data['error'] = $this->language->get('error_po_number');
			$this->redirect($this->url->https('checkout/confirm'));
		}
	}
	
}
?>
