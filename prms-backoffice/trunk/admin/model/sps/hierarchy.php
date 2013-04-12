<?php
ini_set('display_errors','1');
class ModelSPSHierarchy extends Model {
// FOR REFERENCE ONLY: 
// get_multiple ($table, 
//               $where = null, $orderby = null, $limit = null, $startat = null, $additional_cols = null) 
        
   public function getStates($store_code, $active=1, $state_id=0) {
 
      $where = "store_code='{$store_code}'";
      if ($active) {
         $where .= " and active={$active}";
      }
      if ($state_id) {
         $where .= " and id='{$state_id}' ";
      }
      $results = $this->db->get_multiple("sps_state", $where);
      return $results;
   } 

   public function getDistricts($store_code, $state_id=0, $district_id=0) {

      $where = "store_code='{$store_code}'";

      if ($state_id) {
         if (is_array($state_id)) {
            $state_list = implode(", ", $state_id);
            $where .= " and state_id IN (". $state_list .") ";
         } else {
            $where .= " and state_id='{$state_id}' ";
         }
      }

      if ($district_id) {
         if (is_array($district_id)) {
            $district_list = implode(", ", $district_id);
            $where .= " and id IN (" . $district_list . ") ";
         } else {
            $where .= " and id='{$district_id}' ";
         }
      }
      $results = $this->db->get_multiple("sps_district", $where); 
      return $results;
   }

   public function getSchools($store_code, $district_id=0) {
      $where = " store_code='{$store_code}' ";
      if ($district_id) {
         if (is_array($district_id)) {
            $district_list = implode(", ", $district_id);
            $where .= " and district_id IN (" . $district_list . ") ";
         } else {
            $where .= " and district_id='{$district_id}'";
         }
      }
      $results = $this->db->get_multiple("sps_school", $where, "name");
      return $results;
   }

   public function getSchools2($store_code, $school_id=0) {
      $where = " store_code='{$store_code}' ";
      if ($school_id) {
         if (is_array($school_id)) {
            $school_list = implode(", ", $school_id);
            $where .= " and id IN (" . $school_list . ") ";
         } else {
            $where .= " and id='{$school_id}'";
         }
      }
      $results = $this->db->get_multiple("sps_school", $where, "name");
      return $results;
   }

   public function getUsers($store_code, $district_id=0, $school_id=0) {
      $where = " store_code='{$store_code}' ";
      if ($district_id) {
         $where .= " and u.district_id='{$district_id}' ";
      }

      if ($school_id) {
         $where .= " and u.school_id='{$school_id}' ";
      }

      //$results = $this->db->get_multiple("sps_user", $where, "lastname");
      $results = $this->db->query("SELECT u.*, r.role_name FROM sps_user u INNER JOIN sps_role r ON u.role_id = r.id WHERE 1 AND {$where} ORDER BY u.lastname");

      return $results->rows;
   }
   
   public function getUsers2($store_code, $user_id=0) {
      $where = " store_code='{$store_code}' ";
      if ($user_id) {
         if (is_array($user_id)) {
            $user_list = implode(", ", $user_id);
            $where .= " and u.user_id IN (" . $user_list . ") ";
         } else {
            $where .= " and u.user_id='{$user_id}' ";
         }
      }

      //$results = $this->db->get_multiple("sps_user", $where, "lastname");
      $results = $this->db->query("SELECT u.*, r.role_name FROM sps_user u INNER JOIN sps_role r ON u.role_id = r.id WHERE 1 AND {$where} ORDER BY u.lastname");

      return $results->rows;
   }

   public function getObjectData($type, $id) {
      $results = $this->db->get_multiple("sps_".$type, "id='{$id}'");
      return $results;
   }

   public function insertStates($states) {
      // Loop through and insert or update our states.
      $state_map = $this->getStateMap(); 

      foreach ($states as $state) {
//var_dump($state);
//var_dump($results);
         // Convert to our column struct
         $s = array(); // our clean district 
         foreach($state as $key => $value) {
            if (array_key_exists($key, $state_map)) {
               if (strstr($key, 'date')) {
                  $s[$state_map[$key]] = date(ISO_DATETIME_FORMAT, strtotime($value));
               } else {
                  $s[$state_map[$key]] = $value;
               }
            }
         }
//var_dump($s);
         $exists_q = "SELECT id FROM sps_state WHERE id='{$state[$this->reverseLookupKey($state_map,'id')]}'";
         $results = $this->db->query($exists_q);
         if ($results->num_rows) {
            // id exists, update details
            $this->db->update('sps_state', $s, "id='{$results->row['id']}'");
         } else { 
            // id does not exist, insert
            $this->db->add('sps_state', $s);
         }
      }
   }

