<?php
final class Tax {
    
    
	private $taxes = array();
	
	
	public function __construct ($order_id=null) {
	    	    
		if ($order_id) {    // most likely called from back-end	        
	        $this->session->data['shipping_address_id'] = $this->session->data['order_id_'.$order_id]['shipping_address_id'];  
	    }	    
	    
		$this->config = Registry::get('config');	
		$this->db = Registry::get('db');	
		$this->session = Registry::get('session');
      $this->customer = Registry::get('customer');

      $country_id = '';
      $zone_id = '';
      $address_data = '';
      if ($this->customer->isSPS()) {
         $address_data = $this->customer->getSPS()->getAddress((int)@$this->session->data['shipping_address_id']);
         $country_id = $address_data['country_id'];
         $z = $this->db->query("SELECT zone_id FROM zone WHERE country_id='{$country_id}' AND code='{$address_data['zone']}'");
         $zone_id = $z->row['zone_id'];
      } else {
   	$tax_class_query = $this->db->query("SELECT country_id, zone_id FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)@$this->session->data['shipping_address_id'] . "' AND customer_id = '" . (int)@$this->session->data['customer_id'] . "'");
   		
   	if ($tax_class_query->num_rows) {
   		$country_id = $tax_class_query->row['country_id'];
   		$zone_id = $tax_class_query->row['zone_id'];
   	} else {
   		$country_id = $this->config->get('config_country_id');
   		$zone_id = $this->config->get('config_zone_id');
   	}
      }

		$tax_rate_sql = "
			SELECT tr.tax_class_id, SUM(tr.rate) AS rate, tr.description, TC.taxrate_lookup_by_zipcode_flag
			FROM " . DB_PREFIX . "tax_rate tr 
				INNER JOIN tax_class as TC
					ON (tr.tax_class_id = TC.tax_class_id)
				LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz 
					ON (tr.geo_zone_id = z2gz.geo_zone_id) 
				LEFT JOIN " . DB_PREFIX . "geo_zone gz 
					ON (tr.geo_zone_id = gz.geo_zone_id) 
			WHERE 	1
				AND	(z2gz.country_id = '0' OR z2gz.country_id = '" . (int)$country_id . "') 
				AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$zone_id . "') 
				AND	(gz.store_code = '{$_SESSION['store_code']}')
			GROUP BY tr.tax_class_id
		";
		$tax_rate_query = $this->db->query($tax_rate_sql);
		foreach ($tax_rate_query->rows as $result) {
		    
		    if ($result['taxrate_lookup_by_zipcode_flag']==1) {
              if ($this->customer->isSPS()) {
		           $taxrate = $this->getTaxrateByZipcode($address_data['postcode']);
              } else {
		           $taxrate = $this->getTaxrateByZipcode($this->getAddressZipcode($this->session->data['shipping_address_id']));
              }
              //var_dump($this->session->data['shipping_address_id']);
              //var_dump($taxrate);
		    } else {
		        $taxrate = $result['rate'];
		    }
		    
          $this->taxes[$result['tax_class_id']] = array(
        		'rate'        => $taxrate,
        		'description' => $result['description']
          );
    	}
  	}
	
  	
  	public function calculate($value, $tax_class_id, $calculate = TRUE) {

		if (($calculate) && (isset($this->taxes[$tax_class_id])))  {
      		return $value + ($value * $this->taxes[$tax_class_id]['rate'] / 100);
    	} else {
      		return $value;
    	}
    	
  	}
  	
        
  	public function getRate($tax_class_id) {
  	    
    	return (isset($this->taxes[$tax_class_id]) ? $this->taxes[$tax_class_id]['rate'] : NULL);
    	
  	}
  
  	
  	public function getDescription($tax_class_id) {
  	    
		return (isset($this->taxes[$tax_class_id]) ? $this->taxes[$tax_class_id]['description'] : NULL);
		
  	}
  
  	
  	public function has($tax_class_id) {
  	    
		return isset($this->taxes[$tax_class_id]);
		
  	}
  	
  	
  	public function getAddressZipcode ($address_id) {
  	    
        $sql = "
        	SELECT		postcode
        	FROM		address
        	WHERE		1
        		AND		address_id = '{$address_id}'
        ";
        
        $query_result = $this->db->query($sql);
        
        return $query_result->row['postcode'];

  	}
  	
  	
	function getTaxrateByZipcode ($zipcode) {

       if (empty($zipcode)) { return; }
       $strServer = "db.zip2tax.com";
       $strDBUsername = "z2t_link";
       $strDBPassword = "H^2p6~r";
       $strDatabase = "zip2tax";
       
       //Open the connection
       $conn = mysql_connect($strServer, $strDBUsername, $strDBPassword, 0, 65536);
           //or 
       if (!$conn) { echo '<!-- connection failed -->'; }
       
       //Open the Database
       mysql_select_db($strDatabase, $conn);
           //or die ("Could not open database $strDatabase");
       
       //Set-up query variables
       $strZipCode = $zipcode;
       $strUserName = "catalogsol";
       $strUserPassword = "salestax";
       
       //Execute
       $result = mysql_query( "CALL zip2tax.z2t_lookup('" . $strZipCode . "','" . $strUserName . "', '" . $strUserPassword . "')" ); //or die ( mysql_error() );
       //Read the result
       while($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
          $taxrate = $row['Sales_Tax_Rate']; 
          /*echo "<!-- Zip Code: " . $row['Zip_Code'] . "--><br/>";
          echo "<!-- Sales Tax Rate: " . $row['Sales_Tax_Rate'] . "--><br/>";
          echo "<!-- Post Office City: " . $row['Post_Office_City'] . "--><br/>";
          echo "<!-- County: " . $row['County'] . "--><br/>";
          echo "<!-- State: " . $row['State'] . "--><br/>";
          echo "<!-- Shipping Taxable: " . $row['Shipping_Taxable'] . "--><br/>";*/
       }

       //Close the Database
       mysql_close($conn);

/*
	   $url = "http://www.zip2tax.com/Link/Lookup_CatalogSolutions_RateOnly.asp?pwd=sd27rc&zip={$zipcode}";
	    
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 	
		$result = curl_exec($ch);
		curl_close($ch);
*/
	   //echo '<!--TAX'.$taxrate.'-->';	
		return $taxrate;
		
	}  	
}
?>
