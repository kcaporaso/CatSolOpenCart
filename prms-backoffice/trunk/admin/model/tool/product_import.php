<?php
class ModelToolProductImport extends Model {

	private $processed_images = array();

	public function isValidUpload($upload){
		if(isset($upload['tmp_name']) && is_uploaded_file($upload['tmp_name'])){
			return true;
		}
		return false;
	}
	
	public function uploadHasErrors($upload) {
		switch($upload['error']):
			case UPLOAD_ERR_OK:
				return false;
				break;
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				return 'Uploaded file was too large';
				break;
			case UPLOAD_ERR_PARTIAL:
			case UPLOAD_ERR_NO_FILE:
			case UPLOAD_ERR_NO_TMP_DIR:
			case UPLOAD_ERR_CANT_WRITE:
			case UPLOAD_ERR_EXTENSION:
				return 'There was an error uploading your product data.  Please try again.';
				break;
		endswitch;
	}
	
	public function getUploadProductDataCSV($upload){
		$data = array();

		require_once('CsvIterator/CsvIterator.class.php');
		$CsvIterator = new CsvIterator($upload['tmp_name'], false, $delimiter="\t", '"');
        
		while ($CsvIterator->next()) {
			$row = $CsvIterator->current();
			if($row[0] === 'Product Number'){continue;} // Assume header row SKIP
			array_unshift($row, ''); // Make array index match datafiles column index (blank 0 index)
			$data[] = $this->scrubUploadRow($row);
		}
				
		return $data;

	}
	
	public function getUploadProductDataXLS($upload){
		$data = array();
		
		require_once 'Spreadsheet/Excel/Reader.php';
		$reader=new Spreadsheet_Excel_Reader();
		$reader->setUTFEncoder('iconv');
		$reader->setOutputEncoding('UTF-8');
		$reader->read($upload['tmp_name']);
		foreach($reader->sheets[0]['cells'] as $row){
			if($row[1] === 'Product Number'){continue;} // Assume header row SKIP
			$data[] = $this->scrubUploadRow($row);
		}
		return $data;
	}
	
	public function scrubUploadRow($row){
		$data = array();
		$data['ext_product_num'] = ($row[1] != '') ? $row[1] : "";
		$data['name'] = ($row[2] != '') ? htmlentities( $row[2], ENT_QUOTES, $this->detect_encoding($row[3]) ) : "";
		$data['description'] = ($row[3] != '') ? htmlentities( $row[3], ENT_QUOTES, $this->detect_encoding($row[4]) ) : "";
		$data['price'] = ($row[4] != '') ? $this->clean($row[4], false,'$') : "";
		$gradelevels = ($row[5] != '') ? $row[5] : "";
		$gradelevels = $this->clean($gradelevels, true);
		$data['gradelevels'] = ($gradelevels=='') ? array() : explode( "-", $gradelevels );
		if ($data['gradelevels']===FALSE) {
			$data['gradelevels'] = array();
		}
		$data['keywords'] = ($row[6] != '') ? $this->clean($row[6], true) : "";
		$data['category_phrasekey'] = ($row[7] != '') ? $this->clean($row[7], true) : "";
		$data['main_image'] = ($row[8] != '') ? $this->clean($row[8], true) : "";
		// Stripping any paths out of field leaving us with just the filename.ext
		$parts = pathinfo($data['main_image']);
		$data['main_image'] = $parts['basename'];
		$data['manufacturer_name'] = ($row[9] != '') ?  $this->clean($row[9], true) : "";
		$data['safetywarning_choking_flag'] = ($row[10] != '') ? '1' : "";
		$data['safetywarning_balloon_flag'] = ($row[11] != '') ? '1' : "";
		$data['safetywarning_marbles_flag'] = ($row[12] != '') ? '1' : "";
		$data['safetywarning_smallball_flag'] = ($row[13] != '') ? '1' : "";

		return $data;
	}

	public function processUploadedMedia(){
		// NOTE;  Since we are uploading to a staging area, we are going to overwrite existing files to save us the headache of tracking renamed files.
		// We do this by passing true as the second option to handleUpload().
		$this->checkMediaUploadLocation();
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array('jpg','gif','png');
		// max file size in bytes
		$sizeLimit = 2 * 1024 * 1024;
		
		require_once('AjaxUploader/AjaxUploader.class.php');
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload(DIR_IMAGE."custom/{$_SESSION['store_code']}/uploads/", true);
		
		// to pass data through iframe you will need to encode all html tags
		return htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}
	
