<?php
class ControllerPaymentSkipPayment extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('sps_button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->data['action'] = $this->url->https('payment/skip_payment/callback');
		
		$this->data['error'] = (isset($this->session->data['error'])) ? $this->session->data['error'] : NULL;
		unset($this->session->data['error']);
		
		$this->load->language('payment/skip_payment');
		
		$this->data['continue'] = $this->url->https('checkout/success');
		$this->data['back'] = $this->url->https('checkout/payment');
 	 	$this->data['skip_payment_message'] = $this->language->get('skip_payment_message');	
		$this->id       = 'payment';

      // Pick up some default info for SPS system.
      //$this->data['schoolname'] = $this->customer->getSPS()->getSchoolname();

		//$this->template = $this->config->get('config_template') . 'payment/purchase_order.tpl';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/skip_payment.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/skip_payment.tpl';
        } else {
            $this->template = 'default/template/payment/skip_payment.tpl';
        }
		
		$this->render();		 
	}
	
	public function callback() {
		$this->load->language('payment/skip_payment');
      $this->load->model('payment/skip_payment');
		$this->model_payment_skip_payment->confirm($this->session->data['order_id'], $this->config->get('skip_payment_order_status_id'), '');
      $payment_method = $this->language->get('text_title');
      if (isset($this->session->data['payment_method']['title_short'])) {
         $payment_method = $this->session->data['payment_method']['title_short'];
      }
	   $this->db->query("UPDATE `" . DB_PREFIX . "sps_order` SET `payment_method` = '" . $payment_method . "' WHERE `order_id` = '" . $this->session->data['order_id'] . "'");

      $this->load->model('sps/order');
      $this->model_sps_order->updateOrderStatusToPending($_SESSION['store_code'], $this->session->data['order_id']);
		$this->redirect($this->url->https('checkout/success'));
	}
}
?>
