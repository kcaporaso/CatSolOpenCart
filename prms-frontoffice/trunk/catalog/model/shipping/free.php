<?php
class ModelShippingFree extends Model {
    
	function getQuote($order_id=null, $subtotal=0, $hasfree=0) {
	    
		if ($order_id) {    // most likely called from back-end	        
	        $this->session->data['shipping_address_id'] = $this->session->data['order_id_'.$order_id]['shipping_address_id'];  
	    }	    
	    
		$this->load->language('shipping/free', $_SESSION['iamthebackend']);
		
		if ($this->config->get('free_status')) {
			$address = $this->customer->getAddress($this->session->data['shipping_address_id']);
			
     		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('free_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	 	
     		if (!$this->config->get('free_geo_zone_id')) {
        		$status = TRUE;
     		} elseif ($query->num_rows) {
        		$status = TRUE;
     		} else {
        		$status = FALSE;
     		}

		} else {
			$status = FALSE;
		}

      if ($subtotal) {
         if ($subtotal < $this->config->get('free_total')) {
			   $status = FALSE;
         }
      } else {
		   if ($this->cart->getSubTotal() < $this->config->get('free_total')) {
			   $status = FALSE;
		   }
      }

      if ($hasfree) { $status = true; } // hack to override to give free shipping.
		
		$method_data = array();
	
		if ($status) {
			$quote_data = array();
			
      		$quote_data['free'] = array(
        		'id'           => 'free.free',
        		'title'        => $this->language->get('text_description'),
        		'cost'         => 0.00,
        		'tax_class_id' => 0,
				'text'         => $this->currency->format(0.00)
      		);

      		$method_data = array(
        		'id'         => 'free',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('free_sort_order'),
        		'error'      => FALSE
      		);
		}
	
		return $method_data;
	}

}
?>
