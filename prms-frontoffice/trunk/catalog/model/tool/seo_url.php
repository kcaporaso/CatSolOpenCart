<?php
class ModelToolSeoUrl extends Model {
    
	public function rewrite ($link) {
	    
		if ($this->config->get('config_seo_url')) {
		    
			$url_data = parse_url($link);
		
			$url = '';
		
			$data = array();
		
			parse_str(html_entity_decode($url_data['query']), $data);
			
			foreach ($data as $key => $value) {
			    
				if (($key == 'product_id') || ($key == 'manufacturer_id') || ($key == 'information_id')) {
				    
				    $sql = "
						SELECT 		* 
						FROM " . DB_PREFIX . "url_alias 
						WHERE 		1
							AND		`query` = '" . $this->db->escape($key . '=' . (int)$value) . "'
							AND		(store_code IS NULL OR store_code = '{$_SESSION['store_code']}')				    
				    ";
				    
					$query = $this->db->query($sql);
                    			
					if ($query->num_rows) {
						$url .= '/' . $query->row['keyword'];
						
						unset($data[$key]);
					}	
									
				} elseif ($key == 'path') {
				    
					$categories = explode('_', $value);
					
					foreach ($categories as $category) {
					    
					    $sql = "
							SELECT 		* 
							FROM " . DB_PREFIX . "url_alias 
							WHERE 		1
								AND		`query` = 'category_id=" . (int)$category . "'
								AND		(store_code IS NULL OR store_code = '{$_SESSION['store_code']}')					    
					    ";
					    
						$query = $this->db->query($sql);
		
						if ($query->num_rows) {
							$url .= '/' . $query->row['keyword'];
						}							
					}
					
					unset($data[$key]);
				}
				
			}
		
			if ($url) {
			    
				unset($data['route']);
			
				$query = '';
			
				if ($data) {
					$query = '?' . str_replace('&', '&amp;', http_build_query($data));
				}

				return $url_data['scheme'] . '://' . $url_data['host'] . str_replace('/index.php', '', $url_data['path']) . $url . $query;
				
			} else {
			    
				return $link;
				
			}
			
		} else {
		    
			return $link;
			
		}	
			
	}
	
}
?>