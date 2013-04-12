<?php

/*
 * 	At some point, this should be renamed from Typeahead Order Form to Typeahead Product Lookup.
 */

class ModelCatalogTypeaheadorderform extends Model {
 
    
    public function get_typeahead_response_productname ($store_code, $search_string, $product_row_num) {
        
        $search_string = mysql_real_escape_string(urldecode($search_string));

        if ($this->session->data['iamthebackend']) {
  	        $this->load->library('customer');
      	  $this->customer = new Customer();
        }
        
        $sql = "
        	SELECT		
        				P.product_id, PD.name as product_name, P.ext_product_num, 
                  IF(SP.price IS NOT NULL, SP.price, P.price) as unit_price,
        				IF(P.productvariantgroup_id IS NOT NULL, PGLD.name, '') as gradelevels_display,
                  P.discount_level
        	FROM		
        				product as P
        				
        				LEFT JOIN product_description as PD
        					ON (P.product_id = PD.product_id AND PD.language_id = 1)
        				
        				INNER JOIN productset_product as PP
        					ON (P.product_id = PP.product_id)
        				INNER JOIN store_productsets as SPS
        					ON (PP.productset_id = SPS.productset_id)
        				INNER JOIN store as S
        					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
        				INNER JOIN store_product as SP
        					ON (P.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}') 

        				/*INNER JOIN store_product_price_2 as SPP
        					ON (P.product_id = SPP.product_id AND S.code = SPP.store_code)*/

        				LEFT JOIN product_gradelevels_display as PGLD
        					ON (P.product_id = PGLD.product_id)
        					        					
        	WHERE		1
        		AND		PD.name LIKE '{$search_string}%'
        	GROUP BY	P.product_id
        	ORDER BY	PD.name
        ";
  
        $result = $this->db->query($sql);
      
        foreach ($result->rows as $row) {

           $special = '';
            if ($row['discount_level']) {
               if ($this->customer->isSPS()) {
                  if ($district_discount = $this->customer->getSPS()->getDiscount($row['discount_level'])) {
                     $district_price = $row['unit_price']-($row['unit_price']*($district_discount*.01)); 
                     //if ($district_price < $special || !$special) {
                        $special = number_format($district_price, 2); 
                     //} 
                  }   
               } else {
                  // Bender retail.
                  if ($retail_discount = $this->customer->getDiscount($row['discount_level'])) {
                     $disc_retail_price = $row['unit_price']-($row['unit_price']*($retail_discount*.01));
                     if ($disc_retail_price < $special || !$special) {
                        $special = $disc_retail_price;
                     }   
                  }   
               }
            }


            $product_id = $row['product_id'];
            $product_name = $this->language->clean_string($row['product_name']);
            $unit_price = 0;
            if ($special) {
               $unit_price = $special;
            } else {
               $unit_price = $row['unit_price'];
            }
            $ext_product_num = $row['ext_product_num'];
            if ($row['gradelevels_display'] != '') {
                $gradelevels_display = " (".$row['gradelevels_display'].")";
            } else {
                $gradelevels_display = '';
            }            
            
            $output .= '{"tag":' . json_encode($product_name) . ', "id": '.$product_id. ', "product_row": "'.$product_row_num.'"' . ', "unit_price": "'.$unit_price.'"' . ', "ext_product_num": "'.$ext_product_num.'"}' . ((count($result->rows)-1)===$i?"":",");
            
            $i++;
        }
        
        $output = '[' . $output . ']';
        
        return $output;
        
    }
    
    
    public function get_typeahead_response_extproductnum ($store_code, $search_string, $product_row_num) {
        
        $search_string = mysql_real_escape_string(urldecode($search_string));

        if ($this->session->data['iamthebackend']) {
  	        $this->load->library('customer');
      	  $this->customer = new Customer();
        }
        
        $sql = "
        	SELECT		
        				P.product_id, PD.name as product_name, P.ext_product_num, 
                  IF(SP.price IS NOT NULL, SP.price, P.price) as unit_price,
                  IF(P.productvariantgroup_id IS NOT NULL, PGLD.name, '') as gradelevels_display,
                  P.discount_level
        	FROM		
        				product as P
        				
        				LEFT JOIN product_description as PD
        					ON (P.product_id = PD.product_id AND PD.language_id = 1)        				

        				INNER JOIN productset_product as PP
        					ON (P.product_id = PP.product_id)
        				INNER JOIN store_productsets as SPS
        					ON (PP.productset_id = SPS.productset_id)
        				INNER JOIN store as S
        					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
        				INNER JOIN store_product as SP
        					ON (P.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}') 
        					
        				/*INNER JOIN store_product_price_2 as SPP
        					ON (P.product_id = SPP.product_id AND S.code = SPP.store_code)	        					*/
      
        				LEFT JOIN product_gradelevels_display as PGLD
        					ON (P.product_id = PGLD.product_id)
        					        					
        	WHERE		1
        		AND		P.ext_product_num LIKE '{$search_string}%'
        	GROUP BY	P.product_id       
        	ORDER BY	P.ext_product_num 		
        ";
    
        $result = $this->db->query($sql);
   
        foreach ($result->rows as $row) {

            $special = '';
            if ($row['discount_level']) {
               if ($this->customer->isSPS()) {
                  if ($district_discount = $this->customer->getSPS()->getDiscount($row['discount_level'])) {
                     $district_price = $row['unit_price']-($row['unit_price']*($district_discount*.01)); 
                     //if ($district_price < $special || !$special) {
                        $special = number_format($district_price, 2); 
                     //} 
                  }   
               } else {
                  // Bender retail.
                  if ($retail_discount = $this->customer->getDiscount($row['discount_level'])) {
                     $disc_retail_price = $row['unit_price']-($row['unit_price']*($retail_discount*.01));
                     if ($disc_retail_price < $special || !$special) {
                        $special = $disc_retail_price;
                     }   
                  }   
               }
            }

            $product_id = $row['product_id'];
            $product_name = $this->language->clean_string($row['product_name']);
            $unit_price = 0;
            if ($special) {
               $unit_price = $special;
            } else {
               $unit_price = $row['unit_price'];
            }
            $ext_product_num = $row['ext_product_num'];
            if ($row['gradelevels_display'] != '') {
                $gradelevels_display = " (".$row['gradelevels_display'].")";
            } else {
                $gradelevels_display = '';
            }
            
            $output .= '{"tag":"' . $ext_product_num . '", "id": '.$product_id. ', "product_row": "'.$product_row_num.'"' . ', "unit_price": "'.$unit_price.'"' . ', "product_name": '.json_encode($product_name).'}' . ((count($result->rows)-1)===$i?"":",");
            
            $i++;
                    
        }
        
        $output = '[' . $output . ']';
        
        return $output;
        
    }    
    
    
    public function debug ($value) {
        
        $data['value'] = $value;
        $this->db->add('debug', $data);
        
    }
	
}
?>
