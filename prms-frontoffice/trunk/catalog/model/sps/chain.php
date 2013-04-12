<?php
class ModelSPSChain extends Model {

   // function to get Approver's information for a school.
   public function getApproversForSchool($school_id, $store_code) {

      $chain_info = $this->db->query("SELECT user_id_1, user_id_2, user_id_3, user_id_4, user_id_5 FROM sps_chain WHERE school_id='{$school_id}' AND store_code='{$store_code}'");
      if ($chain_info->num_rows) {
         foreach($chain_info->rows[0] as $k => $v) {
            if (strstr($k, 'user_id_')) {
               if ($v != 0 && $v != -1) {
                  $user_info = $this->db->query("SELECT user_id, username, firstname, lastname, email, role_id, notify_approval_via_email FROM sps_user WHERE user_id = '{$v}'");
                  $approvers[] = $user_info->rows[0];
               }
            }
         }
      }
      return $approvers;
   }

   // Came over from the backoffice.
   public function getApprovalChainUsers($school_id) {

      $sql = "SELECT * FROM sps_chain WHERE school_id='{$school_id}'";
      $chain = $this->db->query($sql);
      $users = array();
      if ($chain->num_rows) {
         foreach ($chain->row as $k => $v) {
            if (strstr($k, 'user_id_')) {
               if ($v != 0 && $v != -1) {
                  $user = $this->db->query("SELECT u.user_id, u.username, u.firstname, u.lastname, u.role_id, u.email, u.notify_approval_via_email, r.role_name FROM sps_user u INNER JOIN sps_role r ON u.role_id = r.id WHERE user_id='{$v}'"); 
                  if ($user->num_rows) {
                     $users[] = $user->row;
                  }
               }
            }
         }
      }
      return $users;
   }

   // Came over from the backoffice.
   public function whoApprovesNext($school_id) {
      // Pull this chain, then determine who goes next, if anyone.
      $approvers = $this->getApprovalChainUsers($school_id);
      $just_approved_user_id = $this->customer->getSPS()->getUserID();
      $just_approved_role_id = $this->customer->getSPS()->getRoleID();
      $chain_info = $this->db->query("SELECT * FROM sps_chain WHERE school_id='{$school_id}'");

      if ($chain_info->num_rows) {
         foreach ($chain_info->row as $k => $v) {
            if (strstr($k, 'user_id_')) {
               if ($just_approved_user_id == $v) {
                  // determine which pos in the chain this is:
                  $_pos = strrpos($k, '_');
                  $chain_pos = substr($k,$_pos+1);
                  //echo $chain_pos;
                  if ($approvers[$chain_pos]['role_id'] < $just_approved_role_id) {
                     //echo ' we have more in the chain..';
                     return $approvers[$chain_pos];
                  }
               }
            }
         }
      }
   }
}
?>
