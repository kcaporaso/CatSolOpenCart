<?php  

class ControllerCommonCart extends Controller {
    
	protected function index() { 	
	    
		$this->language->load('common/cart');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

      $this->data['text_subtotal'] = $this->language->get('text_subtotal');
      $this->data['text_empty'] = $this->language->get('text_empty');

      $this->data['text_checkout'] = $this->language->get('text_checkout');
      $this->data['text_cart'] = $this->language->get('text_cart');

      $this->data['checkout'] = $this->url->https('checkout/shipping');
      $this->data['cart_link'] = $this->model_tool_seo_url->rewrite($this->url->http('checkout/cart'));
      $this->data['emailcart'] = $this->model_tool_seo_url->rewrite($this->url->http('checkout/cart&emailcart=yes'));
      $this->data['account']   = $this->model_tool_seo_url->rewrite($this->url->http('account/account'));
      $this->data['wishlists'] = $this->model_tool_seo_url->rewrite($this->url->http('account/findlist'));
      $this->data['login'] = $this->model_tool_seo_url->rewrite($this->url->http('account/login'));
      $this->data['logout'] = $this->model_tool_seo_url->rewrite($this->url->http('account/logout'));
       
      $this->data['username'] = $this->customer->isSPS() ? $this->customer->getSPS()->getUsername() : $this->customer->getEmail() ;
      $this->data['fullname'] = $this->customer->getFirstname() . ' ' . $this->customer->getLastname() ;
      $this->data['islogged'] = $this->customer->isLogged();
      
      $this->session->data['homepage_username'] = $this->data['fullname']; // Set username to session for homepages.
      $this->session->data['homepage_islogged'] = $this->customer->isLogged(); // Same for islogged Status

      $this->data['products'] = array();
      
      foreach ($this->cart->getProducts_all($_SESSION['store_code']) as $result) {
         /*$option_data = array();

         foreach ($result['option'] as $option) {
               $option_data[] = array(
                  'name'  => $option['name'],
                  'value' => $option['value']
               );      
         }*/       
         
            $this->data['products'][] = array(
            'name'     => $result['name']/*,
            'option'   => $option_data,
            'quantity' => $result['quantity'],            
            'stock'    => $result['stock'],
            'price'    => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
            'href'     => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id'])),            */
           );      
      }

      $this->data['subtotal'] = $this->currency->format($this->cart->getTotal());
      $this->session->data['homepage_subtotal'] = $this->data['subtotal']; // Adding subtotal to a session var so we can easily grab on non catalog pages.

		$this->id       = 'cart';
	   $this->template = $this->config->get('config_template') . 'common/'.$_SESSION['store_code'].'_cart.tpl';

    	$this->render();
    	
  	}
  	
}
?>
