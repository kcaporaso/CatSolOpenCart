<?php

//require_once 'CsvIterator/CsvIterator.class.php';
require_once 'php-excel-reader-2/excel_reader2.php';
ini_set('display_errors','1');
class ModelToolSPSImport extends Model {


	function clean( &$str, $allowBlanks=FALSE ) {
		$result = "";
		$n = strlen( $str );
		for ($m=0; $m<$n; $m++) {
			$ch = substr( $str, $m, 1 );
			if (($ch==" ") && (!$allowBlanks) || ($ch=="\n") || ($ch=="\r") || ($ch=="\t") || ($ch=="\0") || ($ch=="\x0B")) {
				continue;
			}
			$result .= $ch;
		}
		return $result;
	}


	function import( &$database, $sql ) {
		foreach (explode(";\n", $sql) as $sql) {
			$sql = trim($sql);
			if ($sql) {
				$database->query($sql);
			}
		}
	}


	protected function detect_encoding( $str ) {
		// auto detect the character encoding of a string
		return mb_detect_encoding( $str, 'UTF-8,ISO-8859-15,ISO-8859-1,cp1251,KOI8-R' );
	}

	function upload($filename, $import_type) {
	    
	   $_SESSION['sps_importer']['count'] = 0;
	    
	   set_time_limit(14400);
      ini_set('max_input_time', '14400');
      ini_set('max_execution_time', '14400');
	    
		$database = Registry::get('db');		
		
//		$CsvIterator = new CsvIterator($filename, false, $delimiter="\t", '"');
      
      $CsvIterator = new Spreadsheet_Excel_reader($filename, false);
	
		$result['errors'] = array();
      $result['info'] = array(); // Want to report some details about what happened during the import.

		$ok = $this->validateUpload( $CsvIterator, $import_type, $output );	

		if (!$ok) {
			return $output;
		}

      //$this->load->model('user/store');
      //$store_codes = $this->model_user_store->get_store_codes($this->user->getID());		
      $store_codes = $_SESSION['store_code'];

      // KMC new category workings, if we are uploading a core_dataset then we associate all this new
      // data with store_code = 'ZZZ'.
		
      if ($_SESSION['sps_importer']['import_type']['states_selected'] || 
          $_SESSION['sps_importer']['import_type']['districts_selected'] || 
          $_SESSION['sps_importer']['import_type']['schools_selected'] || 
          $_SESSION['sps_importer']['import_type']['users_selected'] ||
          $_SESSION['sps_importer']['import_type']['chains_selected'] 
          ) 
      {
		    
		   /*$this->load->model('catalog/category');
         $this->load->model('catalog/manufacturer');
         $this->load->model('catalog/productvariantgroup');
         $this->load->model('catalog/product');
         $this->load->model('user/productset');	    
          */ 

    		$result_uploadData = $this->uploadData( $CsvIterator, $store_codes );

    		if ($result_uploadData) {
    			$result = array_merge($result, $result_uploadData);
    		}
		}

		/*if (empty($result['errors'])) {
		    return TRUE;
		} else {
		    return $result;
      }*/
      return $result;
	}

	function validateUpload( &$reader, $import_types, &$output ) {

      $output = array();

      if ($reader->getWorkbookVersion() != SPREADSHEET_EXCEL_READER_BIFF7 &&
          $reader->getWorkbookVersion() != SPREADSHEET_EXCEL_READER_BIFF8) {
          $output['errors'] = "We cannot read this version of Excel spreadsheet, please verify it is saved as 97/2000 format and try again.";
          return FALSE;
      }

      // Make sure all the import_types are in the boundsheets.
      $missing_sheets = array();
      $imported_sheets = array();
      foreach($reader->boundsheets as $sheets) {
         $imported_sheets[] = strtolower($sheets['name']);
      }
      $missing_sheets = array_diff($import_types, $imported_sheets);
      if (count($missing_sheets))
      {
         $output['errors'] = 'Missing "' . implode(",", $missing_sheets) . '" required sheet(s) from within excel document.';
         return FALSE;
      }

      $output['message'] = 'Found ' . count($imported_sheets) . ' Sheets in the imported file:<br/><ol>';
      foreach ($imported_sheets as $sheet) {
         $output['message'] .= '<li>'.$sheet.'</li>';
      }
      //var_dump($reader->boundsheets); exit;

      /*
		if (!$this->validateCategories( $reader )) {
			return FALSE;
		}
		*/
		return TRUE;
	}

