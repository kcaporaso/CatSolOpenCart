<?php
$headers = (string)'';
$row = (string)'';
foreach($order_info as $header => $data){
	$headers .= $header . "\t";
	if(is_string($data)){
		$row .= '"' . str_replace('"', '""', $data) . '"' . "\t";
	} else {
		$row .= $data . "\t";	
	}
}

echo trim($headers) . "\r\n";
echo trim($row);
?>