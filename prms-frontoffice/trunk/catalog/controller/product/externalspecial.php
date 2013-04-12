<?php 
class ControllerProductExternalSpecial extends Controller { 	
	public function index() { 
    	$this->language->load('product/special');
	  	  
      $store_code = $this->request->get['sc'];
		$url = '';
		
		$this->load->model('catalog/product');
			
		$product_total = $this->model_catalog_product->getTotalProductSpecials($store_code);
						
		if ($product_total) {
			$url = '';
				
			$this->load->model('catalog/review');
			$this->load->model('tool/seo_url');
			
			$this->load->helper('image');
				
       	$this->data['products'] = array();
				
			$results = $this->model_catalog_product->getProductSpecials($store_code, $sort, $order, 0, 1);
        		
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'no_image.jpg';
				}						
					
			   $this->data['products'][] = array(
						'name' => $this->language->clean_string($result['name']),
							'ext_product_num' => $result['ext_product_num'],
							'thumb' => $this->model_catalog_product->get_thumbnail_path($result['product_id']),
							'price' => $this->currency->format($this->tax->calculate($result['price'], $this->data['cust_tax_class'], $this->config->get('config_tax'))),
							'special' => $this->currency->format($this->tax->calculate($result['special'], $this->data['cust_tax_class'], $this->config->get('config_tax'))),
							'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product' . $url . '&product_id=' . $result['product_id']))
						);
					
					}
        	}
				
			$url = '';

			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'product/external_special.tpl';
			$this->layout   = 'common/externalspeciallayout';
		
			$this->render();			
		} 
}
?>
