<?php


class ModelTotalShipping extends Model {
    
    
	public function getTotal(&$total_data, &$total, &$taxes, $order_id=null) {
	    
		if ($order_id) {    // most likely called from back-end        
	        $this->session->data['shipping_method'] = $this->session->data['order_id_'.$order_id]['shipping_method'];	        
           // Let's check to see if we have an "Shipping Adjustments" back here.
           //
           if (isset($this->request->post['shipping_adjustment'])) {
              $ship_adj = $this->request->post['shipping_adjustment'];
              if ($ship_adj) {  // none 0
                 // We have to push this into the "total_data" arrray to get included.
                 $total_data[] = array(
                           'title' => 'Shipping Adjustment:',
                           'text' => $this->currency->format($ship_adj),
                           'value' => $ship_adj,
                           'sort_order' => $this->config->get('shipping_sort_order') + 1
                           );
                 $total += $ship_adj;
              }
           }
	    }	    
	    
		if (($this->cart->hasShipping()) && ($this->config->get('shipping_status'))) {
		    
			$total_data[] = array( 
        		'title'      => $this->session->data['shipping_method']['title'] . ':',
            'text'       => is_numeric($this->session->data['shipping_method']['cost']) ? $this->currency->format($this->session->data['shipping_method']['cost']) : $this->session->data['shipping_method']['cost'],
        		'value'      => $this->session->data['shipping_method']['cost'],
				'sort_order' => $this->config->get('shipping_sort_order')
			);

/*	KMC 6/11/2010
 *	We don't tax shipping!
 *	I don't understand this!
 *	  		if ($this->session->data['shipping_method']['tax_class_id']) {
				if (!isset($taxes[$this->session->data['shipping_method']['tax_class_id']])) {
					$taxes[$this->session->data['shipping_method']['tax_class_id']] = $this->session->data['shipping_method']['cost'] / 100 * $this->tax->getRate($this->session->data['shipping_method']['tax_class_id']);
				} else {
					$taxes[$this->session->data['shipping_method']['tax_class_id']] += $this->session->data['shipping_method']['cost'] / 100 * $this->tax->getRate($this->session->data['shipping_method']['tax_class_id']);
				}
			}
 */
			
			$total += $this->session->data['shipping_method']['cost'];
			
		}	
				
	}
	
}
?>
