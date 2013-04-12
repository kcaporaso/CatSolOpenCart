<?php
class ModelReportReport extends Model {
	public function getProductViewedReport($start = 0, $limit = 20) {
		$total = 0;

		$product_data = array();
		
		$query = $this->db->query("SELECT SUM(viewed) AS total FROM " . DB_PREFIX . "product");

		$total = $query->row['total'];
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->language->getId() . "' ORDER BY viewed DESC LIMIT " . (int)$start . "," . (int)$limit);
		
		foreach ($query->rows as $result) {
			$product_data[] = array(
				'name'    => $result['name'],
				'model'   => $result['model'],
				'viewed'  => $result['viewed'],
				'percent' => round(($result['viewed'] / $total) * 100, 2) . '%'
			);
		}
		
		return $product_data;
	}	
	
	public function getProductPurchasedReport($start = 0, $limit = 20) {
		$query = $this->db->query("SELECT op.name, op.model, SUM(op.quantity) AS quantity, SUM(op.total + op.tax) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) WHERE o.order_status_id > '0' GROUP BY model ORDER BY total DESC LIMIT " . (int)$start . "," . (int)$limit);
	
		return $query->rows;
	}

	
	public function getSaleReport ($store_code, $data = array()) {
	    
      if ($this->user->isSPS()) {
		  $sql = "
			SELECT 		MIN(date_added) AS date_start, MAX(date_added) AS date_end, COUNT(*) AS orders, SUM(total) AS total 
			FROM `" . DB_PREFIX . "sps_order` 
			WHERE 		1
				AND		order_status_id > '0'
				AND		store_code = '{$store_code}'
		  "; 

      } else {
		  $sql = "
			SELECT 		MIN(date_added) AS date_start, MAX(date_added) AS date_end, COUNT(*) AS orders, SUM(total) AS total 
			FROM `" . DB_PREFIX . "order` 
			WHERE 		1
				AND		order_status_id > '0'
				AND		store_code = '{$store_code}'
		  "; 
      }
		
		if (isset($data['date_start'])) {
			$date_start = date('Y-m-d', strtotime($data['date_start']));
		} else {
			$date_start = date('Y-m-d', strtotime('-7 day'));
		}

		if (isset($data['date_end'])) {
			$date_end = date('Y-m-d', strtotime($data['date_end']));
		} else {
			$date_end = date('Y-m-d', time());
		}
		
		$sql .= " AND (DATE(date_added) >= '" . $this->db->escape($date_start) . "' AND DATE(date_added) <= '" . $this->db->escape($date_end) . "')";
		
		if (@$data['order_status_id']) {
			$sql .= " AND order_status_id = '" . (int)$data['order_status_id'] . "'";
		}
		
		if (@$data['payment_method']) {
			$sql .= " AND payment_method = '" . $data['payment_method'] . "'";
		}		
		
		switch(@$data['group']) {
			case 'day';
				$sql .= " GROUP BY DAY(date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY WEEK(date_added)";
				break;	
			case 'month':
				$sql .= " GROUP BY MONTH(date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(date_added)";
				break;									
		}

		if (isset($data['start']) || isset($data['limit'])) {
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
	
		$query = $this->db->query($sql);
		
		return $query->rows;
		
	}	
	
	
	public function get_sale_report_order_export ($store_code, $data = array()) {
	    
		$sql = "
            select
            		O.order_id,
            		O.date_added as order_date,
            		O.date_modified as modified_date,
            		O.payment_method,
            		O.shipping_method,
            		C.email,
            		C.telephone,
            		C.fax,
            		OAC.payment_address,
            		OAC.shipping_address,
            		O.total as order_total,
            		OS.name as order_status
            from
            		`order` as O
            
            		INNER JOIN customer as C
            			ON (O.customer_id = C.customer_id)
            
            		INNER JOIN order_address_concat as OAC
            			ON (O.order_id = OAC.order_id)
            			
            		LEFT JOIN order_status as OS
            			ON (O.order_status_id = OS.order_status_id AND OS.language_id = 1)
            where		1
            	AND	O.order_status_id > '0'
            	AND	O.store_code = '{$store_code}'
		"; 
		
		if (isset($data['date_start'])) {
			$date_start = $data['date_start'];
		} else {
			$date_start = date('Y-m-d', strtotime('-7 day'));
		}

		if (isset($data['date_end'])) {
			$date_end = $data['date_end'];
		} else {
			$date_end = date('Y-m-d', time());
		}
		
		$sql .= " AND (DATE(O.date_added) >= '" . $this->db->escape($date_start) . "' AND DATE(O.date_added) <= '" . $this->db->escape($date_end) . "')";
		
		if (@$data['order_status_id']) {
			$sql .= " AND O.order_status_id = '" . (int)$data['order_status_id'] . "'";
		}
		
		if (@$data['payment_method']) {
			$sql .= " AND O.payment_method = '" . $data['payment_method'] . "'";
		}		
		
		$sql .= " GROUP BY O.order_id";

		if (isset($data['start']) || isset($data['limit'])) {
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
	
		$query = $this->db->query($sql);
		
		foreach ((array)$query->rows as $key=>$row) {
		    $row['order_date'] = date(ISO_DATE_FORMAT, strtotime($row['order_date']));
		    $row['modified_date'] = date(ISO_DATE_FORMAT, strtotime($row['modified_date']));
		    $final_result[$key] = $row;
		}
		
		return $final_result;
		
	}		
	
	
	public function getTotalOrderedProducts() {
      	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` GROUP BY model");
		
		return $query->num_rows;
	}
	
	public function getSaleReportTotal($data = array()) {

      if ($this->user->isSPS()) {
		   $sql = "SELECT MIN(date_added) AS date_start, MAX(date_added) AS date_end, COUNT(*) AS orders, SUM(total) AS total FROM `" . DB_PREFIX . "sps_order` WHERE order_status_id > '0'";
      } else {
		   $sql = "SELECT MIN(date_added) AS date_start, MAX(date_added) AS date_end, COUNT(*) AS orders, SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0'";
      }
		
		if (isset($data['date_start'])) {
			$date_start = date('Y-m-d', strtotime($data['date_start']));
		} else {
			$date_start = date('Y-m-d', strtotime('-7 day'));
		}

		if (isset($data['date_end'])) {
			$date_end = date('Y-m-d', strtotime($data['date_end']));
		} else {
			$date_end = date('Y-m-d', strtotime($date_start));
		}
		
		$sql .= " AND (DATE(date_added) >= '" . $this->db->escape($date_start) . "' AND DATE(date_added) <= '" . $this->db->escape($date_end) . "')";
		
		if (@$data['order_status_id']) {
			$sql .= " AND order_status_id = '" . (int)$data['order_status_id'] . "'";
		}
		
		switch(@$data['group']) {
			case 'day';
				$sql .= " GROUP BY DAY(date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY WEEK(date_added)";
				break;	
			case 'month':
				$sql .= " GROUP BY MONTH(date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(date_added)";
				break;									
		}

		$query = $this->db->query($sql);

		return $query->num_rows;	
	}
}
?>
