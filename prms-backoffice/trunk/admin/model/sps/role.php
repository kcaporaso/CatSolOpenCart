<?php
class ModelSPSRole extends Model {
	public function addUserRole($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "sps_role SET role_name = '" . $this->db->escape(@$data['role_name']) . "', permission = '" . serialize(@$data['permission']) . "'");
	}
	
	public function editUserRole($role_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "sps_role SET role_name = '" . $this->db->escape(@$data['role_name']) . "', permission = '" . serialize(@$data['permission']) . "' WHERE id = '" . (int)$role_id . "'");
	}
	
	public function deleteUserRole($role_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "sps_role WHERE id = '" . (int)$role_id . "'");
	}

	public function addPermission($user_id, $type, $page) {
		$user_query = $this->db->query("SELECT DISTINCT role_id FROM " . DB_PREFIX . "sps_user WHERE user_id = '" . (int)$user_id . "'");
		
		if ($user_query->num_rows) {
			$user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "sps_role WHERE id = '" . (int)$user_query->row['role_id'] . "'");
		
			if ($user_group_query->num_rows) {
				$data = @unserialize($user_group_query->row['permission']);
		
				$data[$type][] = $page;
		
				$this->db->query("UPDATE " . DB_PREFIX . "sps_role SET permission = '" . serialize($data) . "' WHERE id = '" . (int)$user_query->row['role_id'] . "'");
			}
		}
	}
	
	public function getUserRole($role_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "sps_role WHERE id = '" . (int)$role_id . "'");
		
		$user_group = array(
			'role_name'       => $query->row['role_name'],
			'permission' => @unserialize($query->row['permission'])
		);
		
		return $user_group;
	}
	
	public function getUserRoles($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "sps_role";
		
		$sql .= " ORDER BY role_name";	
			
		if (@$data['order'] == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
			
		if (isset($data['start']) || isset($data['limit'])) {
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
			
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTotalUserRoles() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sps_role");
		
		return $query->row['total'];
	}	
}
?>