	public function checkMediaUploadLocation(){
		//Make sure the directories we need to write to exist.  Create if not.
		if(!is_dir(DIR_IMAGE."custom/{$_SESSION['store_code']}") ){
			@mkdir(DIR_IMAGE."custom/{$_SESSION['store_code']}");
		}
		if(!is_dir(DIR_IMAGE."custom/{$_SESSION['store_code']}/uploads") ){
			@mkdir(DIR_IMAGE."custom/{$_SESSION['store_code']}/uploads");
		}
		// Create dir under cache so Iamge Helper will function.
		if(!is_dir(DIR_IMAGE."cache/custom/{$_SESSION['store_code']}") ){
			@mkdir(DIR_IMAGE."cache/custom/{$_SESSION['store_code']}");
		}
	}
	
	public function saveImport($data){
		$store_code = $_SESSION['store_code'];
		// Follow the steps laid out in the original importer as much as possible
		$this->load->model('catalog/category');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/product');
		$this->load->model('user/productset');	    
		$this->load->model('productset/product');
		$this->load->model('store/product');

		// Custom import goes into catalog productset with same code as store
		$productset_id = $this->model_user_productset->getProductsetIDFromCode($store_code);
		if(!$productset_id){			
			// No custom catalog for store found Creating
			$productset_id = $this->createProductsetFromStoreCode();
			$store_productsets_data['store_id'] = $this->model_user_store->getStoreIDFromCode($store_code);
			$store_productsets_data['productset_id'] = $productset_id;
			$this->db->add('store_productsets', $store_productsets_data);
		}
		if(!$productset_id){
			// Still no productset! Sanity Stop!  FIX: Better error erporting
			echo "Error: Unable to create catalog for the import<br>";
			exit();			
		}
		
		$distinct_categories = array();
		$distinct_manufacturers = array();
		foreach($data as $product){
			$distinct_manufacturers[$product['manufacturer_name']] = 1;
			$distinct_categories[$product['category_phrasekey']] = 1;
		}
		// A : Manufacturers
		foreach ($distinct_manufacturers as $manufacturer_name=>$nothing) {     
			$this->model_catalog_manufacturer->add_manufacturer_record_if_not_exists($manufacturer_name);
		}

		// B : Categories
		foreach ($distinct_categories as $phrasekey=>$nothing) {
			// Following current system so rebuild functions work.  Add cats to ZZZ store for defaults
			$this->model_catalog_category->add_category_record_if_not_exists('ZZZ', $phrasekey, false, $productset_id);
			$this->model_catalog_category->assign_category_record_parent('ZZZ', $phrasekey, false, $productset_id);
			// Cleaning current productset cats and rebuild, Solves partial built cats
			$this->db->delete('category',"store_code = '{$store_code}' AND productset_id = '{$productset_id}'");
			$this->model_catalog_category->createStoreCategoriesIfNeeded($store_code, $productset_id);
		}
		
		// C : Products
		$results =  $this->storeProductsIntoDatabase($data, $store_code, $productset_id);

		// Final Rebuild any associations that need it.
		// Need all productsets to build the prod->cat assoc
		$psets = $this->model_user_productset->getProductsetsForStoreCode($store_code);
		foreach($psets as $pset){
			$productset_ids[] = $pset['productset_id'];
		}
		$this->model_productset_product->buildProductToCategoryAssociations($store_code, $productset_ids);
		$this->model_store_product->createUnjunctionedProductRecords($store_code);
		
		// Clear Cache
		$this->clearCache();
	}
	
	public function validateImport($data){
		// Check to make sure we have what we need to have.
		// Mainly empty required fields, but we can add anything here.
		// Should be called twice.  Once on verify to show errors ahead of time, again on finalize for sanity.
		$errors = array();
		if(!is_array($data)){
			$errors[] = 'Missing or Invalid Data!';
			return array('success'=>false, 'errors'=>$errors);
		}
		foreach($data as $index => $product){
			if(!$product['ext_product_num']){
				$errors[$index]['ext_product_num'] = 'Missing or invalid Product Number';
			}
			if(!$product['name']){
				$errors[$index]['name'] = 'Missing or invalid Product Name';
			}
			if(!$product['price'] || !is_numeric($product['price'])){
				$errors[$index]['price'] = 'Missing or invalid Product Price';
			}
			if(!$product['manufacturer_name']){
				$errors[$index]['manufacturer_name'] = 'Missing or invalid Manufacturer/Brand Name';
			}
			if(!$product['category_phrasekey']){
				$errors[$index]['category_phrasekey'] = 'Missing or invalid Category';
			}
			if(!$product['main_image']){
				$errors[$index]['main_image'] = 'Missing or invalid Image';
			}
		}
		if(!empty($errors)){
			return array('success'=>false, 'errors'=>$errors);
		}else{
			return array('success'=>true);
		}
				
	}
	
