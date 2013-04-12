<?php  
class ControllerModuleBestSeller extends Controller {
	protected function index() {
		$this->language->load('module/bestseller');

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('catalog/review');
		$this->load->model('tool/seo_url');
		$this->load->helper('image');
		
		$this->data['products'] = array();
		
		$results = $this->model_catalog_product->getBestSellerProducts($_SESSION['store_code'], $this->config->get('bestseller_limit'));
			
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$rating = $this->model_catalog_review->getAverageRating($_SESSION['store_code'], $result['product_id']);	

			$special = $this->model_catalog_product->getProductSpecial($_SESSION['store_code'], $result['product_id'], false);
			
			if ($special) {
				$special = $special; //$this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = FALSE;
			}
//echo '<!--sp:'.$special.'-->';			
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
               //echo '<!--cat_disc_price'.$cat_discount_price.'-->';
               if ($cat_discount_price < $special || !$special) { $special = $cat_discount_price; }
            }

				$this->data['products'][] = array(
					'name' => $this->language->clean_string($result['name']),
               // Don't calculate the discount in the display price, we want to show full price, then cross it out.
					//'price' => $this->currency->format($this->tax->calculate($result['price']-($result['price']*($this->data['cust_discount']*.01)), $this->data['cust_tax_class'], $this->config->get('config_tax'))),
					'price' => $this->currency->format($result['price']),
					'special' => $special ? $this->currency->format($special) : $special,
					'image' => HelperImage::resize($image, 38, 38),
					'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
				);
			
			} else {
			
				$this->data['products'][] = array(
					'name' => $this->language->clean_string($result['name']),
					'price' => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'special' => $special ? $this->currency->format($special) : $special,
					'image' => HelperImage::resize($image, 38, 38),
					'href' => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
				);
			
			}
			
			// end customer group
		}
		
		$this->id       = 'bestseller';
		$this->template = $this->config->get('config_template') . 'module/bestseller.tpl';
		
		$this->render();
		
	}
}
?>
