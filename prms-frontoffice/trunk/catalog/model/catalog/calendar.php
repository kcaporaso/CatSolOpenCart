<?php
// DB Model for Events Calendar by Fido-X (http://www.fido-x.net)
class ModelCatalogCalendar extends Model {
	public function getCalendar() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "calendar c LEFT JOIN " . DB_PREFIX . "calendar_description cd ON (c.calendar_id = cd.calendar_id) WHERE cd.language_id = '" . (int)$this->language->getId() . "' AND c.status = '1' ORDER BY c.start_date ASC");
		return $query->rows;
	}

	public function getById($calendar_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "calendar c LEFT JOIN " . DB_PREFIX . "calendar_description cd ON (c.calendar_id = cd.calendar_id) WHERE c.calendar_id = '" . (int)$calendar_id . "' AND cd.language_id = '" . (int)$this->language->getId() . "'");
		return $query->row;
	}

	public function getAllEvents() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "calendar c LEFT JOIN " . DB_PREFIX . "calendar_description cd ON (c.calendar_id = cd.calendar_id) WHERE c.status = '1' AND cd.language_id = '" . (int)$this->language->getId() . "' ORDER BY c.start_date");
		return $query->rows;
	}

   public function getEvents($filter_data = array(), $store_code) {
   //query the database for events between the first date of the month and the last date of month
   $sql = "SELECT DATE_FORMAT(c.start_date,'%d') AS day,cd.description,cd.title FROM " . DB_PREFIX . "calendar c LEFT JOIN " . DB_PREFIX . "calendar_description cd ON (c.calendar_id = cd.calendar_id) WHERE c.start_date BETWEEN '" . $filter_data['current_year'] . "-" . $filter_data['current_month'] . "-01' AND '" . $filter_data['current_year'] . "-" . $filter_data['current_month'] . "-" . $filter_data['total_days_of_current_month'] . "' AND c.status='1' AND c.store_code='{$store_code}'";
   //echo $sql;
   $query = $this->db->query($sql);
   return $query->rows;
   }
}
?>
