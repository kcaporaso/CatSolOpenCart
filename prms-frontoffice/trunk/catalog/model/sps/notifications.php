<?php

// Notification Types we can ask about
define("PENDING_ORDERS_FOR_SCHOOL", 1);
define("REJECTED_ORDERS_FOR_SCHOOL", 2);
define("APPROVED_ORDERS_FOR_ALL_SCHOOLS", 3);
define("ALL_ORDERS_FOR_SCHOOL", 4);

class ModelSPSNotifications extends model {

   public function getNotifications($type, $obj_id) {

      $table = "";
      $where_field = "";
      $columns = "";
      $notifications = array();

      if ($type == PENDING_ORDERS_FOR_SCHOOL) {
         $table = "sps_order o";
         $where_field = "o.school_id = '{$obj_id}' AND o.order_status_id = 1";
         $join_table = "sps_order_status os";
         $join_on = "o.order_status_id = os.order_status_id";
         $columns = "o.order_id, o.firstname, o.lastname, o.order_status_id, o.total, o.date_added, os.name, o.waiting_on, su.firstname as waitfirstname, su.lastname as waitlastname, ss.name as schoolname ";
         $notifications['orders_pending'] = $this->getNotificationObjects($table, $where_field, $columns, $join_table, $join_on);
      }

      if ($type == REJECTED_ORDERS_FOR_SCHOOL) {
         $table = "sps_order o";
         $where_field = "o.school_id = '{$obj_id}' AND o.order_status_id = 11";
         $join_table = "sps_order_status os";
         $join_on = "o.order_status_id = os.order_status_id";
         $columns = "o.order_id, o.firstname, o.lastname, o.order_status_id, o.total, o.date_added, os.name, o.waiting_on, su.firstname as waitfirstname, su.lastname as waitlastname, ss.name as schoolname ";
         $notifications['orders_rejected'] = $this->getNotificationObjects($table, $where_field, $columns, $join_table, $join_on);
      }

      if ($type == ALL_ORDERS_FOR_SCHOOL) {
         $table = "sps_order o";
         $where_field = "o.school_id = '{$obj_id}' ";
         $join_table = "sps_order_status os";
         $join_on = "o.order_status_id = os.order_status_id";
         $columns = "o.order_id, o.firstname, o.lastname, o.order_status_id, o.total, o.date_added, os.name, o.waiting_on, su.firstname as waitfirstname, su.lastname as waitlastname, ss.name as schoolname ";
         $notifications['orders_all'] = $this->getNotificationObjects($table, $where_field, $columns, $join_table, $join_on);
      }

      // Called for a Super User to get all the pending orders for their district.
      if ($type == PENDING_ORDERS_FOR_DISTRICT) {
         // So first we need to get all the schools in the district.
         $query = $this->db->query("SELECT id FROM sps_school WHERE district_id='{$obj_id}'");
//         $schools = array("1","2");
         foreach ($query->rows as $r) {
            $schools[] = $r['id'];
         }
         $school_ids = implode(', ', $schools); 
         $table = "sps_order o";
         $where_field = "o.school_id IN ({$school_ids}) ";
         $join_table = "sps_order_status os";
         $join_on = "o.order_status_id = os.order_status_id";
         $columns = "o.order_id, o.firstname, o.lastname, o.order_status_id, o.total, o.date_added, os.name, o.waiting_on, su.firstname as waitfirstname, su.lastname as waitlastname, ss.name as schoolname ";
         $notifications['orders_pending'] = $this->getNotificationObjects($table, $where_field, $columns, $join_table, $join_on);
      }

      // Call from backoffice only.
      if ($type == APPROVED_ORDERS_FOR_ALL_SCHOOLS) {
         $table = "sps_order o";
         $where_field = "o.order_status_id = 12";
         $join_table = "sps_order_status os";
         $join_on = "o.order_status_id = os.order_status_id";
         $columns = "o.order_id, o.firstname, o.lastname, o.order_status_id, os.name ";
         $notifications['orders_approved'] = $this->getNotificationObjects($table, $where_field, $columns, $join_table, $join_on, false);
      }

      return $notifications;
   }

   private function getNotificationObjects($table, $where_stmt, $columns, $join_table, $join_on, $join_user=true, $join_school=true) {
      $return_array = array();

      $sql = "SELECT $columns FROM {$table} ";

      if (!empty($join_table)) {
         $sql .= " INNER JOIN {$join_table} ON {$join_on} ";
      }
      // join in the waiting_on user for notification purposes...
      if ($join_user) {
         $sql .= " INNER JOIN sps_user su ON o.waiting_on = su.user_id ";
      }

      if ($join_school) {
         $sql .= " INNER JOIN sps_school ss ON o.school_id = ss.id ";
      }

      $sql .= " WHERE {$where_stmt} ORDER BY o.order_id ASC";
//echo $sql;exit;
      $query = $this->db->query($sql);
//var_dump($query->rows); exit;       
      foreach ($query->rows as $row) {
         $row['date_added'] = date('m/d/Y', strtotime($row['date_added']));
         $return_array[] = $row;
      }
      return $return_array;
   }
}

?>