	function uploadData( &$reader, $store_codes ) {

      $overall_result = array();
      $overall_result['info'] = array();;
      $overall_result['info']['states'] = array();;
      $overall_result['info']['users'] = array();;
      $overall_result['info']['schools'] = array();;
      $overall_result['info']['districts'] = array();;
      $overall_result['info']['chains'] = array();;

      //var_dump($reader->boundsheets);
      
      // We need in a certain order...  states, districts, schools, users
      $sheet_order = array();
      foreach ($reader->boundsheets as $pos => $sheet) {
         if (strtolower($sheet['name']) == 'states') {
            $sheet_order[0]['pos'] = $pos;
            $sheet_order[0]['name'] = 'states';
         }
         if (strtolower($sheet['name']) == 'districts') {
            $sheet_order[1]['pos'] = $pos;
            $sheet_order[1]['name'] = 'districts';
         }
         if (strtolower($sheet['name']) == 'schools') {
            $sheet_order[2]['pos'] = $pos;
            $sheet_order[2]['name'] = 'schools';
         }
         if (strtolower($sheet['name']) == 'users') {
            $sheet_order[3]['pos'] = $pos;
            $sheet_order[3]['name'] = 'users';
         }
         if (strtolower($sheet['name']) == 'chains') {
            $sheet_order[4]['pos'] = $pos;
            $sheet_order[4]['name'] = 'chains';
         }
      }
      ksort($sheet_order);
      $sheet_count = count($sheet_order);
      //var_dump($sheet_order);exit;
      //var_dump($sheet_order);
      //for ($s=0; $s < $sheet_count; $s++) {
      $districts = array();
      foreach ($sheet_order as $s) {
         
         $rows = $reader->rowcount($s['pos']);
         $cols = $reader->colcount($s['pos']);

         // Column Headers, we ignore 'em.
         for ($c=1; $c <= $cols; $c++) {
            //echo 'h:'. $c . ':' . $reader->val(1, $c, $s['pos']) . '<br/>';
            $key = $reader->val(1, $c, $s['pos']);
            for ($r=2; $r <= $rows; $r++) {
               // Load up our data array...
               $data[$s['name']][$r-2][$key] = $reader->val($r, $c, $s['pos']); 
            } 
         }
      }
      //var_dump($data['users']); exit;

	//	if (!empty($overall_result['errors'])) return $overall_result['errors'];
		
		if ($_SESSION['sps_importer']['import_type']['states_selected']) {
         $state_results = $this->insertStates($data['states']);
         if ($state_results) {
            $overall_result = array_merge($overall_result, $state_results);
         }
		}		
		
      // Handle our district import here.
		if ($_SESSION['sps_importer']['import_type']['districts_selected']) {
         $district_results = $this->insertDistricts($data['districts']);
         if ($district_results) {
            $overall_result = array_merge($overall_result, $district_results);
         }

		}

		if ($_SESSION['sps_importer']['import_type']['schools_selected']) {
         $school_results = $this->insertSchools($data['schools']);
         if ($school_results) {
            $overall_result = array_merge($overall_result, $school_results);
         }
		}

		if ($_SESSION['sps_importer']['import_type']['users_selected']) {
         $user_results = $this->insertUsers($data['users']);
         if ($user_results) {
            $overall_result = array_merge($overall_result, $user_results);
         }
		}
      
		if ($_SESSION['sps_importer']['import_type']['chains_selected']) {
         $chain_results = $this->insertChains($data['chains']);
    		if ($chain_results) {
    			$overall_result = array_merge($overall_result, $chain_results);
    		}
		}

      /*
		if (empty($overall_result['errors'])) {
		    return TRUE;
		} else {
		    return $overall_result;
		}
       */
      return $overall_result;
	}	

   function insertStates($states) {
      // Loop through and insert or update our states.
      $state_map = $this->getStateMap(); 

      $addCount = 0;
      $updateCount = 0;
      $state_results['info'] = array();
      foreach ($states as $state) {
//var_dump($state);
//var_dump($results);
         // Convert to our column struct
         $s = array(); // our clean district 
         $s['store_code'] = $_SESSION['store_code'];
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
            $updateCount++;
         } else { 
            // id does not exist, insert
            $this->db->add('sps_state', $s);
            $addCount++;
         }
      }

