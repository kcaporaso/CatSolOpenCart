<?php
// DB Model for Events Calendar by Fido-X (http://www.fido-x.net)
class ModelCatalogCalendar extends Model {
	public function addCalendarEvent($data, $store_code) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "calendar SET status = '" . (int)$this->request->post['status'] . "', start_date = '" . $this->request->post['start_date'] . "', interim_date = '" . $this->request->post['interim_date'] . "', end_date = '" . $this->request->post['end_date'] . "', image = '" . $this->db->escape(basename($data['image'])) ."', image_size = '" . (int)$this->request->post['image_size'] ."', store_code='" . $store_code . "'");
		$calendar_id = $this->db->getLastId(); 
		foreach (@$data['calendar_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "calendar_description SET calendar_id = '" . (int)$calendar_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', start_message = '" . $this->db->escape($value['start_message']) . "', interim_message = '" . $this->db->escape($value['interim_message']) . "', end_message = '" . $this->db->escape($value['end_message']) . "', store_code='{$store_code}'");
		}
		$this->cache->delete('calendar');
	}

	public function editCalendarEvent($calendar_id, $data, $store_code) {
		$this->db->query("UPDATE " . DB_PREFIX . "calendar SET status = '" . (int)$data['status'] . "', start_date = '" . $data['start_date'] . "', interim_date = '" . $data['interim_date'] . "', end_date = '" . $data['end_date'] . "', image = '" . $this->db->escape(basename($data['image'])) . "', image_size = '" . (int)$data['image_size'] . "' WHERE calendar_id = '" . (int)$calendar_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "calendar_description WHERE calendar_id = '" . (int)$calendar_id . "'");
		foreach (@$data['calendar_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "calendar_description SET calendar_id = '" . (int)$calendar_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', start_message = '" . $this->db->escape($value['start_message']) . "', interim_message = '" . $this->db->escape($value['interim_message']) . "', end_message = '" . $this->db->escape($value['end_message']) . "', store_code='{$store_code}'");
		}
		$this->cache->delete('calendar');
	}

	public function deleteCalendarEvent($calendar_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "calendar WHERE calendar_id = '" . (int)$calendar_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "calendar_description WHERE calendar_id = '" . (int)$calendar_id . "'");
		$this->cache->delete('calendar');
	}	

	public function getCalendarEvent($calendar_id, $store_code) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "calendar WHERE calendar_id = '" . (int)$calendar_id . "' AND store_code='{$store_code}'");
		return $query->row;
	}

	public function getCalendarDescriptions($calendar_id, $store_code) {
		$calendar_description_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "calendar_description WHERE calendar_id = '" . (int)$calendar_id . "' AND store_code='{$store_code}'");
		foreach ($query->rows as $result) {
			$calendar_description_data[$result['language_id']] = array(
				'title'           => $result['title'],
				'start_message'   => $result['start_message'],
				'interim_message' => $result['interim_message'],
				'end_message'     => $result['end_message'],
				'description'     => $result['description']
			);
		}
		return $calendar_description_data;
	}

	public function getList($data = array(), $store_code) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "calendar c LEFT JOIN " . DB_PREFIX . "calendar_description cd ON (c.calendar_id = cd.calendar_id) WHERE cd.language_id = '" . (int)$this->language->getId() . "' AND c.store_code='{$store_code}'";
			if (isset($data['sort'])) {
				$sql .= " ORDER BY " . $this->db->escape($data['sort']);	
			} else {
				$sql .= " ORDER BY cd.title";	
			}
			if (isset($data['order'])) {
				$sql .= " " . $this->db->escape($data['order']);
			} else {
				$sql .= " ASC";
			}
			if (isset($data['start']) || isset($data['limit'])) {
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			$query = $this->db->query($sql);
			return $query->rows;
		} else {
			$calendar_events = $this->cache->get('calendar.' . $this->language->getId());
			if (!$calendar_events) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "calendar c LEFT JOIN " . DB_PREFIX . "calendar_description cd ON (c.calendar_id = cd.calendar_id) WHERE cd.language_id = '" . (int)$this->language->getId() . "' AND store_code='{$store_code}' ORDER BY cd.title");
				$calendar_events = $query->rows;
				$this->cache->set('calendar.' . $this->language->getId(), $calendar_events);
			}	
			return $calendar_events;
		}
	}

	public function getAllEvents($store_code) {
		$calendar = $this->cache->get('calendar.' . $this->language->getId());
		if (!$calendar) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "calendar c LEFT JOIN " . DB_PREFIX . "calendar_description cd ON (c.calendar_id = cd.calendar_id) WHERE cd.language_id = '" . (int)$this->language->getId() . "' AND store_code='$store_code' ORDER BY cd.title");
			$calendar = $query->rows;
			$this->cache->set('calendar.' . $this->language->getId(), $calendar);
		}	
		return $calendar;
	}

	public function getTotalEvents($store_code) {
     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "calendar WHERE store_code='{$store_code}'");
		return $query->row['total'];
	}	
}
?>
