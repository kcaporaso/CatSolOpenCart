<?php 


class ModelPaymentCCCapture extends Model {
    
    
  	public function getMethod () {
  	    
		$this->load->language('payment/cccapture');
		
		if ($this->config->get('cccapture_status')) {
			$address = $this->customer->getAddress($this->session->data['payment_address_id']);
			
      		$query = $this->db->query("
      			SELECT * 
      			FROM " . DB_PREFIX . "zone_to_geo_zone as X,
      						geo_zone as GZ
      			WHERE 		1
      				AND		X.geo_zone_id = GZ.geo_zone_id
      				AND		X.geo_zone_id = '" . (int)$this->config->get('cccapture_geo_zone_id') . "' 
      				AND 	X.country_id = '" . (int)$address['country_id'] . "' 
      				AND 	(X.zone_id = '" . (int)$address['zone_id'] . "' OR X.zone_id = '0')
      				AND		GZ.store_code = '{$_SESSION['store_code']}'
      		");
			
			if (!$this->config->get('cccapture_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
      		  	$status = TRUE;
      		} else {
     	  		$status = FALSE;
			}	
      	} else {
			$status = FALSE;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'id'         => 'cccapture',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('cccapture_sort_order')
      		);
    	}
   
    	return $method_data;
    	
  	}
  	
  	
}
?>