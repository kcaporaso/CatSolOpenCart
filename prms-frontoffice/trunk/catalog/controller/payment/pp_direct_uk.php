<?php
class ControllerPaymentPPDirectUK extends Controller {
	protected function index() {
		$this->language->load('payment/pp_direct_uk');
		 
		$this->data['text_credit_card'] = $this->language->get('text_credit_card');
		$this->data['text_start_date'] = $this->language->get('text_start_date');
		$this->data['text_issue'] = $this->language->get('text_issue');
		$this->data['text_wait'] = $this->language->get('text_wait');
		
		$this->data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
		$this->data['entry_cc_type'] = $this->language->get('entry_cc_type');
		$this->data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$this->data['entry_cc_start_date'] = $this->language->get('entry_cc_start_date');
		$this->data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$this->data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		$this->data['entry_cc_issue'] = $this->language->get('entry_cc_issue');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($_SESSION['store_code'], $this->session->data['order_id']);
		
		$this->data['owner'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		
		$this->data['cards'] = array();

		$this->data['cards'][] = array(
			'text'  => 'Visa', 
			'value' => '0'
		);

		$this->data['cards'][] = array(
			'text'  => 'MasterCard', 
			'value' => '1'
		);

		$this->data['cards'][] = array(
			'text'  => 'Maestro', 
			'value' => '9'
		);
		
		$this->data['cards'][] = array(
			'text'  => 'Solo', 
			'value' => 'S'
		);		
	
		$this->data['months'] = array();
		
		for ($i = 1; $i <= 12; $i++) {
			$this->data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)), 
				'value' => sprintf('%02d', $i)
			);
		}
		
		$today = getdate();
		
		$this->data['year_valid'] = array();
		
		for ($i = $today['year'] - 10; $i < $today['year'] + 1; $i++) {	
			$this->data['year_valid'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)), 
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		$this->data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$this->data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
			);
		}
		
		$this->data['back'] = $this->url->https('checkout/payment');
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/pp_direct_uk.tpl';
		
		$this->render();		
	}

	public function send() {
		$this->language->load('payment/pp_direct_uk');
		
		if (!$this->config->get('pp_direct_uk_test')) {
			$api_url = 'https://payflowpro.verisign.com/transaction';
		} else {
			$api_url = 'https://pilot-payflowpro.verisign.com/transaction';
		}
		
		if (!$this->config->get('pp_direct_uk_transaction')) {
			$payment_type = 'A';	
		} else {
			$payment_type = 'S';
		}
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($_SESSION['store_code'], $this->session->data['order_id']);
		
		$payment_address = $this->customer->getAddress($this->session->data['payment_address_id']);
		
		$payment_data = array(
			'USER'      => $this->config->get('pp_direct_uk_user'),
			'VENDOR'    => $this->config->get('pp_direct_uk_vendor'),
			'PARTNER'   => $this->config->get('pp_direct_uk_partner'),
			'PWD'       => $this->config->get('pp_direct_uk_password'),
			'TENDER'    => 'C',
			'TRXTYPE'   => $payment_type,
			'AMT'       => $this->currency->format($order_info['total'], $order_info['currency'], 1.00000, FALSE),
			'CURRENCY'  => $order_info['currency'],
			'NAME'      => $this->request->post['cc_owner'],
			'STREET'    => $order_info['payment_address_1'],
			'CITY'      => $order_info['payment_city'],
            'STATE'     => $order_info['payment_zone'],
			'COUNTRY'   => $payment_address['iso_code_2'],
			'ZIP'       => $order_info['payment_postcode'],
			'CLIENTIP'  => $this->request->server['REMOTE_ADDR'],
			'EMAIL'     => $order_info['email'],
            'ACCT'      => str_replace(' ', '', $this->request->post['cc_number']),
            'ACCTTYPE'  => $this->request->post['cc_type'],
            'CARDSTART' => $this->request->post['cc_start_date_month'] . $this->request->post['cc_start_date_year'],
            'EXPDATE'   => $this->request->post['cc_expire_date_month'] . $this->request->post['cc_expire_date_year'],
            'CVV2'      => $this->request->post['cc_cvv2'],
			'CARDISSUE' => $this->request->post['cc_issue']
		);
		
		$curl = curl_init($api_url);

		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payment_data));
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-VPS-REQUEST-ID: ' . md5($this->session->data['order_id'] . rand())));

		$response = curl_exec($curl);
  		
		curl_close($curl);
 
 		$response_data = array();
 
		parse_str($response, $response_data);

		$json = array();

		if ($response_data['RESULT'] == '0') {
			$this->model_checkout_order->confirm($_SESSION['store_code'], $this->session->data['order_id'], $this->config->get('config_order_status_id'));
			
			$message = '';
			
			if (isset($response_data['AVSCODE'])) {
				$message .= 'AVSCODE: ' . $response_data['AVSCODE'] . "\n";
			}

			if (isset($response_data['CVV2MATCH'])) {
				$message .= 'CVV2MATCH: ' . $response_data['CVV2MATCH'] . "\n";
			}

			if (isset($response_data['TRANSACTIONID'])) {
				$message .= 'TRANSACTIONID: ' . $response_data['TRANSACTIONID'] . "\n";
			}
			
			$this->model_checkout_order->update($_SESSION['store_code'], $this->session->data['order_id'], $this->config->get('pp_direct_uk_order_status_id'), $message, FALSE);
		
			$json['success'] = TRUE; 
		} else {
			switch ($response_data['RESULT']) {
				case '1':
				case '26':
					$json['error'] = $this->language->get('error_config');
					break;
				case '7':
					$json['error'] = $this->language->get('error_address');
					break;
				case '12':
					$json['error'] = $this->language->get('error_declined');
					break;
				case '23':
				case '24':
					$json['error'] = $this->language->get('error_invalid');
					break;
				default:
					$json['error'] = $this->language->get('error_general');
					break;
			}		
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
	}
}
?>