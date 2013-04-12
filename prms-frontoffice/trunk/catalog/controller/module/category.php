<?php  

class ControllerModuleCategory extends Controller {
    
    
	protected $category_id = 0;
	protected $path = array();
	
	
	protected function index() {
	    
		$this->language->load('module/category');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('catalog/category');
		$this->load->model('tool/seo_url');
		
		if (isset($this->request->get['path'])) {
			$this->path = explode('_', $this->request->get['path']);
			
			$this->category_id = end($this->path);
		}
		
		$this->data['category'] = $this->getCategories($_SESSION['store_code'], 0);
												
		$this->id       = 'category';

		$this->template = $this->config->get('config_template') . 'module/category.tpl';		
		
		$this->render();
		
  	}
	
  	
	protected function getCategories ($store_code, $parent_id, $current_path = '', $show_only_if_has_products=true) {
	    
		$category_id = array_shift($this->path);
		
		$output = '';
	   //echo '::getCategories from model:<br/>';	
      // get cache
      //$results = $this->cache->get('categories.' . $parent_id . '.' . $store_code);
      //if (!$results) {
		   $results = $this->model_catalog_category->getCategories($store_code, $parent_id);
         // set the cache.
         //$this->cache->set('categories.' . $parent_id . '.' . $store_code, $results);
      //}
		
		if ($results) { 
			$output .= '<ul>';
    	}
		
		foreach ($results as $result) {
		    
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}
			
			$output .= '<li>';
			
			$children = '';
			

         if (defined('BENDER')) {
			   // Always grab kids. Platinums get flyouts
				$children = $this->getCategories($store_code, $result['category_id'], $new_path);
         } else {
			   if ($category_id == $result['category_id']) {
				   $children = $this->getCategories($store_code, $result['category_id'], $new_path);
		      }
         }
			
			if ($this->category_id == $result['category_id']) {
				$output .= '<a href="' . $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $new_path))  . '"><b>' . $result['name'] . '</b></a>';
			} else {
				$output .= '<a href="' . $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $new_path))  . '">' . $result['name'] . '</a>';
			}
			
        	$output .= $children;
        
        	$output .= '</li>'; 
        	
		}
 
		if ($results) {
			$output .= '</ul>';
		}
		
		return $output;
		
	}

	
}
?>
