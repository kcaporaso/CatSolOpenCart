<?php
ini_set("display_errors", 1);
final class Cart extends Controller {
    
    
  	public function __construct() {
  	    
		$this->config = Registry::get('config');
		$this->session = Registry::get('session');
		$this->db = Registry::get('db');
		$this->language = Registry::get('language');
		$this->tax = Registry::get('tax');
		$this->weight = Registry::get('weight');

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
        					p.ext_product_num,
        					p.shipping_ as shipping,
        					'1' as status,
        					'2001-01-01' as date_available,
        					p.date_added,
        					p.date_modified,
                     p.discount_level,
                     p.extra_shipping,
        					SP.quantity,
        					SP.stock_status_id,
        					IF((SP.price IS NOT NULL AND SP.price > 0), SP.price, p.price) as price,
        					SP.tax_class_id,

                     /*IF((p.productvariantgroup_id IS NOT NULL AND PGLD.name IS NOT NULL), CONCAT(pd.name,' (',PGLD.name,')'), pd.name) as name,*/
        					pd.name as name,
            			pd.meta_description,
            			pd.description,
            			IF(p.productvariantgroup_id IS NOT NULL, PGLD.name, '') as gradelevels_display
        						
      			FROM " . DB_PREFIX . "product p 
      			
    				INNER JOIN productset_product as PP
    					ON (p.product_id = PP.product_id)
    				INNER JOIN store_productsets as SPS
    					ON (PP.productset_id = SPS.productset_id AND SPS.productset_id = p.productset_id)
    				INNER JOIN store as S
    					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
    				INNER JOIN store_product as SP
    					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')
      			
      				LEFT JOIN " . DB_PREFIX . "product_description pd 
      				ON (p.product_id = pd.product_id) 
      				
    				LEFT JOIN product_gradelevels_display as PGLD
    					ON (p.product_id = PGLD.product_id)      				
      				
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
      			    
      			    $option_value_query_sql = "
        		 		SELECT 	pov.product_option_id, 
        		 				povd.name, pov.price, pov.prefix 
        		 		FROM " . DB_PREFIX . "product_option_value pov 
            		 			LEFT JOIN " . DB_PREFIX . "product_option_value_description povd 
            		 				ON (pov.product_option_value_id = povd.product_option_value_id) 
        		 		WHERE 	1
        		 			AND	pov.product_option_value_id = '" . (int)$product_option_value_id . "' 
        		 			AND pov.product_id = '" . (int)$product_id . "' 
        		 			AND povd.language_id = '" . (int)$this->language->getId() . "' 
        		 		ORDER BY pov.sort_order
        		 	";
//echo $option_value_query_sql;      			    
        		 	$option_value_query = $this->db->query($option_value_query_sql);
					
					if ($option_value_query->num_rows) {
					    
						$option_query = $this->db->query("
							SELECT 		pod.name 
							FROM " . DB_PREFIX . "product_option po 
										LEFT JOIN " . DB_PREFIX . "product_option_description pod 
											ON (po.product_option_id = pod.product_option_id) 
							WHERE 		1
								AND		po.product_option_id = '" . (int)$option_value_query->row['product_option_id'] . "' 
								AND 	po.product_id = '" . (int)$product_id . "' 
								AND 	pod.language_id = '" . (int)$this->language->getId() . "' 
							ORDER BY 	po.sort_order
						");
						
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
		
				$this->load->model('catalog/product');
				$special = $this->model_catalog_product->getProductSpecial($store_code, $product_query->row['product_id'], false);

			   $price = $product_query->row['price'];	

				if ($special) {
					$special = $special;
   			} else {
 					$special = FALSE;
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
				
				// MODIFIED for Customer Group module				
				$this->load->model('catalog/category');
				if ($this->customer->isLogged()) {

					$this->data['cust_group_id'] = $this->customer->getGroupID();
					$this->data['cust_tax_class'] = $this->customer->getGroupTaxClass();
					$this->data['cust_discount'] = $this->customer->getGroupDiscount();

               $discount_pct = 0;
               $category_id = $this->model_catalog_category->getCategoryForProductID($_SESSION['store_code'], $product_query->row['product_id']);
               if ($this->customer->hasCategoryDiscount($category_id, $discount_pct))
               {  
                  // If our category discount is > then a group discount use it.
                  if ($discount_pct > $this->data['cust_discount']) {
                     $this->data['cust_discount'] = $discount_pct;
                  }  
                  // Calculate what should go into the "special" field below.
                  $cat_discount_price = $price-($price*($this->data['cust_discount']*.01));
                  if ($cat_discount_price < $special || !$special) { $special = $cat_discount_price; }
				  
					// SJQ:  Have discount and option_price, we need to discount the option price.
					if ($option_price) {
						$option_price = $option_price-($option_price*($this->data['cust_discount']*.01));	
					}
               }  
   
               // Check for SPS specific discounts next.
               // The product itself has a discount level of 0, 1, 2, 3, 4.
               // 0 is no discount
               // > 1 is some discount %
               if ($product_query->row['discount_level']) {
                  if ($this->customer->isSPS()) {
                     // Check if this customer (at the district level) has a discount at this level.
                     if ($district_discount = $this->customer->getSPS()->getDiscount($product_query->row['discount_level'])) {
                        $district_price = $price-($price*($district_discount*.01)); 
                        if ($district_price < $special || !$special) {
                           $special = $district_price;
                        }
						
						// SJQ:  Have discount and option_price, we need to discount the option price.
						if ($option_price) {
							$option_price = $option_price-($option_price*($district_discount*.01));	
						}
                     }
                  } else {
                     // Bender retail.
                     if ($retail_discount = $this->customer->getDiscount($product_query->row['discount_level'])) {
                        $disc_retail_price = $price-($price*($retail_discount*.01));
                        if ($disc_retail_price < $special || !$special) {
                           $special = $disc_retail_price;
                        }
						
						// SJQ:  Have discount and option_price, we need to discount the option price.
						if ($option_price) {
							$option_price = $option_price-($option_price*($retail_discount*.01));	
						}
						
                     }
                  }
               }
			   
					$product_data[$key] = array(
						'key' => $key,
						'product_id' => $product_query->row['product_id'],
						'name' => $product_query->row['name'],
						'ext_product_num' => $product_query->row['ext_product_num'],
						'shipping' => $product_query->row['shipping'],
						'extra_shipping' => $product_query->row['extra_shipping'],
						'image' => $product_query->row['image'],
						'option' => $option_data,
				   	'download' => $download_data,
						'quantity' => $quantity,
						'stock' => ($quantity <= $product_query->row['quantity']),
						'price' => ($price + $option_price),
					   'special' => $special ? round($special,2) : $special,
						'total' => $special ? ((round($special,2) + $option_price) * $quantity) : (($price + $option_price) * $quantity),
						'tax_class_id' => $this->data['cust_tax_class'],
						'weight' => $product_query->row['weight'],
						'weight_class_id' => $product_query->row['weight_class_id']
					);
				} else {
			      // NOT LoggedIn here.	
					//echo '<!-- NOT LOGGED IN : msrp:' . $msrp . '-->';
					$product_data[$key] = array(
						'key' => $key,
						'product_id' => $product_query->row['product_id'],
						'name' => $product_query->row['name'],
						'ext_product_num' => $product_query->row['ext_product_num'],
						'shipping' => $product_query->row['shipping'],
						'extra_shipping' => $product_query->row['extra_shipping'],
						'image' => $product_query->row['image'],
						'option' => $option_data,
						'download' => $download_data,
						'quantity' => $quantity,
						'stock' => ($quantity <= $product_query->row['quantity']),
						'price' => ($price + $option_price),
						'special' => $special ? round($special,2) : $special,
						'total' => $special ? (round($special,2) + $option_price) * $quantity : ($price + $option_price) * $quantity,
						'tax_class_id' => $product_query->row['tax_class_id'],
						'weight' => $product_query->row['weight'],
						'weight_class_id' => $product_query->row['weight_class_id']
					);
					
					$this->data['cust_discount'] = NULL;
				}			
				// end customer group
				
				if ($cart_extradata = (array) $this->session->data['cart_extradata']) {
				    
				    foreach ($cart_extradata[$key] as $extradata_product_key => $extradata_product_data) {
				        
                        $product_data[$key][$extradata_product_key] = $extradata_product_data;
                        
                        if ($extradata_product_key == 'price') {
                            $product_data[$key]['total'] = $extradata_product_data * $quantity;
                        }
                        
				    }
				    
				}
		
			} else {
				$this->remove($key);
			}
    	}
		
		return $product_data;
  	}
  	
  	
  	public function getProducts_nonstandard () {
  	    
		$product_data = array();
	   if ($this->customer->isLogged()) {	
		   $this->data['cust_group_id'] = $this->customer->getGroupID();
		   $this->data['cust_tax_class'] = $this->customer->getGroupTaxClass();
      }
    	foreach ((array)$this->session->data['cart_nonstandard'] as $item_key => $product_data) {
    	    
			$output[$item_key] = array(
				'key' => $item_key,
				'product_id' => 0,
				'name' => $product_data['product_name'],
				'ext_product_num' => $product_data['ext_product_num'],
				'shipping' => 1,
				'quantity' => $product_data['quantity'],
				'stock' => 1,
				'tax_class_id' => $this->data['cust_tax_class'],
			   'price' => $product_data['price']
			);

			
			$output[$item_key]['total'] = $product_data['price'] * $product_data['quantity'];
			
    		if ($cart_extradata = (array) $this->session->data['cart_extradata']) {
    		    foreach ($cart_extradata[$item_key] as $extradata_product_key => $extradata_product_data) {
                    $output[$item_key][$extradata_product_key] = $extradata_product_data;
    		    }
    		}			

    	}
		
//echo '<!-- get nonstandard';
//print_r($output);
//echo '-->';
		return $output;
  	}
  	
  	
    public function getProducts_all ($store_code) {
        
        $cart_products = array_merge((array)$this->cart->getProducts($store_code), (array)$this->cart->getProducts_nonstandard());
        
        return $cart_products;
        
    }  	

  	
  	public function add ($key, $qty = 1, $options = array(), $extra_data=array()) {
//echo ' opts=> ' . var_dump($options) . '<br/>';
  	    if ( strpos($key, '*^nonstandard^*')!==false && strpos($key, '*^nonstandard^*')==0 ) {
  	        
  	        // nonstandard; add handled elsewhere
  	        
  	    } else {
  	        
      	    if ($options) {
          		$key = $key . ':' . implode('.', $options);
//echo 'k: '.$key . '<br/>';
        	}
    		if (((int)$qty) && ($qty > 0)) {
    		    
        		if (!isset($this->session->data['cart'][$key])) {
          			$this->session->data['cart'][$key] = $qty;
        		} else {
          			$this->session->data['cart'][$key] += $qty;
        		}
        		
    		    foreach ((array)$extra_data as $extradata_key => $extradata_value) {
        		    $this->session->data['cart_extradata'][$key][$extradata_key] = $extradata_value;
        		}        		
        		        
    		}  	        
  	        
//var_dump($this->session->data['cart']);    
//exit;
  	    }
  	    
  	}
  	
  	
  	public function add_nonstandard ($product_name, $ext_product_num, $qty = 1, $unit_price=0, $extra_data=array()) {
    	
		if (((int)$qty) && ($qty > 0)) {
		    
		    $this->session->data['cart_nonstandard']['*^nonstandard^*'.$product_name]['product_name'] = $product_name;
    		$this->session->data['cart_nonstandard']['*^nonstandard^*'.$product_name]['ext_product_num'] = $ext_product_num;
    		$this->session->data['cart_nonstandard']['*^nonstandard^*'.$product_name]['quantity'] += $qty; 
    		$this->session->data['cart_nonstandard']['*^nonstandard^*'.$product_name]['price'] = $unit_price;

    		foreach ((array)$extra_data as $extradata_key => $extradata_value) {
    		    $this->session->data['cart_extradata']['*^nonstandard^*'.$product_name][$extradata_key] = $extradata_value;
    		}
    		
		}
		
  	}  	

  	
  	public function update ($key, $qty) {
  	    
  	   if ( strpos($key, '*^nonstandard^*')!==false && strpos($key, '*^nonstandard^*')==0 ) {

  	        $this->update_nonstandard($key, $qty);
    		
  	   } else {
  	     	        
        	if (((int)$qty) && ($qty > 0)) {
          		$this->session->data['cart'][$key] = $qty;
        	} else {
    	  		$this->remove($key);
    		}
    		  	       
  	   }
		
  	}
  	
  	
  	public function update_nonstandard ($product_name, $qty) {
  	    
    	if (((int)$qty) && ($qty > 0)) {
      		$this->session->data['cart_nonstandard'][$product_name]['quantity'] = $qty;
    	} else {
	  		$this->remove_nonstandard($product_name);
		}
		
  	}
  	  	

  	public function remove ($key) {
  	    
  	    if ( strpos($key, '*^nonstandard^*')!==false && strpos($key, '*^nonstandard^*')==0 ) {
  	        
  	        $this->remove_nonstandard($key);
	        
  	    } else {

            unset($this->session->data['cart'][$key]);
  	        
  	    }

  		
	}
  	  	

  	public function remove_nonstandard ($product_name) {

		if (isset($this->session->data['cart_nonstandard'][$product_name])) {
     		unset($this->session->data['cart_nonstandard'][$product_name]);
  		}
  		
	}
	
	
  	public function clear () {
  	    
		$this->session->data['cart'] = array();
		$this->session->data['cart_nonstandard'] = array();
		$this->session->data['cart_extradata'] = array();
		
  	} 	
  	
  	
  	public function getWeight() {
		$weight = 0;
	
    	foreach ($this->getProducts($_SESSION['store_code']) as $product) {
      		$weight += $this->weight->convert($product['weight'] * $product['quantity'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
    	}
	
		return $weight;
	}

   // KMC - Gift Certs - 07/27/2010
   // This will give us our shippable subtotal - we leave out non-ship items like Gift Certs.
   //
   public function getShippableSubTotal() {
		$total = 0;
		
		$product_rows = (array)$this->getProducts_all($_SESSION['store_code']);
		foreach ($product_rows as $product) {
         // KMC - Gift Certs - 07/27/2010
         if ($product['shipping']) { // only calculate shipable items in subtotal.
			   //$total += $product['total'] - ($product['total'] * ($this->data['cust_discount'] * .01)); // MODIFIED for Customer Group module
			   $total += $product['total'];
         }
		}

		return $total;
   }
	
  	public function getSubTotal() {
  	    
		$total = 0;
		
		$product_rows = (array)$this->getProducts_all($_SESSION['store_code']);
//var_dump($product_rows);
		foreach ($product_rows as $product) {
//		   $total += $product['total'] - ($product['total'] * ($this->data['cust_discount'] * .01)); // MODIFIED for Customer Group module
		   $total += $product['total'];
		}
//exit;
		return $total;
		
  	}
  	
	
	public function getTaxes() {
      // Determine if tax exempt.
      if ($this->customer->isLogged()) {
         // Bail if exempt.
         if ($this->customer->isTaxExempt()) { return 0; }
      }
//var_dump($this->getProducts_all($_SESSION['store_code']));
		foreach ((array)$this->getProducts_all($_SESSION['store_code']) as $product) {
		   // Customer Group module		
		   if ($this->customer->isLogged()) {
			   $product['tax_class_id'] = $this->data['cust_tax_class'];
    		}
	   	// end customer group
			if ($product['tax_class_id'] && $product['shipping']) { // KMC - we need to exclude Gift Certs, no ship, no taxes.
				if (!isset($taxes[$product['tax_class_id']])) {
					$taxes[$product['tax_class_id']] = $product['total'] / 100 * $this->tax->getRate($product['tax_class_id']);
				} else {
					$taxes[$product['tax_class_id']] += $product['total'] / 100 * $this->tax->getRate($product['tax_class_id']);
				}
			}
		}
		return $taxes;
  	}

	// MODIFIED for Customer Group module
	public function getTotal() {
	
		$total = 0;
		
      $allproducts = $this->getProducts_all($_SESSION['store_code']);
//      var_dump($allproducts); exit;
		foreach ($allproducts as $product) {
			if ($this->customer->isLogged()) {
				   $total += $this->tax->calculate($product['total'], $this->data['cust_tax_class'], $this->config->get('config_tax'));
			} else {
				$total += $this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax'));
			}
		}
		
		return $total;
	
	}
	// end customer group
  	
  	public function countProducts() {
  	    
		$total = 0;
		
		foreach ($this->session->data['cart'] as $value) {
			$total += $value;
		}
		
  		foreach ((array)$this->session->data['cart_nonstandard'] as $product) {
			$total += $product['quantity'];
		}		
		
    	return $total;
    	
  	}
	  
  	public function hasProducts() {
    	return count($this->session->data['cart']) + count($this->session->data['cart_nonstandard']);
  	}
  
  	
  	public function hasStock() {
  	    
		$stock = TRUE;
		
		foreach ($this->getProducts($_SESSION['store_code']) as $product) {
			if (!$product['stock']) {
	    		$stock = FALSE;
			}
		}
		
  		// nonstandard Products always will be "in stock" so we don't check them		
		
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
		
		if (count($this->getProducts_nonstandard())) {
		    $shipping = TRUE;
		}
		
		return $shipping;
		
	}
	
	
	public function debug ($string) {
	    
	    $data['value'] = $string;
	    $this->db->add('debug', $data);
	    
	}	
	
   // KMC: this will grab cart products at face value, no querying database for new information.
   // Mainly used in the admin area to deal with changing already placed orders.
   public function getCartInMemoryProducts()
   {
		$product_data = array();
//   	var_dump($this->session->data['cart']);	
//    var_dump($this->session->data['cart_extradata']);
//    exit;
      //  Standard Products!
    	foreach ($this->session->data['cart'] as $key => $value) {
      	$array = explode(':', $key);
      	$product_id = $array[0];
      	$quantity = $value;

      	if (isset($array[1])) {
        	   $options = explode('.', $array[1]);
      	} else {
        	   $options = array();
         }      			
         $product_data[$key] = array(
            'key' => $key,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'name' => $this->session->data['cart_extradata'][$key]['name'],
            'order_product_id' => $this->session->data['cart_extradata'][$key]['order_product_id'],
            'price' => $this->session->data['cart_extradata'][$key]['price'],
            'ext_product_num' => $this->session->data['cart_extradata'][$key]['ext_product_num'],
            'discount' => $this->session->data['cart_extradata'][$key]['discount'],
            'total' => $this->session->data['cart_extradata'][$key]['total'],
            'tax' => $this->session->data['cart_extradata'][$key]['tax']
         );
      }

      // Non standard products.
      $nonstd_data = $this->getProducts_nonstandard();

      $merged = array();
      if ($nonstd_data) {
         $merged = array_merge($product_data, $nonstd_data);
         return $merged;
      } else {
         return $product_data;
      }
   }

   // Determine if any cart items has at least one "extra shipping" charge.
   public function hasExtraShippingItem() {
      $cart_products = $this->getProducts($_SESSION['store_code']);
      foreach ($cart_products as $p) {
         if ($p['extra_shipping']) { return true; }
      } 
      return false;
   }
}
?>
