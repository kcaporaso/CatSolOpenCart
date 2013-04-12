<?php if ($customers) {
	echo "Name \t Email \t Address 1 \t Address 2 \t City \t State \t Postal code \t Telephone \t Fax \t Date Added \r\n";
	$row = (string)'';
	foreach($customers as $customer){
		echo '"'.$customer['name']."\"\t";
		echo '"'.$customer['email']."\"\t";
		echo '"'.$customer['address']['address_1']."\"\t";
		echo '"'.$customer['address']['address_2']."\"\t";
		echo '"'.$customer['address']['city']."\"\t";
		echo '"'.$customer['address']['zone']['name']."\"\t";
		echo '"'.$customer['address']['postcode']."\"\t";
		echo $customer['telephone']."\t";
		echo $customer['fax']."\t";
		echo '"'.$customer['date_added']."\"\r\n";
	}
	
} ?>