	private function storeProductsIntoDatabase($data, $store_code, $productset_id){
		foreach($data as $product){
			$result[] = $this->storeProductIntoDatabase($product, $store_code, $productset_id);
		}
		return $result;
	}
	
	private function storeProductIntoDatabase($product, $store_code, $productset_id){
		$language =& Registry::get('language');
		$language_id = $language->getId();
		$this->load->model('user/store');
	    $this->load->model('catalog/gradelevel');
	    $this->load->model('catalog/manufacturer');
	    $this->load->model('catalog/category');
	    $this->load->model('catalog/product');
		$store_owner_id = $this->model_user_store->getOwnerUserIDFromCode($store_code);
		// A: Build product dataset
		$product_core['user_id'] =  $store_owner_id;
		$product_core['ext_product_num'] = $product['ext_product_num'];
		$product_core['productset_id'] = $productset_id;
		$product_core['price'] = $product['price'];
		if ($product['gradelevels'][0] == 'All' || $product['gradelevels'][0] == '') {
			$product_core['min_gradelevel_id'] = $this->model_catalog_gradelevel->get_gradelevel_id_from_name('Birth');
			$product_core['max_gradelevel_id'] = $this->model_catalog_gradelevel->get_gradelevel_id_from_name('Adult');	            
		} else {
			$product_core['min_gradelevel_id'] = $this->model_catalog_gradelevel->get_gradelevel_id_from_name($product['gradelevels'][0]);
			$product_core['max_gradelevel_id'] = $this->model_catalog_gradelevel->get_gradelevel_id_from_name($product['gradelevels'][1]);	
		}
		$product_core['manufacturer_id'] = $this->model_catalog_manufacturer->get_id_from_name($product['manufacturer_name']);
		$product_core['safetywarning_choking_flag'] = $product['safetywarning_choking_flag'];
		$product_core['safetywarning_balloon_flag'] = $product['safetywarning_balloon_flag'];
		$product_core['safetywarning_marbles_flag'] = $product['safetywarning_marbles_flag'];
		$product_core['safetywarning_smallball_flag'] = $product['safetywarning_smallball_flag'];
		$product_core['date_modified'] = date(ISO_DATETIME_FORMAT);
    	$product_core['date_added'] = date(ISO_DATETIME_FORMAT);
		$product_core['productvariantgroup_id'] = '%NULL%'; // Product Variants not supported at this time
		$product_core['product_variation'] = "";
		$product_core['product_variant'] = "";	  
		$product_core['shipping_'] = 1; // Default Shippable Changing on import not supported at this time
		$product_description['meta_description'] = $product['keywords'];
		$product_description['language_id'] = $language_id;
		$product_description['name'] = $product['name'];
		$product_description['description'] = $product['description'];
		
		// B: Handle Images
		$product_core['image'] = $this->moveImageHome($product['main_image']);
		$product_core['image_for_alt_main_thumb'] = '%NULL%'; // ALT Main Thumbs not supported at this time
		
		
		// C: Get our Product ID and Save ? Add || Update
		$product_exists = $this->model_catalog_product->get_product_id_from_ext_product_num($product['ext_product_num'], $productset_id);
		if(!$product_exists){
			// Add
			// Special NOTE: Race Condition see $this->getNextAvailableID() for details
			// Andrea has assigned all ids above 99999999 for the wizard imports
			$product_id = $product_core['product_id'] = $this->getNextAvailableID();
			$this->db->add('product', $product_core);
		} else {
			//Update
			$product_id = $product_exists;
			$this->db->update('product', $product_core, "product_id = '{$product_id}' AND productset_id = '{$productset_id}'");
	        $this->db->delete('product_description', "product_id = '{$product_id}'");
		}
		$product_description['product_id'] = $product_id;
		$this->db->add('product_description', $product_description, true); 
		
		// D:  Productset->product Clean then add.
		$where_clause = "product_id = '{$product_id}' AND productset_id = '{$productset_id}'";
		$this->db->delete('productset_product', $where_clause);
		$productset_product_data['productset_id'] = $productset_id;
		$productset_product_data['product_id'] = $product_id;
		$productset_product_data['creator_user_id'] = $store_owner_id;
		$productset_product_data['created_datetime'] = date(ISO_DATETIME_FORMAT);
		$this->db->add('productset_product', $productset_product_data, true);

		// E: Default Product -> Category assignments Clean then add
		// Need store_code in where clause In this case we're working with default ZZZ
		// So that the rebuild functions continue to work when called and rebuild our data
		$where_clause .= " AND store_code='ZZZ'";
		$this->db->delete('product_to_category',$where_clause);
            
		$category_id = $this->model_catalog_category->get_id_from_phrasekey('ZZZ', $product['category_phrasekey'], $productset_id);
		if ($category_id) {
			$product_to_category_data = array(
				'store_code' => 'ZZZ',
				'product_id' => $product_id,
				'category_id' => $category_id,
				'productset_id' => $productset_id,
			);
			$result = $this->db->add('product_to_category',$product_to_category_data);
		}
		
		return;
	}
	
