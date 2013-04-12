<?php
class ModelCheckoutCart extends Model {
  	public function __construct() {
		if (!is_array(@$this->session->data['cart'])) {
      		$this->session->data['cart'] = array();
    	}
	}
	      
  	public function getProducts ($store_code) {
  	    
		$product_data = array();
		
    	foreach ($this->session->data['cart'] as $key => $value) {
    	    
      		$array = explode(':', $key);
      		$product_id = $array[0];
      		$quantity = $value;

      		if (isset($array[1])) {
        		$options = explode('.', $array[1]);
      		} else {
        		$options = array();
      		} 
	 
      		$product_query = $this->db->query("
      			SELECT 		
                			p.product_id,
        					p.image,
        					p.manufacturer_id,
        					p.weight,
        					p.weight_class_id,
        					'1' as shipping,
        					'1' as status,
        					'2001-01-01' as date_available,
        					p.date_added,
        					p.date_modified,
        					p.ext_product_num,
        					
        					SP.quantity,
        					SP.stock_status_id,
        					FORMAT(IF((SP.price IS NOT NULL AND SP.price > 0), SP.price, p.price), 2) as price,
        					SP.tax_class_id,

        					pd.name AS name,
            				pd.meta_description,
            				pd.description 					
        						
      			FROM " . DB_PREFIX . "product p 
      			
    				INNER JOIN productset_product as PP
    					ON (p.product_id = PP.product_id)
    				INNER JOIN store_productsets as SPS
    					ON (PP.productset_id = SPS.productset_id)
    				INNER JOIN store as S
    					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
    				INNER JOIN store_product as SP
    					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')				    			
      			
      				LEFT JOIN " . DB_PREFIX . "product_description pd 
      				ON (p.product_id = pd.product_id) 
      				
      			WHERE 		1
      				AND		p.product_id = '" . (int)$product_id . "' 
      				AND 	pd.language_id = '" . (int)$this->language->getId() . "' 
      				/* AND 	p.date_available <= NOW() */
      				/* AND p.status = '1' */
      				
      			GROUP BY	p.product_id
      		");
      		
			if ($product_query->num_rows) {
      			$option_price = 0;

      			$option_data = array();
      
      			foreach ($options as $product_option_value_id) {
        		 	$option_value_query = $this->db->query("SELECT pov.product_option_id, povd.name, pov.price, pov.prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "product_option_value_description povd ON (pov.product_option_value_id = povd.product_option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_id = '" . (int)$product_id . "' AND povd.language_id = '" . (int)$this->language->getId() . "' ORDER BY pov.sort_order");
					
					if ($option_value_query->num_rows) {
						$option_query = $this->db->query("SELECT pod.name FROM " . DB_PREFIX . "product_option po LEFT JOIN " . DB_PREFIX . "product_option_description pod ON (po.product_option_id = pod.product_option_id) WHERE po.product_option_id = '" . (int)$option_value_query->row['product_option_id'] . "' AND po.product_id = '" . (int)$product_id . "' AND pod.language_id = '" . (int)$this->language->getId() . "' ORDER BY po.sort_order");
						
        				if ($option_value_query->row['prefix'] == '+') {
          					$option_price = $option_price + $option_value_query->row['price'];
        				} elseif ($option_value_query->row['prefix'] == '-') {
          					$option_price = $option_price - $option_value_query->row['price'];
        				}
        
        				$option_data[] = array(
          					'product_option_value_id' => $product_option_value_id,
          					'name'                    => $option_query->row['name'],
          					'value'                   => $option_value_query->row['name'],
          					'prefix'                  => $option_value_query->row['prefix'],
          					'price'                   => $option_value_query->row['price']
        				);
					}
      			}
				
				$product_discount_query = $this->db->query("
					SELECT 	* 
					FROM " . DB_PREFIX . "product_discount 
					WHERE 		1
						AND		product_id = '" . (int)$product_query->row['product_id'] . "' 
						AND 	quantity <= '" . (int)$quantity . "'
						AND		store_code = '{$store_code}'
					ORDER BY 	quantity DESC 
					LIMIT 		1
				");
				
				if ($product_discount_query->num_rows) {
					$discount = $product_discount_query->row['discount'];
				} else {
					$discount = 0;
				}
				
				$this->load->model('catalog/product');
				$special_price = $this->model_catalog_product->getProductSpecial($store_code, $product_id);
			
				if ($special_price) {
					$price = $special_price;
				} else {
					$price = $product_query->row['price'];
				}

				$download_data = array();     		
				
				$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$product_id . "' AND dd.language_id = '" . (int)$this->language->getId() . "'");
			
				foreach ($download_query->rows as $download) {
        			$download_data[] = array(
          				'download_id' => $download['download_id'],
						'name'        => $download['name'],
						'filename'    => $download['filename'],
						'mask'        => $download['mask'],
						'remaining'   => $download['remaining']
        			);
				}
				
      			$product_data[$key] = array(
        			'key'             => $key,
        			'product_id'      => $product_query->row['product_id'],
        			'name'            => $product_query->row['name'],
        			'ext_product_num'           => $product_query->row['ext_product_num'],
					'shipping'        => $product_query->row['shipping'],
        			'image'           => $product_query->row['image'],
        			'option'          => $option_data,
					'download'        => $download_data,
        			'quantity'        => $quantity,
					'stock'           => ($quantity <= $product_query->row['quantity']),
        			'price'           => ($price + $option_price),
					'discount'        => $discount,
        			'total'           => (($price + $option_price) - $discount) * $quantity,
        			'tax_class_id'    => $product_query->row['tax_class_id'],
        			'weight'          => $product_query->row['weight'],
        			'weight_class_id' => $product_query->row['weight_class_id']
      			);
			} else {
				$this->remove($key);
			}
    	}
		
		return $product_data;
  	}
		  
  	public function add($product_id, $qty = 1, $options = array()) {
    	if (!$options) {
      		$key = $product_id;
    	} else {
      		$key = $product_id . ':' . implode('.', $options);
    	}
    	
		if (((int)$qty) && ($qty > 0)) {
    		if (!isset($this->session->data['cart'][$key])) {
      			$this->session->data['cart'][$key] = $qty;
    		} else {
      			$this->session->data['cart'][$key] += $qty;
    		}
		}
  	}

  	public function update($key, $qty) {
    	if (((int)$qty) && ($qty > 0)) {
      		$this->session->data['cart'][$key] = $qty;
    	} else {
	  		$this->remove($key);
		}
  	}

  	public function remove($key) {
		if (isset($this->session->data['cart'][$key])) {
     		unset($this->session->data['cart'][$key]);
  		}
	}

  	public function clear() {
		$this->session->data['cart'] = array();
  	}
  	
  	public function getWeight() {
		$weight = 0;
	
    	foreach ($this->getProducts($_SESSION['store_code']) as $product) {
      		$weight += $this->weight->convert($product['weight'] * $product['quantity'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
    	}
	
		return $weight;
	}

  	public function getSubTotal() {
		$total = 0;
		
		foreach ($this->getProducts($_SESSION['store_code']) as $product) {
			$total += $product['total'];
		}

		return $total;
  	}
	
	public function getTaxes() {
		$taxes = array();
		
		foreach ($this->getProducts($_SESSION['store_code']) as $product) {
			if ($product['tax_class_id']) {
				if (!isset($taxes[$product['tax_class_id']])) {
					$taxes[$product['tax_class_id']] = $product['total'] / 100 * $this->tax->getRate($product['tax_class_id']);
				} else {
					$taxes[$product['tax_class_id']] += $product['total'] / 100 * $this->tax->getRate($product['tax_class_id']);
				}
			}
		}
		
		return $taxes;
  	}

  	public function getTotal() {
		$total = 0;
		
		foreach ($this->getProducts($_SESSION['store_code']) as $product) {
			$total += $this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax'));
		}

		return $total;
  	}
  	
  	public function countProducts() {
		$total = 0;
		
		foreach ($this->session->data['cart'] as $value) {
			$total += $value;
		}
		
    	return $total;
  	}
	  
  	public function hasProducts() {
    	return count($this->session->data['cart']);
  	}
  
  	public function hasStock() {
		$stock = TRUE;
		
		foreach ($this->getProducts($_SESSION['store_code']) as $product) {
			if (!$product['stock']) {
	    		$stock = FALSE;
			}
		}
		
    	return $stock;
  	}
  
  	public function hasShipping() {
		$shipping = FALSE;
		
		foreach ($this->getProducts($_SESSION['store_code']) as $product) {
	  		if ($product['shipping']) {
	    		$shipping = TRUE;
				
				break;
	  		}		
		}
		
		return $shipping;
	}
}
?>