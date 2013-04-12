<?php  

class ControllerCommonHome extends Controller {
    
    
	public function index() {
		    
		$this->language->load('common/home');
		
		$this->document->title = $this->config->get('config_store');
		$this->document->description = $this->config->get('config_meta_description');

		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);
		
		$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_store'));
		$this->data['welcome'] = html_entity_decode($this->config->get('config_welcome_' . $this->language->getId()));
		
		$this->data['text_featured'] = $this->language->get('text_featured');
      $this->data['cartlink'] = $this->url->http('checkout/cart');
      $this->data['special'] = $this->url->http('product/special');		

		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('catalog/review');
		$this->load->model('tool/seo_url');
		$this->load->helper('image');
		
		$this->data['products'] = array();

      $showpinnedfeatured = true;
		foreach ($this->model_catalog_product->getFeaturedProducts($_SESSION['store_code'], 8, $showpinnedfeatured) as $result) {			
			if ($result['image']) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			//$rating = $this->model_catalog_review->getAverageRating($_SESSION['store_code'], $result['product_id']);	

			$special = $this->model_catalog_product->getProductSpecial($_SESSION['store_code'], $result['product_id'], false);
			
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

            $discount_pct = 0; 
            $category_id = $this->model_catalog_category->getCategoryForProductID($_SESSION['store_code'], $result['product_id']); 
            if ($this->customer->hasCategoryDiscount($category_id, $discount_pct))
            {  
               // If our category discount is > then a group discount use it.
               if ($discount_pct > $this->data['cust_discount']) {
                  $this->data['cust_discount'] = $discount_pct;
               }  
               //echo '<!--disc:'.$this->data['cust_discount'].'-->';
               // Calculate what should go into the "special" field below.
               $cat_discount_price = $result['price']-($result['price']*($this->data['cust_discount']*.01));
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
				
					$this->data['products'][] = array(
						'name' => $this->language->clean_string($result['name']),
  				      'gradelevels_display' => $result['gradelevels_display'],
						'ext_product_num' => $result['ext_product_num'],
						'rating' => $rating,
						'stars' => sprintf($this->language->get('text_stars'), $rating),
						'thumb' => $this->model_catalog_product->get_thumbnail_path($result['product_id']),
                  // show full price, then cross it out using the "special" price below.
						//'price' => $this->currency->format($this->tax->calculate($result['price']-($result['price']*($this->data['cust_discount']*.01)), $this->data['cust_tax_class'], $this->config->get('config_tax'))),
						'price' => $this->currency->format($this->tax->calculate($result['price'], $this->data['cust_tax_class'], $this->config->get('config_tax'))),
						'special' => $special ? $this->currency->format($special) : $special,
						'product_id' => $result['product_id'],
						'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id'])),
                  'description' => $this->language->clean_string($result['description'])
					);
				
				} else {
				
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
                  'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id'])),
                  'description' => $this->language->clean_string($result['description'])
					);
				
				}
			
			} else {
			
				$this->data['products'][] = array(
					'name' => $this->language->clean_string($result['name']),
				    'gradelevels_display' => $result['gradelevels_display'],
					'ext_product_num' => $result['ext_product_num'],
					'rating' => $rating,
					'stars' => sprintf($this->language->get('text_stars'), $rating),
					'thumb' => $this->model_catalog_product->get_thumbnail_path($result['product_id']),
					'price' => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'special' => $special ? $this->currency->format($special) : $special,
					'product_id' => $result['product_id'],
               'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id'])),
               'description' => $this->language->clean_string($result['description'])
				);
				
				$this->data['cust_discount'] = NULL;
			
			}
			
			// end customer group

		}
		
		$this->id       = 'content';
		if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'common/'.$_SESSION['store_code'].'_home.tpl')){
			$this->template = $this->config->get('config_template') . 'common/'.$_SESSION['store_code'].'_home.tpl';
		}else{
			$this->template = $this->config->get('config_template') . 'common/home.tpl';		
		}
		$this->layout   = 'common/layout';

		//$this->children[] = 'module/events';		// enable to show Event on Home Page if on current day; also update catalog/view/theme/default/template/common/home.tpl
		
		$this->render();
		
	}
	
	
}
?>
