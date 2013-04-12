<?php

//require_once 'CsvIterator/CsvIterator.class.php';
require_once 'php-excel-reader-2/excel_reader2.php';
ini_set('display_errors','1');
class ModelToolSPSExport extends Model {

	function download() {
		$database =& Registry::get('db');
		$language =& Registry::get('language');
		$languageId = $language->getId();

		// We use the package from http://pear.php.net/package/Spreadsheet_Excel_Writer/
		require_once "Spreadsheet/Excel/Writer.php";
		
		// Creating a workbook
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->setTempDir(getcwd().'/../cache');
		$priceFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '######0.00'));
		$boxFormat =& $workbook->addFormat(array('vAlign' => 'vequal_space' ));
		$weightFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '##0.00'));
		$textFormat =& $workbook->addFormat(array('Size' => 10, 'NumFormat' => "@" ));
		
		// sending HTTP headers
		$workbook->send('SPS_easy_upload_export.xls');

      // Creating the states worksheet
		$worksheet =& $workbook->addWorksheet('States');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateStateWorksheet( $worksheet, $database, $languageId, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Creating the users worksheet
		$worksheet =& $workbook->addWorksheet('Users');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateUsersWorksheet( $worksheet, $database, $languageId, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Creating the districts worksheet
		$worksheet =& $workbook->addWorksheet('Districts');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateDistrictsWorksheet( $worksheet, $database, $languageId, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Creating the schools worksheet
		$worksheet =& $workbook->addWorksheet('Schools');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateSchoolsWorksheet( $worksheet, $database, $languageId, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));

		// Creating the chains worksheet
		$worksheet =& $workbook->addWorksheet('Chains');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateChainsWorksheet( $worksheet, $database, $languageId, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Let's send the file
		$workbook->close();
		exit;
	}

   private function populateStateWorksheet($wks, $db, $lang, $box, $text) {
      $states = $db->query("SELECT id, name, active FROM sps_state");
      $wks->write(0,0,"id");
      $wks->write(0,1,"name");
      $wks->write(0,2,"active");

      // Now stuff the sheet with user data.
      $col = 0;
      $row = 1;
      foreach ($states->rows as $state) {
         $col = 0;
         foreach ($state as $k => $v) {
            $wks->write($row, $col, ($v == 'NULL') ? '' : $v);
            $col++;
         } 
         $row++;
      }
   }

   private function populateUsersWorksheet($wks, $db, $lang, $box, $text) {
      $users = $db->query("SELECT user_id, role_id, district_id, school_id, active, firstname, lastname, email, username, tax_exempt, tax_exempt_number, free_shipping, instant_approval, notify_approval_via_email, create_date, modified_date FROM sps_user ORDER BY user_id");
      // Set the columns headers
      $wks->write(0,0,"UserID");
      $wks->write(0,1,"RoleID");
      $wks->write(0,2,"ClientID");
      $wks->write(0,3,"LocationID");
      $wks->write(0,4,"isactive");
      $wks->write(0,5,"fname");
      $wks->write(0,6,"lname");
      $wks->write(0,7,"email");
      $wks->write(0,8,"uname");
      $wks->write(0,9,"password");
      $wks->write(0,10,"IsTaxExempt");
      $wks->write(0,11,"TaxExemptionNo");
      $wks->write(0,12,"HasFreeShipping");
      $wks->write(0,13,"instant_approval");
      $wks->write(0,14,"notify_approval_via_email");
      $wks->write(0,15,"create_date");
      $wks->write(0,16,"mod_date");

      // Now stuff the sheet with user data.
      $col = 0;
      $row = 1;
      foreach ($users->rows as $user) {
         $col = 0;
         foreach ($user as $k => $v) {
            $wks->write($row, $col, ($v == 'NULL') ? '' : $v);
            $col++;
            if ($k == 'username') {
               $col+=1;
            }
         } 
         $row++;
      }
   }
	
   private function populateDistrictsWorksheet($wks, $db, $lang, $box, $text) {
      $districts = $db->query("SELECT id, name, active, tax_exempt, tax_exempt_number, free_shipping, free_freight_over, discount_1, discount_2, discount_3, discount_4, create_date, modified_date FROM sps_district ORDER BY id");
      // Set the columns headers
      $wks->write(0,0,"ClientID");
      $wks->write(0,1,"ClientName");
      $wks->write(0,2,"isactive");
      $wks->write(0,3,"IsTaxExempt");
      $wks->write(0,4,"TaxExemptionNo");
      $wks->write(0,5,"HasFreeShipping");
      $wks->write(0,6,"minfreeshipamt");
      $wks->write(0,7,"discount_1");
      $wks->write(0,8,"discount_2");
      $wks->write(0,9,"discount_3");
      $wks->write(0,10,"discount_4");
      $wks->write(0,11,"create_date");
      $wks->write(0,12,"mod_date");

      // Now stuff the sheet with district data.
      $col = 0;
      $row = 1;
      foreach ($districts->rows as $district) {
         $col = 0;
         foreach ($district as $k => $v) {
            $wks->write($row, $col, ($v == 'NULL') ? '' : $v);
            $col++;
         } 
         $row++;
      }
   }

   private function populateSchoolsWorksheet($wks, $db, $lang, $box, $text) {
      $schools = $db->query("SELECT id, district_id, approval_chain_id, name, active, address1, address2, city, state, country, zipcode, phone, fax, email, url, billing_firstname, billing_lastname, billing_address1, billing_address2, billing_city, billing_state, billing_zipcode, billing_phone, instant_approval, create_date, modified_date FROM sps_school ORDER BY id");
      // Set the columns headers
      $wks->write(0,0,"LocationID");
      $wks->write(0,1,"ClientID");
      $wks->write(0,2,"ChainID");
      $wks->write(0,3,"LocationName");
      $wks->write(0,4,"IsActive");
      $wks->write(0,5,"address");
      $wks->write(0,6,"address2");
      $wks->write(0,7,"city");
      $wks->write(0,8,"state");
      $wks->write(0,9,"country");
      $wks->write(0,10,"zip");
      $wks->write(0,11,"phone");
      $wks->write(0,12,"fax");
      $wks->write(0,13,"email");
      $wks->write(0,14,"url");
      $wks->write(0,15,"billing_firstname");
      $wks->write(0,16,"billing_lastname");
      $wks->write(0,17,"billing_address");
      $wks->write(0,18,"billing_address2");
      $wks->write(0,19,"billing_city");
      $wks->write(0,20,"billing_state");
      $wks->write(0,21,"billing_zip");
      $wks->write(0,22,"billing_phone");
      $wks->write(0,23,"instant_approval");
      $wks->write(0,24,"create_date");
      $wks->write(0,25,"modified_date");

      // Now stuff the sheet with school data.
      $col = 0;
      $row = 1;
      foreach ($schools->rows as $school) {
         $col = 0;
         foreach ($school as $k => $v) {
            $wks->write($row, $col, ($v == 'NULL') ? '' : $v);
            $col++;
         } 
         $row++;
      }
   }

   private function populateChainsWorksheet($wks, $db, $lang, $box, $text) {
      $chains = $db->query("SELECT id, name, user_id_1, user_id_2, user_id_3, user_id_4, user_id_5, school_id FROM sps_chain ORDER BY id");
      // Set the columns headers
      $wks->write(0,0,"existing_chain_id");
      $wks->write(0,1,"chain_name");
      $wks->write(0,2,"user_id_1");
      $wks->write(0,3,"user_id_2");
      $wks->write(0,4,"user_id_3");
      $wks->write(0,5,"user_id_4");
      $wks->write(0,6,"user_id_5");
      $wks->write(0,7,"school_id");

      // Now stuff the sheet with chain data.
      $col = 0;
      $row = 1;
      foreach ($chains->rows as $chain) {
         $col = 0;
         foreach ($chain as $k => $v) {
            $wks->write($row, $col, ($v == 'NULL') ? '' : $v);
            $col++;
         } 
         $row++;
      }
   }
}
?>
