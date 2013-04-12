<?php 


class ModelShippingSubtotalbased extends Model {
    
    
  	public function getQuote($order_id=null, $cart_subtotal=0) {
	    
	   if ($order_id) {    // most likely called from back-end	        
	      $this->session->data['shipping_address_id'] = $this->session->data['order_id_'.$order_id]['shipping_address_id'];  
//       var_dump($this->session->data['shipping_address_id']);
	   }	    
  	    
		$this->load->language('shipping/subtotalbased', $_SESSION['iamthebackend']);
		
		$quote_data = array();
//var_dump($this->config->get('subtotalbased_status'));
		if ($this->config->get('subtotalbased_status')) {
		    
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name");
		
			$address = $this->customer->getAddress($this->session->data['shipping_address_id']);

         if (is_null($this->session->data['shipping_address_id'])) {
            // Grab a default address to estimate shipping charges in the cart.
            $this->session->data['shipping_address_id'] = $this->customer->getAddressId();
            if (!isset($this->session->data['shipping_address_id'])) {
               //echo 'still no address id';
               //TODO 
               // Strictly to get shipping when not logged in for Bender (HACK!)
               if (!$this->customer->isLogged() && $_SESSION['store_code'] == 'BND') {
                  // US and FL, it's all the same ship price.
                  $z = $this->db->query("SELECT zone_id FROM zone WHERE country_id='223' AND code='FL'");
                  if ($z->num_rows) {
                     $address['zone_id'] = $z->row['zone_id'];
                     $address['country_id'] = 223;
                  }
               }
            }
         }
         // SPS : We have to pick up our zone_id now based on zone a state; two letters.
//var_dump($this->customer->getId());
         if ($this->customer->isSPS()) {
            $z = $this->db->query("SELECT zone_id FROM zone WHERE country_id='{$address['country_id']}' AND code='{$address['zone']}'");
            if ($z->num_rows) {
               $address['zone_id'] = $z->row['zone_id'];
            }
         }
			foreach ($query->rows as $result) {
			    
   				if ($this->config->get('subtotalbased_' . $result['geo_zone_id'] . '_status')) {
//var_dump($result['geo_zone_id']);
   				    
   					$query = $this->db->query("
   						SELECT 		* 
   						FROM " . DB_PREFIX . "zone_to_geo_zone 
   						WHERE 		1
   							AND		geo_zone_id = '" . (int)$result['geo_zone_id'] . "' 
   							AND 	country_id = '" . (int)$address['country_id'] . "' 
   							AND 	(zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')
   					");
					if ($query->num_rows) {
       					$status = TRUE;
   					} else {
       					$status = FALSE;
   					}
   					
				} else {
					$status = FALSE;
				}

				if ($status) {
				    
					$effective_cost = 0;
               // KMC - Gift Certs - 07/27/2010
               // Give me the subtotal of items that ship.
               //if ($order_id) {
                  // If we have an order_id, then get the already placed order sub-total, which will contain
                  // discounts and the such. 
               //   $this->load->model('customer/order');
               //   $subtotal = $this->model_customer_order->getSubtotalForProducts($order_id);
               //} else {
					   //Future Gift Cert Work: $subtotal = floatval($this->cart->getShippableSubTotal());
                  if ($cart_subtotal) {
					      $subtotal = floatval($cart_subtotal);
                  } else {
					      $subtotal = floatval($this->cart->getSubTotal());
                  }
               //}
               //
					//Original, pre-GiftCert call : $subtotal = floatval($this->cart->getSubTotal());
				
					$rates = explode("\n", $this->config->get('subtotalbased_' . $result['geo_zone_id'] . '_rate'));
                    	
					$i = 0;
					foreach ($rates as $rate) {
					    
					    $i++;
					    
  						$data = explode(':', $rate);
  						
  						$threshold_value = trim($data[0]);
  						$fee_value = trim($data[1]);
                     		
						if (floatval($threshold_value) >= $subtotal || $i==count($rates)) {
						    
						    $fee_value_reversed = strrev($fee_value);
						    $strpos_percentsymbol_fee_value_reversed = strpos($fee_value_reversed, '%');
						    
						    if ($strpos_percentsymbol_fee_value_reversed !== false && $strpos_percentsymbol_fee_value_reversed == '0') {
    						    $effective_cost = $fee_value/100 * $subtotal;
						    } else {
						        $effective_cost = $fee_value;
						    }
						    
						    if ($this->config->get('subtotalbased_' . $result['geo_zone_id'] . '_minimum_charge_flag')) {
						        if ($effective_cost < $this->config->get('subtotalbased_' . $result['geo_zone_id'] . '_minimum_charge_amount')) {
						            $effective_cost = $this->config->get('subtotalbased_' . $result['geo_zone_id'] . '_minimum_charge_amount');
						        }
						    }
   						 break;
  						}
  						
					}
					
					if ((int)$effective_cost) {

                  /* KMC : 08/03/2010 : Override the effective_cost and use what we get from the original order
                   * placed.  This only happens from the admin order screen, hence order_id.
                   */
                  if ($order_id) {
                     $this->load->model('customer/order');
                     $effective_cost = $this->model_customer_order->getOrderShippingCharge($order_id);
                  }
                 
      				$quote_data['subtotalbased_' . $result['geo_zone_id']] = array(
        					'id'           => 'subtotalbased.subtotalbased_' . $result['geo_zone_id'],
        					'title'        => $result['name']." Shipping",
        					'cost'         => $effective_cost,
							'tax_class_id' => $this->config->get('subtotalbased_tax_class_id'),
        					'text'         => $this->currency->format($this->tax->calculate($effective_cost, $this->config->get('subtotalbased_tax_class_id'), $this->config->get('config_tax')))
      				);	

                  if (!isset($order_id) && $this->cart->hasExtraShippingItem()) { 
                     // Sneaking in the TBD stuff ? (THIS IS BETA CODE WATCH FOR CRUD!)
      				   $quote_data['subtotalbased_' . $result['geo_zone_id']] = array(
        					   'id'           => 'subtotalbased.subtotalbased_' . $result['geo_zone_id'],
        					   'title'        => $result['name']." Shipping",
        					   'cost'         => 'TBD',
							   'tax_class_id' => $this->config->get('subtotalbased_tax_class_id'),
        					   'text'         => 'TBD'
      					);	
                  }
               } else {
                  /* KMC : 08/03/2010 : Override the effective_cost and use what we get from the original order
                   * placed.  This only happens from the admin order screen, hence order_id.
                   */
                  if ($order_id) {
                     $this->load->model('customer/order');
                     $effective_cost = $this->model_customer_order->getOrderShippingCharge($order_id);
                     // Watch for TBD.
                     if ($effective_cost == 'TBD') {
      				      $quote_data['subtotalbased_' . $result['geo_zone_id']] = array(
        					      'id'           => 'subtotalbased.subtotalbased_' . $result['geo_zone_id'],
        					      'title'        => $result['name']." Shipping",
        					      'cost'         => 'TBD',
							      'tax_class_id' => $this->config->get('subtotalbased_tax_class_id'),
        					      'text'         => 'TBD'
      					   );	
                     }
                  }
               }
				}
			}
		}
		
		$method_data = array();
	
		if ($quote_data) {
      		$method_data = array(
        		'id'         => 'subtotalbased',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('subtotalbased_sort_order'),
        		'error'      => FALSE
      		);
		}

		return $method_data;
		
  	}
}
?>
