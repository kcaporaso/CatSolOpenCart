<?php 

    /*
     * 	This script will generate a preset Geo-Zone, named "Continental United States" containing the 50 States, for the active Store Code.
     * /
     */

    // Configuration
    require_once('../config.php');
    // Startup
    require_once(DIR_SYSTEM . 'startup.php');

    session_start();

    if (!$_SESSION['store_code']) {
        echo "Store Code required.<br><br>";
        exit;
    } else {
        echo "Starting script for Store Code {$_SESSION['store_code']}.<br><br>";
    }


    $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    $today_iso_date = date(ISO_DATETIME_FORMAT);
    
    
        
    if ($db->get_multiple('geo_zone', "store_code = '{$_SESSION['store_code']}' AND name = 'Continental United States' ")) {
        echo "Geo-Zone \"Continental United States\" already created.<br><br>";
        echo "Please click on the \"Back\" button on your browser.<br><br>";
        exit;
    }

    $sql = "
    	INSERT INTO		geo_zone
    	SET				store_code = '{$_SESSION['store_code']}',
    					name = 'Continental United States',
    					description = 'Continental United States',
    					date_added = '{$today_iso_date}'
    ";
    $db->query($sql);
    $geozone_id = $db->getLastId();


    $sql = "
    	SELECT		*
    	FROM		zone
    	WHERE		1
    		AND		country_id = 223	/* USA */
    		AND		code IN ('AL', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY')
    	ORDER BY	code    		
    ";
    $states = $db->query($sql);
    
    
    foreach ($states->rows as $state) {
        
        $sql = "
        	INSERT INTO		zone_to_geo_zone
        	SET				country_id = 223,
        					zone_id = {$state['zone_id']},
        					geo_zone_id = $geozone_id,
        					date_added = '{$today_iso_date}'
        ";
        $db->query($sql);    
        
    }


    echo "Script finished.<br><br>";
    echo "Please click on the \"Back\" button on your browser.<br><br>";

?>
