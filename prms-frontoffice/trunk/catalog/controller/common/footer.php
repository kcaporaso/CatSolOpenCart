<?php  
class ControllerCommonFooter extends Controller {
	protected function index() {
		$this->language->load('common/footer');
		
		$this->data['text_powered_by'] = sprintf($this->language->get('text_powered_by'), $this->config->get('config_store'), date('Y'));
		$this->data['cataloghome'] = $this->url->http('common/home');
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
      $this->data['sitemap'] = $this->url->http('information/sitemap');
		
		$this->id       = 'footer';

		$this->template = $this->config->get('config_template') . 'common/footer.tpl';
		
		$this->render();
	}
}
?>
