<?php


class ModelCatalogManufacturer extends Model {
    
    
	public function getManufacturer ($manufacturer_id) {
	    
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
	
		return $query->row;	
	
	}
	
	
	public function getManufacturers ($store_code) {
	    
		$manufacturer = $this->cache->get($store_code.'.manufacturer');
		
		if (!$manufacturer) {
		    
			$sql = "
				SELECT 		M.* 
				FROM " . DB_PREFIX . "manufacturer as M
					INNER JOIN product as P
						ON (P.manufacturer_id = M.manufacturer_id)						
    				INNER JOIN productset_product as PP
    					ON (P.product_id = PP.product_id)
    				INNER JOIN store_productsets as SPS
    					ON (PP.productset_id = SPS.productset_id AND SPS.productset_id = P.productset_id)
    				INNER JOIN store as S
    					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
    				INNER JOIN store_product as SP
    					ON (P.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')
    			GROUP BY	M.manufacturer_id		
				ORDER BY 	M.name
			";
         //echo '<!-- MFG SQL: ' . $sql . '-->';	
			$query = $this->db->query($sql);
			$manufacturer = $query->rows;
			
			$this->cache->set($store_code.'.manufacturer', $manufacturer);
			
		}
		 
		return $manufacturer;
		
	} 
	
}
?>
