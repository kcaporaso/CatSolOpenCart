<?php

class ModelShippingLocalpickup extends Model {
    
	function getQuote($order_id=null) {	    
	    
		$this->load->language('shipping/localpickup', $_SESSION['iamthebackend']);

  		if ($this->config->get('localpickup_status') && $this->store_has_locations($_SESSION['store_code'])) {
    		$status = TRUE;
  		}

		$method_data = array();
	
		if ($status) {
		    
		    $storelocations = (array) $this->db->get_multiple('storelocation', "store_code = '{$_SESSION['store_code']}'");
   
			$quote_data = array();
			
			foreach ($storelocations as $storelocation) {
			
          		$quote_data['localpickup_'.$storelocation['id']] = array(
            		'id'           => 'localpickup.localpickup_'.$storelocation['id'],
            		'title'        => $this->language->get('text_description')." -- ".$storelocation['name'],
            		'cost'         => $this->get_storelocation_localpickup_fee($storelocation['id']),
            		'tax_class_id' => '',
    				'text'         => $this->currency->format($this->get_storelocation_localpickup_fee($storelocation['id']))
          		);
      		
			}

      		$method_data = array(
        		'id'         => 'localpickup',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('localpickup_sort_order'),
        		'error'      => FALSE
      		);
		}

		return $method_data;
	}
	
	
	public function store_has_locations ($store_code) {
	    
	    return (boolean) $this->db->get_multiple('storelocation', "store_code = '{$store_code}'");
	    
	}
	
	
	public function get_storelocation_localpickup_fee ($storelocation_id) {
	    
	    return $this->db->get_column('storelocation', 'localpickup_fee', "id = '{$storelocation_id}'");
	    
	}
	
	
}
?>