   public function insertDistricts($districts) {
      // Loop through and insert or update our districts. 
      $district_map = $this->getDistrictMap(); 

      foreach ($districts as $district) {
//var_dump($district);
//var_dump($results);
         // Convert to our column struct
         $d = array(); // our clean district 
         foreach($district as $key => $value) {
            if (array_key_exists($key, $district_map)) {
               if (strstr($key, 'date')) {
                  $d[$district_map[$key]] = date(ISO_DATETIME_FORMAT, strtotime($value));
               } else {
                  $d[$district_map[$key]] = $value;
               }
            }
         }
//var_dump($d);
         $exists_q = "SELECT id FROM sps_district WHERE id='{$district[$this->reverseLookupKey($district_map,'id')]}'";
         $results = $this->db->query($exists_q);
         if ($results->num_rows) {
            // id exists, update details
            $this->db->update('sps_district', $d, "id='{$results->row['id']}'");
         } else { 
            // id does not exist, insert
            $this->db->add('sps_district', $d);
         }
      }
   }

   public function insertSchools($schools) {
      // Loop through and insert or update our schools
      $school_map = $this->getSchoolMap(); 
//var_dump($school_map);
//var_dump($schools);

      foreach ($schools as $school) {
         // Convert to our column struct
         $s = array(); // our clean school
         foreach($school as $key => $value) {
            if (array_key_exists($key, $school_map)) {
               if (strstr($key, 'date')) {
                  $s[$school_map[$key]] = date(ISO_DATETIME_FORMAT, strtotime($value));
               } else {
                  $s[$school_map[$key]] = $value;
               }
            }
         }

         $exists_q = "SELECT id FROM sps_school WHERE id='{$school[$this->reverseLookupKey($school_map,'id')]}'";
         $results = $this->db->query($exists_q);
         if ($results->num_rows) {
            // id exists, update details
            $this->db->update('sps_school', $s, "id='{$results->row['id']}'");
         } else { 
            // id does not exist, insert
            $this->db->add('sps_school', $s);
         }
      }
   }

   public function insertUsers($users) {
      // Loop through and insert or update our schools
      $user_map = $this->getUserMap(); 
//var_dump($user_map);
//var_dump($users);

      foreach ($users as $user) {
         // Convert to our column struct
         $u = array(); // our clean user
         $u['store_code'] = $_SESSION['store_code'];
         foreach($user as $key => $value) {
            if (array_key_exists($key, $user_map)) {
               if (strstr($key, 'date')) {
                  $u[$user_map[$key]] = date(ISO_DATETIME_FORMAT, strtotime($value));
               } else {
                  $u[$user_map[$key]] = $value;
               }
            }
         }
         $exists_q = "SELECT id FROM sps_user WHERE sps_user_id='{$user[$this->reverseLookupKey($user_map,'sps_user_id')]}'";
         //var_dump($exists_q);
         $results = $this->db->query($exists_q);
         if ($results->num_rows) {
            // id exists, update details
            $this->db->update('sps_user', $u, "id='{$results->row['id']}'");
         } else { 
            // id does not exist, insert
            $this->db->add('sps_user', $u);
         }
      }
   }


   public function updateUserState($user_id, $district_id) {
      $d_state = $this->db->query("SELECT state_id FROM sps_district WHERE id='{$district_id}'");
      $s_id = $d_state->row['state_id'];
      $results = $this->db->query("UPDATE sps_user SET state_id='{$s_id}' WHERE user_id='{$user_id}'");
      return $s_id;
   }

   private function reverseLookupKey($input, $value) {
      foreach ($input as $i) {
         if ($i = $value) {
            return key($input);
         }
      }
   }

   private function getStateMap() {
      $state_map = array(
              'StateID'=>'id',
              'StateName'=>'name');
      return $state_map;
   }

   private function getDistrictMap() {
      $district_map = array(
              'ClientID'=>'id',
              'ClientName'=>'name',
              'isactive'=>'active',
              'IsTaxExempt'=>'tax_exempt',
              'TaxExemptionNo'=>'tax_exempt_number',
              'HasFreeShipping'=>'free_shipping',
              'minfreeshipamt'=>'free_freight_over',
              'create_date'=>'create_date',
              'mod_date'=>'modified_date');

      return $district_map;
   }   

