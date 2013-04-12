<?php 
class ControllerProductSpecial extends Controller { 	
	public function index() { 
    	$this->language->load('product/special');
	  	  
    	$this->document->title = $this->language->get('heading_title');

		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->http('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
			
   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->http('product/special' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => $this->language->get('text_separator')
   		);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
   
		$this->data['text_sort'] = $this->language->get('text_sort');
			 
  		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
	
		$this->load->model('catalog/product');
			
		$product_total = $this->model_catalog_product->getTotalProductSpecials($_SESSION['store_code']);
						
		if ($product_total) {
			$url = '';
				
			$this->load->model('catalog/review');
			$this->load->model('tool/seo_url');
			
			$this->load->helper('image');
				
       		$this->data['products'] = array();
				
			$results = $this->model_catalog_product->getProductSpecials($_SESSION['store_code'], $sort, $order, ($page - 1) * 12, 12);
        		
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'no_image.jpg';
				}						
					
				$rating = $this->model_catalog_review->getAverageRating($_SESSION['store_code'], $result['product_id']);	
									
				// MODIFIED for Customer Group module
				
				if ($this->customer->isLogged()) {
				
					$this->data['cust_group_id'] = $this->customer->getGroupID();
					$this->data['cust_tax_class'] = $this->customer->getGroupTaxClass();
					$this->data['cust_discount'] = $this->customer->getGroupDiscount();
					
					if ($this->data['cust_discount']>0) {
					
						$this->data['products'][] = array(
							'name' => $this->language->clean_string($result['name']),
						    'gradelevels_display' => $result['gradelevels_display'],
							'ext_product_num' => $result['ext_product_num'],
							'rating' => $rating,
							'stars' => sprintf($this->language->get('text_stars'), $rating),
							'thumb' => $this->model_catalog_product->get_thumbnail_path($result['product_id']),
							'price' => $this->currency->format($this->tax->calculate($result['price']-($result['price']*($this->data['cust_discount']*.01)), $this->data['cust_tax_class'], $this->config->get('config_tax'))),
							'special' => NULL,
							'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product' . $url . '&product_id=' . $result['product_id']))
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
							'special' => $this->currency->format($this->tax->calculate($result['special'], $this->data['cust_tax_class'], $this->config->get('config_tax'))),
							'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product' . $url . '&product_id=' . $result['product_id']))
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
						'special' => $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'))),
						'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product' . $url . '&product_id=' . $result['product_id']))
					);
					
					$this->data['cust_discount'] = NULL;
				
				}
				
				// end customer group

        	}
				
			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}	
				
			$this->data['sorts'] = array();
				
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name',
				'href'  => $this->url->http('product/special' . $url . '&sort=pd.name')
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->http('product/special' . $url . '&sort=pd.name&order=DESC')
			);  

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'special-ASC',
				'href'  => $this->url->http('product/special' . $url . '&sort=special&order=ASC')
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'special-DESC',
				'href'  => $this->url->http('product/special' . $url . '&sort=special&order=DESC')
			); 
				
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $this->url->http('product/special' . $url . '&sort=rating&order=DESC')
			); 
				
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $this->url->http('product/special' . $url . '&sort=rating&order=ASC')
			); 
				
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = 12; 
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->http('product/special' . $url . '&page=%s');
				
			$this->data['pagination'] = $pagination->render();
				
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'product/special.tpl';
			$this->layout   = 'common/layout';
		
			$this->render();			
		} else {
      		$this->data['text_error'] = $this->language->get('text_empty');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->http('common/home');
	  			
			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'error/not_found.tpl';
			$this->layout   = 'common/layout';
		
			$this->render();
		}
  	}
}
?>