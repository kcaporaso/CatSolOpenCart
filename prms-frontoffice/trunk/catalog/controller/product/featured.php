<?php 
/*
 This is typically called from a home page for a dealer, it's sort of the back door into
 the featured products for a dealer.
*/
class ControllerProductFeatured extends Controller {
    
	public function index() {
	    
		$this->language->load('product/category');
	
		$this->load->model('catalog/product');
		$this->load->model('tool/seo_url');  
      $this->load->model('catalog/category');
		
	   $this->data['products'] = array();
     	$store_code = $this->request->get['sc'];
      $prod_count = $this->request->get['ct'];
      $prod_wrap  = $this->request->get['wrap'];
      if (empty($prod_count)) { $prod_count = 3; }
      if (empty($prod_wrap))  { $prod_wrap = 4; }

      //echo 'code:' . $store_code;	
      // If we have no products, then try to use the featured ones, this is most likely
      // done when we are on a major category page that has sub category featured items.
      // KMC : Grab the featured products for sub categories of our parent category:
      $results = $this->model_catalog_product->getFeaturedProducts($store_code, $prod_count, true);
            $this->load->helper('image');
            // Loop each result of the product set!!
        		foreach ($results as $result) {
					if ($result['image']) {
						$image = $result['image'];
					} else {
						$image = 'no_image.jpg';
					}

					$special = $this->model_catalog_product->getProductSpecial($store_code, $result['product_id']);
					if ($special) {
						$special = $special; //$this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = FALSE;
					}

					// MODIFIED for Customer Group module
               
					
					if ($this->customer->isLogged()) {
					
						$this->data['cust_group_id'] = $this->customer->getGroupID();
						$this->data['cust_tax_class'] = $this->customer->getGroupTaxClass();
						$this->data['cust_discount'] = $this->customer->getGroupDiscount();
                  $category_id = $this->model_catalog_category->getCategoryForProductID($_SESSION['store_code'], $result['product_id']);
                  //echo "<!-- category_id: " . $category_id . "-->";
                
                  $discount_pct = 0; 
                  if ($this->customer->hasCategoryDiscount($category_id, $discount_pct))
                  {  
                     // If our category discount is > then a group discount use it.
                     if ($discount_pct > $this->data['cust_discount']) {
                        $this->data['cust_discount'] = $discount_pct;
                     }  
                     //echo '<!--disc:'.$this->data['cust_discount'].'-->';
                     // Calculate what should go into the "special" field below.
                     $cat_discount_price = $result['price']-($result['price']*($this->data['cust_discount']*.01));
                     //echo '<!--cat_disc_price'.$cat_discount_price.'-->';
                     if ($cat_discount_price < $special || !$special) { $special = $cat_discount_price; }
                  }  
 
                  // Check for SPS specific discounts next.
                  // The product itself has a discount level of 0, 1, 2, 3, 4.
                  // 0 is no discount
                  // > 1 is some discount %
                  if ($result['discount_level']) {
                     if ($this->customer->isSPS()) {
                        // Check if this customer (at the district level) has a discount at this level.
                        if ($district_discount = $this->customer->getSPS()->getDiscount($result['discount_level'])) {
                           $district_price = $result['price']-($result['price']*($district_discount*.01)); 
                           if ($district_price < $special || !$special) {
                              $special = $district_price;
                           }
                        }
                     } else {
                        if ($retail_discount = $this->customer->getDiscount($result['discount_level'])) {
                           $disc_retail_price = $result['price']-($result['price']*($retail_discount*.01)); 
                           if ($disc_retail_price < $special || !$special) {
                              $special = $disc_retail_price;
                           }
                        }
                     }
                  }

                  if ($this->data['cust_discount']>0) {
//echo '<!-- cust_discount gt 0 -->';						
							$this->data['products'][] = array(
								'name' => $this->language->clean_string($result['name']),
							   'gradelevels_display' => $result['gradelevels_display'],
								'ext_product_num' => $result['ext_product_num'],
								'rating' => $rating,
								'stars' => sprintf($this->language->get('text_stars'), $rating),
								'thumb' => $this->model_catalog_product->get_thumbnail_path($result['product_id']),
								'price' => $this->currency->format($this->tax->calculate($result['price'], $this->data['cust_tax_class'], $this->config->get('config_tax'))),
								'special' => $special ? $this->currency->format($special) : $special,
								'product_id' => $result['product_id'],
								'pvg_id' => $result['productvariantgroup_id'],
								'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'])),
								//'description' => $this->language->clean_string($result['description'])
							);
						
						} else {
						
							$this->data['products'][] = array(
								//'name' => $result['name'],
								'name' => $this->language->clean_string($result['name']),
						      'gradelevels_display' => $result['gradelevels_display'],
								'ext_product_num' => $result['ext_product_num'],
								'rating' => $rating,
								'stars' => sprintf($this->language->get('text_stars'), $rating),
								'thumb' =>  $this->model_catalog_product->get_thumbnail_path($result['product_id']),
								'price' => $this->currency->format($this->tax->calculate($result['price'], $this->data['cust_tax_class'], $this->config->get('config_tax'))),
								'special' => $special ? $this->currency->format($special) : $special,
								'product_id' => $result['product_id'],
								'pvg_id' => $result['productvariantgroup_id'],
								'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'])),
								//'description' => $this->language->clean_string($result['description']),
								'cat_discount_price' => NULL 
							);
						
						}
					
					} else {
					
                  // not logged in can't know about customer specific discounts.
						$this->data['products'][] = array(
							'name' => $this->language->clean_string($result['name']),
							'gradelevels_display' => $result['gradelevels_display'],
							'ext_product_num' => $result['ext_product_num'],
							'rating' => $rating,
							'stars' => sprintf($this->language->get('text_stars'), $rating),
							'thumb' =>  $this->model_catalog_product->get_thumbnail_path($result['product_id']),
							'price' => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
							'special' => $special ? $this->currency->format($special) : $special,
							'product_id' => $result['product_id'],
							'pvg_id' => $result['productvariantgroup_id'],
							'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'])),
							//'description' => $this->language->clean_string($result['description'])
						);
						
						$this->data['cust_discount'] = NULL;
					
					}
					
					// end customer group				

/* 

				   $this->data['products'][] = array(
							'name' => $this->language->clean_string($result['name']),
						   'gradelevels_display' => $result['gradelevels_display'],
							'ext_product_num' => $result['ext_product_num'],
							'rating' => $rating,
							'stars' => sprintf($this->language->get('text_stars'), $rating),
							'thumb' =>  $this->model_catalog_product->get_thumbnail_path($result['product_id']),
							'price' => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
							'special' => $special,
							'product_id' => $result['product_id'],
							'pvg_id' => $result['productvariantgroup_id'],
							'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&path=' . $this->request->get['path'] . '&product_id=' . $result['product_id']))
						);
						
						$this->data['cust_discount'] = NULL;
 */           
        		}
            $this->data['prod_count'] = $prod_count;
            $this->data['prod_wrap'] = $prod_wrap;
            $this->data['cartlink'] = $this->url->http('checkout/cart');
            $this->data['store_code'] = $store_code;
				$url = '';
		
				$this->id       = 'content';
				$this->template = $this->config->get('config_template') . 'product/featured.tpl';
				$this->layout   = 'common/featuredlayout';
		
				$this->render();	
     	}
}
?>
