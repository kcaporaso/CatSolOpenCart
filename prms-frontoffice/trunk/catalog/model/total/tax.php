<?php

class ModelTotalTax extends Model {
    
    
	public function getTotal(&$total_data, &$total, &$taxes, $order_id=null) {
	    
		if ($this->config->get('tax_status')) {
		     
			foreach ((array)$taxes as $key => $value) {
			    
				if ($value > 0) {
	    	   		$total_data[] = array(
	    				'title'      => $this->tax->getDescription($key) . ':', 
	    				'text'       => $this->currency->format($value),
	    				'value'      => $value,
						'sort_order' => $this->config->get('tax_sort_order')
	    			);
			
					$total += $value;
				}
				
			}
			
		}
		
	}
	
	
}
?>