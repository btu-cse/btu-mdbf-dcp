
function verifySharedgroup(sharedgroup_id, subject_id, val)
{
   if (val == true)
   {
      val = 1;
   }
   else
   {
      val = 0;
   }
   //console.log(sharedgroup_id + " | " + subject_id + " | " + val);
   var data2send = {
      action: 'utt_setok_sharedgroup',
      sharedgroup_id: sharedgroup_id,
      subject_id: subject_id,
      val: val
   }

   //ajax call
   jQuery.get('admin-ajax.php' , data2send, function(data)
   {
      jQuery('#messages').html("<div id='message' class='updated'>"+data+"</div>");
   });
}

function checkSelectedSubjects()
{
   if(jQuery('#s1').val()!=0)
   {
      jQuery('#s2').prop("disabled", false);
   }
   else
   {
      jQuery('#s2').val('0').prop("disabled", true);
      jQuery('#s3').val('0').prop("disabled", true);
      jQuery('#s4').val('0').prop("disabled", true);
      jQuery('#s5').val('0').prop("disabled", true);
   }

   if(jQuery('#s2').val()!=0)
   {
      jQuery('#s3').prop("disabled", false);
   }
   else
   {
      jQuery('#s3').val('0').prop("disabled", true);
      jQuery('#s4').val('0').prop("disabled", true);
      jQuery('#s5').val('0').prop("disabled", true);
   }

   if(jQuery('#s3').val()!=0)
   {
      jQuery('#s4').prop("disabled", false);
   }
   else
   {
      jQuery('#s4').val('0').prop("disabled", true);
      jQuery('#s5').val('0').prop("disabled", true);
   }

   if(jQuery('#s4').val()!=0)
   {
      jQuery('#s5').prop("disabled", false);
   }
   else
   {
      jQuery('#s5').val('0').prop("disabled", true);
   }
}

//used to decline delete and edit when form is being completed
var isDirty = 0;

//delete sharedgroup function
function deleteSharedgroup(sharedgroupID){
   //if form is being completed it does not let you delete
   if (isDirty==1) {
      alert(sharedgroupStrings.deleteForbidden);
      return false;
   }
   //ajax data
   var data = {
      action: 'utt_delete_sharedgroup',
      sharedgroup_id: sharedgroupID
   }
   //confirm deletion
   if (confirm(sharedgroupStrings.deleteSharedgroup)) {
      //ajax call
      jQuery.get('admin-ajax.php' , data, function(data){
         //success
         if (data == 1) {
            jQuery('#'+sharedgroupID).remove();
            jQuery('#messages').html("<div id='message' class='updated'>"+sharedgroupStrings.sharedgroupDeleted+"</div>");
         //fail
         }else{
            jQuery('#messages').html("<div id='message' class='error'>"+sharedgroupStrings.sharedgroupNotDeleted+"</div>");
         }
         
      });
   }
   return false;
}
//edit function
function editSharedgroup(sharedgroupID,sharedgroup) {
   //if form is being completed it does not let you edit
   if (isDirty==1) {
      alert(sharedgroupStrings.editForbidden)
      return false;
   }
   //fill form
   document.getElementById('sharedgroup').value=sharedgroup;
   document.getElementById('sharedgroupid').value=sharedgroupID;
   document.getElementById('sharedgroupTitle').innerHTML=sharedgroupStrings.editSharedgroup;
   document.getElementById('clearSharedgroupForm').innerHTML=sharedgroupStrings.cancel;
   jQuery('#message').remove();
   isDirty = 1;
   return false;
}

jQuery(function ($) {
    //submit form
    $('#insert-updateSharedgroup').click(function()
    {
      //data
        var sharedgroupID = $('#sharedgroupid').val();
        var groupID = $('#groupID').val();
        var subjectID = $('#subjectID').val();
        var success = 0;
        
        //ajax data
        var data = {
            action: 'utt_insert_update_sharedgroup',
            sharedgroup_id: sharedgroupID,
            groupID: groupID,
            subjectID: subjectID

        };
        //ajax call
        $.get('admin-ajax.php' , data, function(data)
        {
           console.log(data);
            success = data;
            //success
            if (success == 1)
            {
               //insert
               if (sharedgroupID == 0)
               {
                  $('#messages').html("<div id='message' class='updated'>"+sharedgroupStrings.successAdd+"</div>");
               //edit
               }
               else
               {
                  $('#messages').html("<div id='message' class='updated'>"+sharedgroupStrings.successEdit+"</div>"); 
               }
               //clear form
               $('#sharedgroupid').val(0);
               $('#sharedgroupTitle').html(sharedgroupStrings.insertSharedgroup);
               $('#clearSharedgroupForm').html(sharedgroupStrings.reset);
               isDirty = 0;
            //fail
            }
            else if (success == 0)
            {
               //insert
               if (sharedgroupID == 0)
               {
                  $('#messages').html("<div id='message' class='error'>"+sharedgroupStrings.failAdd+"</div>");
               //delete
               }
               else
               {
                  $('#messages').html("<div id='message' class='error'>"+sharedgroupStrings.failEdit+"</div>");
               }
            }
            else
            {
               $('#messages').html("<div id='message' class='error'>"+success+"</div>");
            }
            //ajax data
            data = {
               action: 'utt_view_sharedgroups'
            };
            //ajax call
            $.get('admin-ajax.php' , data, function(data){
               //load new data
               $('#sharedgroupsResults').html(data);
            });
        });
        return false;
    })
    //clear form
    $('#clearSharedgroupForm').click(function(){
        $('#sharedgroupTitle').html(sharedgroupStrings.insertSharedgroup);
        $('#clearSharedgroupForm').html(sharedgroupStrings.reset);
        $('#message').remove();


        $('#sharedgroupid').val(0);
        
        $('#groupID').val('0');
        $('#subjectID').val('0');
        isDirty = 0;
        return false;
    })
    //form is dirty
    $('.dirty').change(function(){
      isDirty = 1;
    })

    
});