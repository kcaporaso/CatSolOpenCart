<?php 
class ControllerAccountCreate extends Controller {
	private $error = array();

   private function clean_store_name($in) {

      $wip = str_replace('&amp;', '&', $in);
      $wip = str_replace('&#039;', "'", $wip);
      return $wip;
   }         

  	public function index() {
		if ($this->customer->isLogged()) {
	  		$this->redirect($this->url->https('account/account'));
    	}

    	$this->language->load('account/create');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('account/customer');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_account_customer->addCustomer($_SESSION['store_code'], $this->request->post);
			
			$this->customer->login($_SESSION['store_code'], $this->request->post['email'], $this->request->post['password']);
	
			$subject = sprintf($this->language->get('mail_subject'), $this->clean_store_name($this->config->get('config_store')));
			
			$message  = sprintf($this->language->get('mail_line_1'), $this->clean_store_name($this->config->get('config_store'))) . "\n\n";
			$message .= $this->language->get('mail_line_2') . "\n";
			$message .= $this->url->https('account/login') . "\n\n";
			$message .= $this->language->get('mail_line_3') . "\n\n";
         $message .= sprintf($this->language->get('mail_line_5'), $this->config->get('config_telephone')) . "\n\n";
			$message .= $this->language->get('mail_line_4') . "\n";
			$message .= $this->clean_store_name($this->config->get('config_store'));
			
			$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));
			$mail->setTo($this->request->post['email']);
	  		$mail->setFrom($this->config->get('config_email'));
	  		$mail->setSender($this->clean_store_name($this->config->get('config_store')));
	  		$mail->setSubject($subject);
			$mail->setText($message);
      		$mail->send();
	  	  
	  		$this->redirect($this->url->https('account/success'));
    	} 

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('account/account'),
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('account/create'),
        	'text'      => $this->language->get('text_create'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
    	$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->https('account/login'));
    	$this->data['text_your_details'] = $this->language->get('text_your_details');
    	$this->data['text_your_address'] = $this->language->get('text_your_address');
    	$this->data['text_your_password'] = $this->language->get('text_your_password');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		$this->data['text_taxexempt'] = $this->language->get('text_taxexempt');
		$this->data['text_tax_note'] = $this->language->get('text_tax_note');
		$this->data['text_school_info'] = $this->language->get('text_school_info');
				
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_company'] = $this->language->get('entry_company');
    	$this->data['entry_address_1'] = $this->language->get('entry_address_1');
    	$this->data['entry_address_2'] = $this->language->get('entry_address_2');
    	$this->data['entry_postcode'] = $this->language->get('entry_postcode');
    	$this->data['entry_city'] = $this->language->get('entry_city');
    	$this->data['entry_country'] = $this->language->get('entry_country');
    	$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');

		$this->data['entry_schoolname'] = $this->language->get('entry_schoolname');
		$this->data['entry_taxid'] = $this->language->get('entry_taxid');

		$this->data['button_continue'] = $this->language->get('button_continue');
    
		$this->data['error_warning'] = @$this->error['warning'];
		$this->data['error_firstname'] = @$this->error['firstname'];
    	$this->data['error_lastname'] = @$this->error['lastname'];
    	$this->data['error_email'] = @$this->error['email'];
    	$this->data['error_telephone'] = @$this->error['telephone'];
    	$this->data['error_password'] = @$this->error['password'];
    	$this->data['error_confirm'] = @$this->error['confirm'];
    	$this->data['error_address_1'] = @$this->error['address_1'];
    	$this->data['error_postcode'] = @$this->error['postcode'];
    	$this->data['error_city'] = @$this->error['city'];

    	$this->data['action'] = $this->url->https('account/create');

    	$this->data['firstname'] = @$this->request->post['firstname'];
    	$this->data['lastname'] = @$this->request->post['lastname'];
    	$this->data['email'] = @$this->request->post['email'];
    	$this->data['telephone'] = @$this->request->post['telephone'];
    	$this->data['fax'] = @$this->request->post['fax'];
    	$this->data['company'] = @$this->request->post['company'];
    	$this->data['address_1'] = @$this->request->post['address_1'];
    	$this->data['address_2'] = @$this->request->post['address_2'];
    	$this->data['postcode'] = @$this->request->post['postcode'];
    	$this->data['city'] = @$this->request->post['city'];

    	if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
    	} else {
      		$this->data['country_id'] = $this->config->get('config_country_id');
    	}

    	if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id'];
    	} else {
      		$this->data['zone_id'] = 0;
    	}
		
		$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries($_SESSION['store_code']);
		
    	$this->data['password'] = @$this->request->post['password'];
    	$this->data['confirm'] = @$this->request->post['confirm'];
		$this->data['newsletter'] = @$this->request->post['newsletter'];

		if ($this->config->get('config_account')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($_SESSION['store_code'], $this->config->get('config_account'));
			
			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->http('information/information&information_id=' . $this->config->get('config_account')), $information_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}
		
      	$this->data['agree'] = @$this->request->post['agree'];
		
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'account/create.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();	
  	}

  	private function validate() {
    	if ((strlen(utf8_decode($this->request->post['firstname'])) < 3) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((strlen(utf8_decode($this->request->post['lastname'])) < 3) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if (!preg_match('#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#i', $this->request->post['email'])) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if ($this->model_account_customer->getTotalCustomersByEmail($_SESSION['store_code'], $this->request->post['email'])) {
      		$this->error['warning'] = $this->language->get('error_exists');
    	}

    	if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
      		$this->error['password'] = $this->language->get('error_password');
    	}

    	if ($this->request->post['confirm'] != $this->request->post['password']) {
      		$this->error['confirm'] = $this->language->get('error_confirm');
    	}

    	if ((strlen(utf8_decode($this->request->post['address_1'])) < 3) || (strlen(utf8_decode($this->request->post['address_1'])) > 128)) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}
  	
    	if ((strlen(utf8_decode($this->request->post['postcode'])) < 3) || (strlen(utf8_decode($this->request->post['postcode'])) > 128)) {
      		$this->error['postcode'] = $this->language->get('error_postcode');
    	}
    	
    	if ((strlen(utf8_decode($this->request->post['city'])) < 3) || (strlen(utf8_decode($this->request->post['city'])) > 128)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}

    	if ((strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone'])) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}
		
		if ($this->config->get('config_account')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($_SESSION['store_code'], $this->config->get('config_account'));
			
			if ($information_info) {
    			if (!@$this->request->post['agree']) {
      				$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
    			}
			}
		}
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}
  
  	public function zone() {	
    	$output = '<select name="zone_id">';

		$this->load->model('localisation/zone');

    	$results = $this->model_localisation_zone->getZonesByCountryId(@$this->request->get['country_id']);
        
      	foreach ($results as $result) {
        	$output .= '<option value="' . $result['zone_id'] . '"';
	
	    	if (@$this->request->get['zone_id'] == $result['zone_id']) {
	      		$output .= ' selected="selected"';
	    	}
	
	    	$output .= '>' . $result['name'] . '</option>';
    	} 
		
		if (!$results) {
		  	$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
    	}

    	$output .= '</select>';
	
		$this->response->setOutput($output);
  	}  
}
?>
