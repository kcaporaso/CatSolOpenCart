<?php if ($error_warning) { ?>
<?php if (is_array($error_warning['errors'])): ?>
<?php foreach ($error_warning['errors'] as $error_message): ?>
<div class="warning">
  <?php echo $error_message; ?>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="warning">
  <?php echo $error_warning; ?>
</div>
<?php endif; ?>
<?php } ?>
<?php if ($success) { ?>
<div class="success">
  <?php echo $success; ?>
</div>
<?php } ?>
<div class="heading">
  <h1>
    <?php echo $heading_title; ?>
  </h1>
  <!--div class="buttons">
    <a onclick="$('#form').submit();" class="button">
      <span class="button_left button_restore"></span>
      <span class="button_middle">
        <?php echo $button_import; ?>
      </span>
      <span class="button_right"></span>
    </a>
    <a onclick="location='<?php echo $download_template; ?>'" class="button"><span class="button_left button_backup"></span><span class="button_middle">
        <?php echo $button_template; ?>
      </span><span class="button_right"></span>
    </a>
  </div-->
</div>
<p>
  <?php echo $entry_description; ?>
</p>
<div class="tabs">
  <a tab="#tab_hierarchy">
    <?php echo $tab_hierarchy; ?>
  </a>
</div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">  

  <div id="tab_hierarchy" class="page">
	 <div align="center" class="ajax_loading_animation"><img style="padding:8px" src="<?php echo HTTP_SERVER ?>/view/image/ajax-loader.gif" /></div>    
    <div id="editme"></div>
    
    <table class="form">
      <tr>
        <td>
        <ul id="hierarchy" class="filetree">
          <li><span class="folder">States</span>
            <?php 
              $unassigned = array();
              foreach ($states as $state) {
                 echo '<ul>';
                 echo '<li><span class="folder">' . $state['id'] . ':' . $state['name'] . '</span>';
                    foreach ($districts as $district) {
                       if ($district['state_id'] == $state['id'] && $district['id'] != '1000') {
                          echo '<ul>';
                          echo "<li id='district_".$district['id']."'><span class='folder'>" . $district['id'] . ":" . $district['name'] . " <a id='district_click_".$district['id']."' onclick=retrieve_schools_for_district('".$district['id']."');>get schools</a></span>";
                                  
                             if ($schools) { foreach ($schools as $school) {
                                if ($school['district_id'] == $district['id']) {
                                   echo '<ul>';
                                   echo '<li><span class="folder">' . $school['id'] . ':' . $school['name'] . '</span>';

                                   if ($users) { foreach ($users as $user) {
                                      if ($user['school_id'] == $school['id']) {
                                         echo '<ul>';
                                         echo "<li><span class='file'><a onclick=retrieve_object_data('user','" . $user['id']."');>" . $user['firstname'] . ' ' . $user['lastname'] . '</a></span>';
                                         /*echo '<div class="edit-area" id="edit-area-'.$user['id'].'">Edit Area<div>';*/
                                         echo '</li>';
                                         echo '</ul>';
                                      }
                                   }
                                   echo '</li>';
                                   echo '</ul>';
                                } }
                             } // foreach 
                             } else { // $schools
                                echo "<ul><li id='district_holder_".$district['id']."'><span class='folder'><a onclick=retrieve_schools_for_district('".$district['id']."');>Schools....</a></span></li></ul>";
                             }
                          echo '</li>';
                          echo '</ul>';
                       }
                    }
                 echo '</li>'; 
                 echo '</ul>';
              }
              //echo '<ul>';     
              //echo '<li><span class="folder">Unassigned Districts</span>';
              //   foreach ($districts as $d) {
              //      if ($d['state_id'] == 0) { 
              //         echo '<ul>';
              //         echo '<li><span class="folder">' . $d['id'] . ':' . $d['name'] . '</span>';
              //         echo '</ul>';
              //      }
              //   }
              //echo '</ul>';
            ?>
          </li>
        </ul>
        </td>
      </tr>
    </table>
  </div>
  <div class="edit-area" title="Raw User Data" id="edit-area">Edit Object</div>
  <input type="hidden" name="type"/><input type="hidden" name="id"/>
  <input type="hidden" name="district_id" id="district_id" value=""/>
  <input type="hidden" name="school_id" id="school_id" value=""/>
  <input type="hidden" name="store_code" id="store_code" value="<?php echo $_SESSION['store_code']; ?>"/>
