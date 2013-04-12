<?php


class ModelTotalSubTotal extends Model {
    
	public function getTotal(&$total_data, &$total, &$taxes, $order_id=null) {
		if ($this->config->get('sub_total_status')) {
			$this->load->language('total/sub_total', $_SESSION['iamthebackend']);
			
			$total_data[] = array( 
        		'title'      => $this->language->get('text_sub_total'),
        		'text'       => $this->currency->format($this->cart->getSubTotal()),
        		'value'      => $this->cart->getSubTotal(),
				'sort_order' => $this->config->get('sub_total_sort_order')
			);
			
			$total += $this->cart->getSubTotal();
		}
	}
	
}


?>