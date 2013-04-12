<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->language->load('common/header');
	    	
		$this->data['store'] = $this->config->get('config_store');
		
		if (@$this->request->server['HTTPS'] != 'on') {
			$this->data['logo'] = HTTP_IMAGE  .'stores/'.$_SESSION['store_code'].'/'. $this->config->get('config_logo');
		} else {
			$this->data['logo'] = HTTPS_IMAGE .'stores/'.$_SESSION['store_code'].'/'. $this->config->get('config_logo');
		}
		
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_special'] = $this->language->get('text_special');
    	$this->data['text_account'] = $this->language->get('text_account');
    	$this->data['text_login'] = $this->language->get('text_login');
    	$this->data['text_logout'] = $this->language->get('text_logout');
    	$this->data['text_cart'] = $this->language->get('text_cart'); 
    	$this->data['text_checkout'] = $this->language->get('text_checkout');

		$this->data['cataloghome'] = $this->url->http('common/home');
      //Andrea wanted HOME icon in tool bar to go to dealer specified home page.
      $config_hp = $this->config->get('config_home_page');
      if (strpos($config_hp, 'http://')) {
		   $this->data['home'] = $this->config->get('config_home_page');
      } else {
         $this->data['home'] = 'http://' . $config_hp;
      }

		$this->data['special'] = $this->url->http('product/special');
    	$this->data['account'] = $this->url->https('account/account');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['login'] = $this->url->https('account/login');
		$this->data['logout'] = $this->url->http('account/logout');
    	$this->data['cartlink'] = $this->url->http('checkout/cart');
		$this->data['checkout'] = $this->url->https('checkout/shipping');
		$this->data['typeaheadorderform'] = $this->url->http('checkout/typeaheadorderform');
		$this->data['contact'] = $this->url->http('information/contact');
		$this->data['calendar'] = $this->url->http('information/calendar');
		$this->data['wishlist'] = $this->url->http('product/wishlist');
      $this->data['shoppinglist'] = $this->url->http('account/list');
      $this->data['findlist'] = $this->url->http('account/findlist');

      // SPS integration.
      $this->data['iamsps'] = $this->customer->isSPS();

		$this->id       = 'header';
		$this->template = $this->config->get('config_template') . 'common/'.$_SESSION['store_code'].'_header.tpl';

		$this->children = array(
			'common/language',
			'common/cart',
			//'common/search'
		);
		
    	$this->render();
	}	
}
?>
