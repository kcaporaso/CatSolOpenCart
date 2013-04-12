<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
  	<h3>Hello 
     <?php echo $this->user->getFirstName() . ' ' . $this->user->getLastName();
     if ($this->user->isSPS()) {
        echo ' (' . $this->user->getRoleName() . ')';
     }
     ?></h3>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
	<div align="center" class="ajax_loading_animation"><img style="padding:8px" src="<?php echo HTTP_SERVER ?>/view/image/ajax-loader.gif" /></div>    
    <table class="form">
      <tr>
        <td width="300"><strong>Notifications : Quick List</strong>
           <div class="notifications">
              <!-- Display things like pending orders, etc.... -->
              <?php foreach ($notifications as $k=>$v) { ?>
                 <strong><?php if ($k == 'orders_approved') { echo 'Awaiting Fulfilment'; } else { echo $k; }?></strong>
                 <ul> 
                 <?php
                 foreach ($v as $ok=>$ov) {
                   echo '<span style="color:red"><li><a href="'.$order_url.'&order_id='.$ov['order_id'] .'">'.$ov['name'].': '.$ov['order_id'].': ' .$ov['firstname'].' '.$ov['lastname'] . '</a></li></span>';
                 }
                 ?>
                 </ul>
              <?php }
              ?>
           </div>
        </td>
        <td style="border-left:1px solid #ccc">
         <div style="border-bottom:1px solid #ccc;"><strong>Nofications : Hierarchy</strong></div>
        	<table>
        		<?php $lone_store_checked = 'checked'; ?>
        		<?php /*foreach ($districts as $district): ?>
            		<?php 
            		    if (count($districts) == 1 || ($district['id'] == $_SESSION['district_id'])) {
            		        $this_store_checked = 'checked';
            		    } else {
            		        $this_store_checked = '';
            		    }
            		?>
            		<tr>
            			<td>
            				<!--input id="radio_store_code_<?php echo $district['id']; ?>" type="radio" name="district_id" value="<?php echo $district['id']; ?>" <?php echo $this_store_checked; ?> ></input-->
            			</td>
            			<td>
            				<label for="radio_store_code_<?php echo $district['id']; ?>"><strong><?php echo $district ['name']; ?></strong> </label>
                          <ul id="hierarchy-home" class="filetree"> 
                             <li class="closed"><span class="folder"><?php echo $district['name']?></span>
                             <?php foreach ($schools as $school) { ?>
                                   <ul>
                                   <li class="closed"><span class="folder"><?php echo $school['name']; ?></span>
                                            <?php if ($users) { ?>
                                            <ul><li><span class="folder">Users</span>
                                                <?php 
                                                  foreach ($users as $user) {
                                                     if ($user['school_id'] == $school['id']) {
                                                        echo '<ul>';
                                                        echo "<li><span class='file'><a href={$edit_user}&user_id=" . $user['user_id']."&cancel_url={$action}>" . $user['firstname'] . ' ' . $user['lastname'] . ' : ' . $user['username'] . ' : ' . $user['role_name'] . '</a></span>';
                                                        echo '</li>';
                                                        echo '</ul>';
                                                     }
                                                  }
                                                ?>
                                            </li></ul>
                                            <?php } else { ?>
                                               <ul><li><span class='file'>No Users Defined</span></li></ul>
                                            <?php } ?>

                                            <?php if ($orders) { ?>
                                            <ul><li><span class="folder">Orders</span>
                                                  <ul> 
                                                     <!-- orders shown here -->
                                                     <?php
                                                       foreach ($orders as $order) {
                                                          echo "<li><span class='file'>";
                                                          echo "<a href='" . $order_url . "&order_id={$order['order_id']}&cancel_url={$action}'>" . $order['order_status_name'] .' : '. $order['order_id'].' : '.$order['firstname'].' '.$order['lastname']."</a>";
                                                          echo "</span></li>";
                                                       }
                                                     ?>
                                                  </ul>
                                            </li>
                                            </ul>
                                            <?php } else { ?>
                                               <ul><li><span class='file'>No Orders</span></li></ul>
                                            <?php } ?>
                                   </ul>
                              <?php } ?>
                              </li>

                          </ul>
            			</td>
            		</tr>
        		<?php endforeach;*/ ?>
        	</table>
        </td>
      </tr>
    </table>
  	<!--div class="buttons">
  		<a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle">Submit</span><span class="button_right"></span></a>
  	</div-->
  </div>
  <div class="edit-area" id="edit-area">Edit Object</div>
</form>

<script>
 $(document).ready(function() { 
    $("#hierarchy-home").treeview({
       persist: "location", 
       collapsed: true,
       unique: true
    }); 
    $('.ajax_loading_animation').hide();
});

function buildit(key, value) {
   //alert(id);
   var input = '<div>' + key + ':<input type="' + key + '" value="' + value + '"></div>';
   return input;
}

function retrieve_object_data(type, id) {

    // type: district, school, user
    // id: database id of the object
    // call out to the controller and grab data...
    $('.ajax_loading_animation').show();

    //url = <?php echo $retrieve_object_data_url ?> + '&id=' + type;
    $("[name='id']").val(id);
    $("[name='type']").val(type);

/*
    $.getJSON('<?php echo $retrieve_object_data_url ?>'+'&id='+id+'&type='+type, function(json){ 
        alert(json.results);
        $.each(json.items, function(i, value) {
           $("#editme").html('<p>' + value + '</p>'); 
        });
     });
     */

    $('.ajax_loading_animation').hide();
    var input ="";
    $.post(
      
      '<?php echo $retrieve_object_data_url ?>', 
      //url,
      $('#form').serialize(), 
      
      function (result) {
         //alert(result);
         JSON.parse(result, function(key, value) {
            //alert(key + ': ' + value);
            //buildEditForm(key, value, id);
            if (typeof value != "object") {
               input += buildit(key, value);
               //alert(input);
            }

         });
         $("#edit-area").html(input);
         /*$.each(result, function(i, item){
            //alert(item);
         }) */;
         $('.ajax_loading_animation').hide();

         //clear_all_subtotal_rows();
         
         //data = eval('(' + result + ')');
         //alert(data.firstname);
         //for (i=0; i < subtotal_rows.length; i++) {
         //   addSubtotal(subtotal_rows[i].title, subtotal_rows[i].text);
         //}
         
         $("#edit-area").show();
         
      }
      
    );
 }
</script>