	private function getNextAvailableID(){
		// Andrea has assigned all ids above 99999999 for the wizard imports
		// We try and find the highest and return +1;
		// Nasty, but not sure what else to run without and auto incriment on that id
		// Were gonna pray we dont get multiple clients at the exact same time importing.
		$result = $this->db->query("SELECT MAX(product_id) AS maxid FROM product WHERE product_id > 99999999;");
		if($result->row['maxid']){
			return ++$result->row['maxid'];
		} else {
			// Will only ever happen on the first import, but we need it here
			return 100000000;	
		}
	}
	
	private function moveImageHome($imagename){
		// Move image out of stageing fixing its name.  return name + custom path for db insert.
		// resizer makes the image a jpg on upload, adding a second extension.  We clean that up here.
		if(array_key_exists($imagename,$this->processed_images)){
			// Already copied once this import, no need to again.
			return $this->processed_images[$imagename];
		}
		$parts = pathinfo($imagename);
		$newfile = "custom/{$_SESSION['store_code']}/".$parts['filename'].'.jpg';
		if (@copy(DIR_IMAGE."custom/{$_SESSION['store_code']}/uploads/{$imagename}.jpg", DIR_IMAGE.$newfile) ){
			//TODO Throw error if cant copy file
			require_once DIR_SYSTEM.'/helper/image.php';
			HelperImage::resize($newfile, '100', '100');
			$this->processed_images[$imagename] = $newfile; // Index file copy so we only copy an image once per import.
		}
		return $newfile;
		
	}
	
	private function createProductsetFromStoreCode(){
		$this->load->model('user/store');
		$this->load->model('user/productset');
		$data = array(
			'name'=>$this->model_user_store->getStoreNameFromCode($_SESSION['store_code']),
			'code'=>$_SESSION['store_code'],
			'user_id'=> $this->model_user_store->getOwnerUserIDFromCode($_SESSION['store_code'])
		);
		$this->model_user_productset->addProductset($data);
		return $this->model_user_productset->getProductsetIDFromCode($_SESSION['store_code']);
	}

	private function clearCache() {
		$this->cache->delete('category');
		$this->cache->delete('category_description');
		$this->cache->delete('manufacturer');
		$this->cache->delete('product');
		$this->cache->delete('product_image');
		$this->cache->delete('product_option');
		$this->cache->delete('product_option_description');
		$this->cache->delete('product_option_value');
		$this->cache->delete('product_option_value_description');
		$this->cache->delete('product_to_category');
		$this->cache->delete('url_alias');
	}
	
	protected function clean( $str, $allowBlanks=FALSE, $extra=null ) {
		// Add any unwanted characters to the dirty array to have them removed from the string
		$dirty = array("\n","\r","\t","\0","\x0B");
		if($extra){$dirty = array_merge($dirty,(array)$extra);}
		(!$allowBlanks) ? array_unshift($dirty, " ") : "";
		return str_replace($dirty,'',$str);
	}

	protected function detect_encoding( $str ) {
		// auto detect the character encoding of a string
		return mb_detect_encoding( $str, 'UTF-8,ISO-8859-15,ISO-8859-1,cp1251,KOI8-R' );
	}
}
?>