      $state_results['info']['updates'] = $updateCount;
      $state_results['info']['adds'] = $addCount;

      return $state_results;
   }

   function insertDistricts($districts) {
      // Loop through and insert or update our districts. 
      $district_map = $this->getDistrictMap(); 

      $addCount = 0;
      $updateCount = 0;
      $district_results['info'] = array();

      foreach ($districts as $district) {
         // Convert to our column struct
         $d = array(); // our clean district 
         $d['store_code'] = $_SESSION['store_code'];
         $d['customer_group_id'] = 67; // Bender specific hack.
         foreach($district as $key => $value) {
            if (array_key_exists($key, $district_map)) {
               if (strstr($key, 'date')) {
                  if (empty($value)) {
                     $d[$district_map[$key]] = date(ISO_DATETIME_FORMAT);
                  } else {
                     $d[$district_map[$key]] = date(ISO_DATETIME_FORMAT, strtotime($value));
                  }
               } elseif ($key == $this->reverseLookupKey($district_map,'name')) {
//                  var_dump($value);
                  if (strpos($value,",") == 2) { // This is for picking off the NC, SC, VA, in the import ClientName.
                     $state = substr($value, 0, 2);
//                     var_dump($state);
                     $state_id = $this->db->get_column("sps_state", "id", "name='{$state}'");
                     $d['state_id'] = $state_id;
                  } 
                  $d[$district_map[$key]] = $value;
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
            $updateCount++;
         } else { 
            // id does not exist, insert
            $this->db->add('sps_district', $d);
            $addCount++;
         }
      }
      $district_results['info']['updates'] = $updateCount;
      $district_results['info']['adds'] = $addCount;

      return $district_results;
//exit;
   }

   function insertSchools($schools) {
      // Loop through and insert or update our schools
      $school_map = $this->getSchoolMap(); 
//var_dump($school_map);
//var_dump($schools);
      $addCount = 0;
      $updateCount = 0;
      $school_results['info'] = array();

      foreach ($schools as $school) {
         // Convert to our column struct
         $s = array(); // our clean school
         $s['store_code'] = $_SESSION['store_code'];
         foreach($school as $key => $value) {
            if (array_key_exists($key, $school_map)) {
               if (strstr($key, 'date')) {
                  if (empty($value)) {
                     $s[$school_map[$key]] = date(ISO_DATETIME_FORMAT);
                  } else {
                     $s[$school_map[$key]] = date(ISO_DATETIME_FORMAT, strtotime($value));
                  }
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
            $updateCount++;
         } else { 
            // id does not exist, insert
            $this->db->add('sps_school', $s);
            $addCount++;
         }
      }

      $school_results['info']['updates'] = $updateCount;
      $school_results['info']['adds'] = $addCount;

      return $school_results;
   }

   function insertUsers($users) {
      // Loop through and insert or update our schools
      $user_map = $this->getUserMap(); 
//var_dump($user_map);
//var_dump($users);
      $addCount = 0;
      $updateCount = 0;
      $user_results['info'] = array();
      foreach ($users as $user) {
         // Convert to our column struct
         $u = array(); // our clean user
         $u['store_code'] = $_SESSION['store_code'];
         $u['customer_group_id'] = 67; // Default Bender customer_group_id, hack!
         foreach($user as $key => $value) {
            if (array_key_exists($key, $user_map)) {
               if (strstr($key, 'date')) {
                  if (empty($value)) {
                     $u[$user_map[$key]] = date(ISO_DATETIME_FORMAT);
                  } else {
                     $u[$user_map[$key]] = date(ISO_DATETIME_FORMAT, strtotime($value));
                  }
               } else if ($key == 'password' && !empty($value)) {
                  // md5 our password.
                  $u[$user_map[$key]] = md5($value);
               } else if ($key == 'password' && empty($value)) {
                  // empty password field, remove from the user so we don't clear our password on update.
               } else {
                  $u[$user_map[$key]] = $value;
               }
            }
         }
         $exists_q = "SELECT id FROM sps_user WHERE user_id='{$user[$this->reverseLookupKey($user_map,'user_id')]}'";
         //var_dump($exists_q);
         $results = $this->db->query($exists_q);
         if ($results->num_rows) {
            // id exists, update details
            $this->db->update('sps_user', $u, "id='{$results->row['id']}'");
            $updateCount++;
         } else { 
            // id does not exist, insert
            $this->db->add('sps_user', $u);
            $addCount++;
         }
      }

      $user_results['info']['updates'] = $updateCount;
      $user_results['info']['adds'] = $addCount;

      return $user_results;
   }

   function insertChains($chains) {
      $chain_map = $this->getChainMap();
      $store_code = $_SESSION['store_code'];
      $c = array(); // our clean chain
      $insert = true;
      $chain_results = array();
      $chain_results['info'] = array();
      $addCount = 0;
      $updateCount = 0;
      foreach ($chains as $chain) {

         if ($chain['existing_chain_id']=="") { echo 'blank row<br/>'; continue; }
         // build a clean chain
         // this is a row in the list of chains, a chain..
         foreach ($chain as $key => $value) {
            if ($key && $value) { // skip the empty keys (empty columns).
               // build each chain for insert or update.
               $c[$chain_map[$key]] = $value; 
            } 
         }
         // Now set some basics for each chain and then insert or update:
         $c['active'] = 1;
         $c['store_code'] = $store_code;

         if ($insert) {
            $c['create_date'] = date(ISO_DATETIME_FORMAT);
         } else {
            $c['modified_date'] = date(ISO_DATETIME_FORMAT);
         }

         // Now we stuff this chain into the DB, check for existing first.
         $exists = $this->db->query("SELECT id FROM sps_chain WHERE id='{$c['id']}' AND store_code='{$store_code}' AND school_id='{$c['school_id']}'");
         if ($exists->num_rows) {
            // UPDATE
            $insert = false;
         } 
         // check to see if we are for multiple school_ids
         $pos = strpos($c['school_id'], ',');
         if ($pos !== false) {
            // this chain applies to multiple schools so do extra processing here...
            $schools = explode(', ', $c['school_id']);
            $nc = array();
            $nc = $c;
            //var_dump($nc);
            //echo '<br/>';
            //continue;
            foreach ($schools as $school) {
               // build new chains for each school_id and add them
               // Only difference is the school_id.
               $nc['school_id'] = (int) $school;
               //var_dump($nc);
               //echo '<br/>';
               //continue;
               if (isset($nc['user_id_1'])) {
                  $user = $this->db->query("SELECT id FROM sps_user WHERE user_id='{$nc['user_id_1']}'");
                  if (!$user->num_rows) {
                     $chain_results['errors'][] = '<span style="color:#F00;">User ID: ' . $nc['user_id_1'] . ' does NOT exist for ' . $nc['name'] . '!!</span><br/>';
                  }
               } else {
                  $chain_results['errors'][] = 'Missing (ms) required user_id_1 for : ' . $nc['name'] . '<br/>';
               }

               if (isset($nc['school_id'])) {
                  $school = $this->db->query("SELECT id FROM sps_school WHERE id='{$nc['school_id']}'");
                  if (!$school->num_rows) {
                     $chain_results['errors'][] = '<span style="color:#F00;">School ID: ' . $nc['school_id'] . ' does NOT exist!!</span><br/>';
                  }
               } else {
                  $chain_results['errors'][] =  'Missing (ms) required school_id<br/>';
               }

               if ($insert) {
                  $this->db->add('sps_chain', $nc);
                  $addCount++;
                  //echo '>> insert ';
                  //var_dump($nc);
                  //echo '<br/>'; 
               } else {
                  $this->db->update('sps_chain', $nc, "id='{$nc['id']}' and school_id='{$nc['school_id']}'");
                  $updateCount++;
                  //echo '>> update ';
                  //var_dump($nc);
                  //echo '<br/>'; 
               }
            }
         } else {
            // single school_id no extra processing...
            // check for users/schools and that they exist.
            if (isset($c['user_id_1'])) {
               $user = $this->db->query("SELECT id FROM sps_user WHERE user_id='{$c['user_id_1']}'");
               if (!$user->num_rows) {
                  $chain_results['errors'][] = '<span style="color:#F00;">User ID: ' . $c['user_id_1'] . ' does NOT exist for ' . $c['name'] . '!!</span><br/>';
               }
            } else {
               $chain_results['errors'][] = 'Missing required user_id_1 for : ' . $c['name'] . '<br/>';
            }
            if (isset($c['school_id'])) {
               $school = $this->db->query("SELECT id FROM sps_school WHERE id='{$c['school_id']}'");
               if (!$school->num_rows) {
                  $chain_results['errors'][] = '<span style="color:#F00;">School ID: ' . $c['school_id'] . ' does NOT exist!!</span><br/>';
               }
            } else {
               $chain_results['errors'][] = 'Missing required school_id<br/>';
            }

            if ($insert) {
               //echo 'insert ' . $c['id'] . '<br/>';
               $this->db->add('sps_chain', $c);
               $addCount++;
            } else {
               //echo 'update ' . $c['id'] . '<br/>';
               $this->db->update('sps_chain', $c, "id='{$c['id']}' and school_id='{$c['school_id']}'");
               $updateCount++;
            }
         }
         
         unset($c); // clean out the reusable array.
         $insert = true; // reset insert.
      } // loop each chain

      $chain_results['info']['updates'] = $updateCount;
      $chain_results['info']['adds'] = $addCount;

      return $chain_results;
   }

   function reverseLookupKey($input, $value) {
      foreach ($input as $k => $i) {
         if ($i == $value) {
            return $k;
         }
      }
   }

   function getChainMap() {
      $chain_map = array(
         'existing_chain_id' => 'id',         
         'chain_name' => 'name',
         'user_id_1' => 'user_id_1',
         'user_id_2' => 'user_id_2',
         'user_id_3' => 'user_id_3',
         'user_id_4' => 'user_id_4',
         'user_id_5' => 'user_id_5',
         'school_id' => 'school_id'
      );
      return $chain_map;
   }

   function getStateMap() {
      $state_map = array(
              'id'=>'id',
              'name'=>'name',
              'active'=>'active');
      return $state_map;
   }

   function getDistrictMap() {
      $district_map = array(
              'ClientID'=>'id',
              'ClientName'=>'name',
              'isactive'=>'active',
              'IsTaxExempt'=>'tax_exempt',
              'TaxExemptionNo'=>'tax_exempt_number',
              'HasFreeShipping'=>'free_shipping',
              'minfreeshipamt'=>'free_freight_over',
              'create_date'=>'create_date',
              'mod_date'=>'modified_date',
              'discount_1'=>'discount_1',
              'discount_2'=>'discount_2',
              'discount_3'=>'discount_3',
              'discount_4'=>'discount_4');

      return $district_map;
   }   

   function getSchoolMap() {
      // spreadsheet column header => db column name
      $school_map = array(
              'LocationID'=>'id',
              'ClientID'=>'district_id',
              'ChainID'=>'approval_chain_id',
              'AdminID'=>'admin_id',
              'LocationName'=>'name',
              'IsActive'=>'active',
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
              'billing_firstname' => 'billing_firstname',
              'billing_lastname' => 'billing_lastname',
              'billing_address' => 'billing_address1',
              'billing_address2' => 'billing_address2',
              'billing_city' => 'billing_city',
              'billing_state' => 'billing_state',
              'billing_zip' => 'billing_zipcode',
              'billing_phone' => 'billing_phone',
              'autoapproveorder' => 'instant_approval',
              'create_date'=>'create_date',
              'modified_date'=>'modified_date');

      return $school_map;
   }

   function getUserMap() {
      $school_map = array(
              'UserID'=>'user_id',
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
              'mod_date'=>'modified_date',
              'instant_approval'=>'instant_approval',
              'notify_approval_via_email'=>'notify_approval_via_email');

      return $school_map;
   }

	public function merge_error_arrays ($cumulative_array, $additional_array) {
	    
	    foreach ($additional_array as $product_id=>$product_errors) {
	        foreach ($product_errors as $error_index => $error_value) {
	            $cumulative_array[$product_id][] = $error_value;
	        }
	    }

	    return $cumulative_array;
	}

	public function get_file_extension ($fileName) {
	    return substr($fileName, strrpos($fileName, '.') + 1);
	}
}
?>
