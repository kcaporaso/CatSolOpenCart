<?php

ini_set('display_errors',0);
class ControllerPaymentCCCapture extends Controller {
    
    
	protected function index() {
	    
    	$this->language->load('payment/cccapture');
		
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
		
      if ($this->customer->isSPS()) {
		   $this->data['button_confirm'] = $this->language->get('sps_button_confirm');
      } else {
		   $this->data['button_confirm'] = $this->language->get('button_confirm');
      }
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['cards'] = array();
      $cards = $this->config->get('cccapture_card_types');

      if(!empty($cards)){
         foreach(explode(',',$cards) as $v){
            $this->data['cards'][] = array(
               'text' => $this->language->get("card_{$v}"),
               'value' => $v
            );  
         }   
      } else {
   		$this->data['cards'][] = array(
   			'text'  => 'Visa', 
   			'value' => 'VISA'
   		);
   
   		$this->data['cards'][] = array(
   			'text'  => 'MasterCard', 
   			'value' => 'MASTERCARD'
   		);
   
   		$this->data['cards'][] = array(
   			'text'  => 'Discover Card', 
   			'value' => 'DISCOVER'
   		);
   		
   		$this->data['cards'][] = array(
   			'text'  => 'American Express', 
   			'value' => 'AMEX'
   		);
      }	

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
		$this->template = $this->config->get('config_template') . 'payment/cccapture.tpl';
		
		$this->render();	
			
	}

	
	public function send() {

	    /*
		if (!$this->config->get('cccapture_test')) {
			$api_endpoint = 'https://api-3t.paypal.com/nvp';
		} else {
			$api_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
		}
		
		if (!$this->config->get('cccapture_transaction')) {
			$payment_type = 'Authorization';	
		} else {
			$payment_type = 'Sale';
		}
		*/
		
	    /*
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($_SESSION['store_code'], $this->session->data['order_id']);
		
		$payment_address = $this->customer->getAddress($this->session->data['payment_address_id']);
		
		$payment_data = array(
			'METHOD'         => 'DoDirectPayment', 
			'VERSION'        => '51.0', 
			'PWD'            => $this->config->get('cccapture_password'),
			'USER'           => $this->config->get('cccapture_username'),
			'SIGNATURE'      => $this->config->get('cccapture_signature'),
			'CUSTREF'        => $order_info['order_id'],
			//'PAYMENTACTION'  => $payment_type,
			'AMT'            => $this->currency->format($order_info['total'], $order_info['currency'], 1.00000, FALSE),
			'CREDITCARDTYPE' => $this->request->post['cc_type'],
			'ACCT'           => str_replace(' ', '', $this->request->post['cc_number']),
			'CARDSTART'      => $this->request->post['cc_start_date_month'] . $this->request->post['cc_start_date_year'],
			'EXPDATE'        => $this->request->post['cc_expire_date_month'] . $this->request->post['cc_expire_date_year'],
			'CVV2'           => $this->request->post['cc_cvv2'],
			'CARDISSUE'      => $this->request->post['cc_issue'],
			'FIRSTNAME'      => $order_info['payment_firstname'],
			'LASTNAME'       => $order_info['payment_lastname'],
			'EMAIL'          => $order_info['email'],
			'PHONENUM'       => $order_info['telephone'],
			'IPADDRESS'      => $this->request->server['REMOTE_ADDR'],
			'STREET'         => $order_info['payment_address_1'],
			'CITY'           => $order_info['payment_city'],
			'STATE'          => $order_info['payment_zone'],
			'ZIP'            => $order_info['payment_postcode'],
			'COUNTRYCODE'    => $payment_address['iso_code_2'],
			'CURRENCYCODE'   => $order_info['currency']
		);
		
		*/
		
		/*
		$curl = curl_init($api_endpoint);
		
		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payment_data));

		$response = curl_exec($curl);
 		
		curl_close($curl);
 
		if (!$response) {
			exit('DoDirectPayment failed: ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
		}
 
 		$response_data = array();
 
		parse_str($response, $response_data);

		$json = array();
		
		if ($response_data['ACK'] == 'Success') {
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
			
			$this->model_checkout_order->update($_SESSION['store_code'], $this->session->data['order_id'], $this->config->get('cccapture_order_status_id'), $message, FALSE);
		
			$json['success'] = TRUE; 
		}
		
        if (($response_data['ACK'] != 'Success') && ($response_data['ACK'] != 'SuccessWithWarning')) {
        	$json['error'] = $response_data['L_LONGMESSAGE0'];
        }
        */

	    
       if ($this->customer->isSPS()) {
          $this->load->model('checkout/spsorder');
       } else {
	       $this->load->model('checkout/order');
       }
	    
	    $data['order_id'] = $this->session->data['order_id'];
	    $data['cc_type'] = $this->request->post['cc_type'];
	    $data['cc_number'] = trim($this->request->post['cc_number']);
	    $data['cc_expire_date_year'] = $this->request->post['cc_expire_date_year'];
	    $data['cc_expire_date_month'] = $this->request->post['cc_expire_date_month'];

       if ($this->customer->isSPS()) {
	       $data['is_pcard'] = (int)$this->request->post['is_pcard'];
          $data['po_number'] = $this->request->post['po_number'];
       }
	    
	    $data = $this->clean_up_ccinput($data);
	    
	    	    
	    if ($error = $this->validateCCInput($data)) {
	        
	        $json['error'] = $error;
	        
	    } else {
	    
          if ($this->customer->isSPS()) {
    	       $this->model_checkout_spsorder->confirm($_SESSION['store_code'], $this->session->data['order_id'], $this->config->get('config_order_status_id'));
          } else {
    	       $this->model_checkout_order->confirm($_SESSION['store_code'], $this->session->data['order_id'], $this->config->get('config_order_status_id'));
          }
    
    	    $data = $this->encrypt_cc_data($data);
    	    	    
          if ($this->customer->isSPS()) {
    	       $this->model_checkout_spsorder->insertCCCapture($data);
    	       $this->model_checkout_spsorder->update($_SESSION['store_code'], $this->session->data['order_id'], $this->config->get('cccapture_order_status_id'), null, FALSE);
          } else {
    	       $this->model_checkout_order->insertCCCapture($data);
    	       $this->model_checkout_order->update($_SESSION['store_code'], $this->session->data['order_id'], $this->config->get('cccapture_order_status_id'), null, FALSE);
          }
    	    
    		$json['success'] = TRUE;
	    }
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
		
	}
	
	
	public function clean_up_ccinput ($data) {
	    
        $data['cc_number'] = preg_replace("/[^0-9]/", "", $data['cc_number']);
        
        return $data;
	    
	}
	
	
	public function validateCCInput ($form_data) {
	    
		if ($form_data['cc_number']=='') {
	        
	        return 'Please enter a Credit Card Number.';
	        
		} elseif (($form_data['cc_number']!='') && (!is_numeric($form_data['cc_number']))) {
	        
	        return 'Credit Card Number must be numeric.';
	        
	    } elseif (!$this->luhn_check($form_data['cc_number'])) {
	        
	        return 'Invalid Credit Card Number detected.';
	        
       }
/*
	    if ($form_data['cc_cvv2']=='') {
	        
	        return 'Please enter a Card Security Code (CVV2).';
	        
		} elseif (($form_data['cc_cvv2']!='') && (!is_numeric($form_data['cc_cvv2']))) {
	        
	        return 'Card Security Code (CVV2) must be numeric.';
	        
	    } elseif (strlen($form_data['cc_cvv2']) > 4) {
	        
	        return 'Invalid Card Security Code (CVV2) detected.';
	        
	    }
*/	    
	    $data_yearmonth = ($form_data['cc_expire_date_year'].$form_data['cc_expire_date_month']);
	    $now_yearmonth = date('Ym');
            
	    if ($now_yearmonth > $data_yearmonth) {
	        return "Credit Card has expired.";
	    }
	    
	}
	