</form>
<script type="text/javascript">
  <!--$.tabs('.tabs a'); //-->
</script>
<script>
 $(document).ready(function() { 

    $("#hierarchy").hide();
    $("#hierarchy").treeview({
       persist: "location", 
       collapsed: true,
       unique: true
    }); 

    $('.ajax_loading_animation').hide();
    $("#hierarchy").show('slow');

});


function buildit(key, value) {
   //alert(id);
   var input = '<div>' + key + ':<input disabled="disabled" type="' + key + '" value="' + value + '"></div>';
   return input;
}

function closeEdit() {
   $("#edit-area").hide('slow');
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
    var u_id = '';
    var input = '';
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
               if (key =='user_id') { u_id = value; }
               input += buildit(key, value);
               //alert(input);
            }

         });
         //input += "<div><a onclick=location='<?php echo $edit_user . '&user_id='; ?>" + u_id + "'>Edit User</a></div>";
         $("#edit-area").html(input);
         /*$.each(result, function(i, item){
            //alert(item);
         }) */
         $('.ajax_loading_animation').hide();

         //clear_all_subtotal_rows();
         
         //data = eval('(' + result + ')');
         //alert(data.firstname);
         //for (i=0; i < subtotal_rows.length; i++) {
         //   addSubtotal(subtotal_rows[i].title, subtotal_rows[i].text);
         //}
         $('#edit-area').dialog({ 
            height: 700, 
            width: 400,
            show: "blind",
            modal: true,
            buttons: {
              "Jump To Edit User": function() {
              location = '<?php echo $edit_user; ?>'+'&user_id='+u_id;
              },
              Cancel: function() {
                 $(this).dialog("close");
              }
            }
         });
         
         //$("#edit-area").show('slow');
      }
      
    );
 }

 function update_object_data(type, id) {

 }

 function buildUserHierarchy(key, value) {
    input = '<ul><li id="user_'+key+'"><span class="file"><a onclick="retrieve_object_data(\'user\','+key+');">'+value+'</a></span></li></ul>';
    return input;
 }

 function retrieve_users_for_school(school) {
    $('#school_id').val(school);

    var input = ''; 
    $.post(
      '<?php echo $retrieve_users_for_school ?>', 
      //url,
      $('#form').serialize(), 
      
      function (result) {
         //alert(result);
         JSON.parse(result, function(key, value) {
            if (typeof value != "object") {
               input += buildUserHierarchy(key, value);
            }
         });
         $('#school_'+school).append(input);
         $('#school_click_'+school).remove();
         $('.ajax_loading_animation').hide();
      }
    );
 }

 function buildSchoolHierarchy(key, value) {
    input = '<ul><li class="closed" id="school_'+key+'"><span class="folder">'+value+' <a id="school_click_'+key+'" onclick="retrieve_users_for_school('+key+');">get users</a></span>';
    input += '</li></ul>';
    return input;
 }

 function retrieve_schools_for_district(district) {
    $('.ajax_loading_animation').show();
    $('#district_id').val(district);

    var input = ''; 
    $.post(
      '<?php echo $retrieve_schools_for_district ?>', 
      //url,
      $('#form').serialize(), 
      
      function (result) {
         //alert(result);
         JSON.parse(result, function(key, value) {
            if (typeof value != "object") {
               input += buildSchoolHierarchy(key, value);
            }
         });
         $('#district_holder_'+district).remove();
         $('#district_click_'+district+'').remove();
         $('#district_'+district).append(input);
         $('.ajax_loading_animation').hide();
      }
    );
 }

 function retrieve_schools_and_users(district) {
    alert(district);
 }
</script>
