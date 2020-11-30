


function verifyJoint(joint_id, subject_id, val)
{
   if (val == true)
   {
      val = 1;
   }
   else
   {
      val = 0;
   }
   //console.log(joint_id + " | " + subject_id + " | " + val);
   var data2send = {
      action: 'utt_setok_joint',
      joint_id: joint_id,
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
      jQuery('#s6').val('0').prop("disabled", true);
      jQuery('#s7').val('0').prop("disabled", true);
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
      jQuery('#s6').val('0').prop("disabled", true);
      jQuery('#s7').val('0').prop("disabled", true);
   }

   if(jQuery('#s3').val()!=0)
   {
      jQuery('#s4').prop("disabled", false);
   }
   else
   {
      jQuery('#s4').val('0').prop("disabled", true);
      jQuery('#s5').val('0').prop("disabled", true);
      jQuery('#s6').val('0').prop("disabled", true);
      jQuery('#s7').val('0').prop("disabled", true);
   }

   if(jQuery('#s4').val()!=0)
   {
      jQuery('#s5').prop("disabled", false);
   }
   else
   {
      jQuery('#s5').val('0').prop("disabled", true);
      jQuery('#s6').val('0').prop("disabled", true);
      jQuery('#s7').val('0').prop("disabled", true);
   }
   
   
   if(jQuery('#s5').val()!=0)
   {
      jQuery('#s6').prop("disabled", false);
   }
   else
   {
      jQuery('#s6').val('0').prop("disabled", true);
      jQuery('#s7').val('0').prop("disabled", true);
   }
   
   
   
   if(jQuery('#s6').val()!=0)
   {
      jQuery('#s7').prop("disabled", false);
   }
   else
   {
      jQuery('#s7').val('0').prop("disabled", true);
   }
   
   ReloadSearchableSelect();
}

//used to decline delete and edit when form is being completed
var isDirty = 0;

//delete joint function
function deleteJoint(jointID){
   //if form is being completed it does not let you delete
   if (isDirty==1) {
      alert(jointStrings.deleteForbidden);
      return false;
   }
   //ajax data
   var data = {
      action: 'utt_delete_joint',
      joint_id: jointID
   }
   //confirm deletion
   if (confirm(jointStrings.deleteJoint)) {
      //ajax call
      jQuery.get('admin-ajax.php' , data, function(data){
         //success
         if (data == 1) {
            jQuery('#'+jointID).remove();
            jQuery('#messages').html("<div id='message' class='updated'>"+jointStrings.jointDeleted+"</div>");
         //fail
         }else{
            jQuery('#messages').html("<div id='message' class='error'>"+jointStrings.jointNotDeleted+"</div>");
         }
         
      });
   }
   return false;
}
//edit function
function editJoint(jointID,joint) {
   //if form is being completed it does not let you edit
   if (isDirty==1) {
      alert(jointStrings.editForbidden)
      return false;
   }
   //fill form
   document.getElementById('joint').value=joint;
   document.getElementById('jointid').value=jointID;
   document.getElementById('jointTitle').innerHTML=jointStrings.editJoint;
   document.getElementById('clearJointForm').innerHTML=jointStrings.cancel;
   jQuery('#message').remove();
   isDirty = 1;
   return false;
}

jQuery(function ($) {
    
    
    $(document).ready(function() {
        InitSearchableSelect(); 
    });
    
    
    //submit form
    $('#insert-updateJoint').click(function()
    {
      //data
        var jointID = $('#jointid').val();
        var s1 = $('#s1').val();
        var s2 = $('#s2').val();
        var s3 = $('#s3').val();
        var s4 = $('#s4').val();
        var s5 = $('#s5').val();
        var s6 = $('#s6').val();
        var s7 = $('#s7').val();
        var success = 0;
        
        //ajax data
        var data = {
            action: 'utt_insert_update_joint',
            joint_id: jointID,
            s1: s1,
            s2: s2,
            s3: s3,
            s4: s4,
            s5: s5,
            s6: s6,
            s7: s7

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
               if (jointID == 0)
               {
                  $('#messages').html("<div id='message' class='updated'>"+jointStrings.successAdd+"</div>");
               //edit
               }
               else
               {
                  $('#messages').html("<div id='message' class='updated'>"+jointStrings.successEdit+"</div>"); 
               }
               //clear form
               $('#jointid').val(0);
               $('#jointTitle').html(jointStrings.insertJoint);
               $('#clearJointForm').html(jointStrings.reset);
               isDirty = 0;
            //fail
            }
            else if (success == 0)
            {
               //insert
               if (jointID == 0)
               {
                  $('#messages').html("<div id='message' class='error'>"+jointStrings.failAdd+"</div>");
               //delete
               }
               else
               {
                  $('#messages').html("<div id='message' class='error'>"+jointStrings.failEdit+"</div>");
               }
            }
            else
            {
               $('#messages').html("<div id='message' class='error'>"+success+"</div>");
            }
            //ajax data
            data = {
               action: 'utt_view_joints'
            };
            //ajax call
            $.get('admin-ajax.php' , data, function(data){
               //load new data
               $('#jointsResults').html(data);
            });
        });
        return false;
    })
    //clear form
    $('#clearJointForm').click(function(){
        $('#jointTitle').html(jointStrings.insertJoint);
        $('#clearJointForm').html(jointStrings.reset);
        $('#message').remove();


        $('#jointid').val(0);
        
        $('#s1').val('0');
        $('#s2').val('0');
        $('#s3').val('0');
        $('#s4').val('0');
        $('#s5').val('0');
        $('#s6').val('0');
        $('#s7').val('0');
        
        $('#s1').change();
        $('#s2').change();
        $('#s3').change();
        $('#s4').change();
        $('#s5').change();
        $('#s6').change();
        $('#s7').change();
        
        
        $('#s2').val('0').prop("disabled", true);
        $('#s3').val('0').prop("disabled", true);
        $('#s4').val('0').prop("disabled", true);
        $('#s5').val('0').prop("disabled", true);
        $('#s6').val('0').prop("disabled", true);
        $('#s7').val('0').prop("disabled", true);
        
        
        isDirty = 0;
        return false;
    })
    //form is dirty
    $('.dirty').change(function(){
      isDirty = 1;
    })

    
});