   private function getSchoolMap() {
      $school_map = array(
              'LocationID'=>'id',
              'ClientID'=>'district_id',
              'ChainID'=>'approval_chain_id',
              'AdminID'=>'admin_id',
              'LocationName'=>'name',
              'IsActive'=>'active',
              'IsHQ'=>'is_hq',
              'address' => 'address1',
              'address2' => 'address2',
              'city' => 'city',
              'state' => 'state',
              'county' => 'county',
              'country' => 'country',
              'zip' => 'zipcode',
              'phone' => 'phone',
              'fax' => 'fax',
              'email' => 'email',
              'url' => 'url',
              'autoapproveorder' => 'instant_approval',
              'create_date'=>'create_date',
              'mod_date'=>'modified_date');

      return $school_map;
   }

   private function getUserMap() {
      $school_map = array(
              'UserID'=>'sps_user_id',
              'RoleID'=>'role_id',
              'ClientID'=>'district_id',
              'LocationID'=>'school_id',
              'LocationName'=>'name',
              'isactive'=>'active',
              'fname' => 'firstname',
              'lname' => 'lastname',
              'email' => 'email',
              'uname' => 'username',
              'password' => 'password',
              'IsTaxExempt' => 'tax_exempt',
              'TaxExemptionNo' => 'tax_exempt_number',
              'HasFreeShipping' => 'free_shipping',
              'create_date'=>'create_date',
              'mod_date'=>'modified_date');

      return $school_map;
   }

	private function merge_error_arrays ($cumulative_array, $additional_array) {
	    
	    foreach ($additional_array as $product_id=>$product_errors) {
	        foreach ($product_errors as $error_index => $error_value) {
	            $cumulative_array[$product_id][] = $error_value;
	        }
	    }

	    return $cumulative_array;
	}


	function download() {
		$database =& Registry::get('db');
		$language =& Registry::get('language');
		$languageId = $language->getId();

		// We use the package from http://pear.php.net/package/Spreadsheet_Excel_Writer/
		require_once "Spreadsheet/Excel/Writer.php";
		
		// Creating a workbook
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->setTempDir(getcwd().'/../cache');
		$workbook->setVersion(8); // Use Excel97/2000 Format
		$priceFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '######0.00'));
		$boxFormat =& $workbook->addFormat(array('vAlign' => 'vequal_space' ));
		$weightFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '##0.00'));
		$textFormat =& $workbook->addFormat(array('Size' => 10, 'NumFormat' => "@" ));
		
		// sending HTTP headers
		$workbook->send('backup_categories_products.xls');
		
		// Creating the categories worksheet
		$worksheet =& $workbook->addWorksheet('Categories');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateCategoriesWorksheet( $worksheet, $database, $languageId, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Get all additional product images
		$imageNames = array();
		$query  = "SELECT DISTINCT ";
		$query .= "  p.product_id, ";
		$query .= "  pi.product_image_id AS image_id, ";
		$query .= "  pi.image AS filename ";
		$query .= "FROM `".DB_PREFIX."product` p ";
		$query .= "INNER JOIN `".DB_PREFIX."product_image` pi ON pi.product_id=p.product_id ";
		$query .= "ORDER BY product_id, image_id; ";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$productId = $row['product_id'];
			$imageId = $row['image_id'];
			$imageName = $row['filename'];
			if (!isset($imageNames[$productId])) {
				$imageNames[$productId] = array();
				$imageNames[$productId][$imageId] = $imageName;
			}
			else {
				$imageNames[$productId][$imageId] = $imageName;
			}
		}
		
		// Creating the products worksheet
		$worksheet =& $workbook->addWorksheet('Products');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateProductsWorksheet( $worksheet, $database, $imageNames, $languageId, $priceFormat, $boxFormat, $weightFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Creating the options worksheet
		$worksheet =& $workbook->addWorksheet('Options');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateOptionsWorksheet( $worksheet, $database, $languageId, $priceFormat, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Let's send the file
		$workbook->close();
		exit;
	}
	
	
	public function get_file_extension ($fileName) {
	    return substr($fileName, strrpos($fileName, '.') + 1);
	}
}
?>