   public function luhn_check ($str) {
      if (strspn($str, "0123456789") != strlen($str)) {
         return false; // non-digit found
      }
      $map = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, // for even indices
                   0, 2, 4, 6, 8, 1, 3, 5, 7, 9); // for odd indices
      $sum = 0;
      $last = strlen($str) - 1;
      for ($i = 0; $i <= $last; $i++) {
         $sum += $map[$str[$last - $i] + ($i & 1) * 10];
      }
      return $sum % 10 == 0;
   }   

   public function luhn_check_OLD_ ($number) {
    
      // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
      $number=preg_replace('/\D/', '', $number);
    
      // Set the string length and parity
      $number_length=strlen($number);
      $parity=$number_length % 2;
    
      // Loop through each digit and do the maths
      $total=0;
      for ($i=0; $i<$number_length; $i++) {
        $digit=$number[$i];
        // Multiply alternate digits by two
        if ($i % 2 == $parity) {
          $digit*=2;
          // If the sum is two digits, add them together (in effect)
          if ($digit > 9) {
            $digit-=9;
          }
        }
        // Total up the digits
        $total+=$digit;
      }
    
      // If the total mod 10 equals 0, the number is valid
      return ($total % 10 == 0) ? TRUE : FALSE;
    
    }
    
    
    public function encrypt_cc_data ($data) {
        
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));        
        
        $data['cc_number'] = $encryption->encrypt($data['cc_number']);
        
        return $data;
        
    }
    	
	
}
?>
