//used to decline delete and edit when form is being completed
var isDirty = 0;
//delete function
function deleteTeacher(teacherID) {
   //if form is being completed it does not let you delete
   if (isDirty==1) {
      alert(teacherStrings.deleteForbidden);
      return false;
   }
   //ajax data
   var data = {
      action: 'utt_delete_teacher',
      teacher_id: teacherID
   };
   //confirm deletion
   if (confirm(teacherStrings.deleteRecord)) {
      //ajax call
      jQuery.get('admin-ajax.php' , data, function(data){
         //success
         if (data == 1) {
            jQuery('#'+teacherID).remove();
            jQuery('#messages').html("<div id='message' class='updated'>"+teacherStrings.teacherDeleted+"</div>");
         //fail
         }else{
            jQuery('#messages').html("<div id='message' class='error'>"+teacherStrings.teacherNotDeleted+"</div>");
         }
         
      });
   }
   return false;
}
//edit function
function editTeacher(teacherID, teacherSurName, teacherName) {
   //if form is being completed it does not let you edit
   if (isDirty==1) {
      alert(teacherStrings.editForbidden);
      return false;
   }
   //complete form
   document.getElementById('firstname').value=teacherName;
   document.getElementById('lastname').value=teacherSurName;
   document.getElementById('teacherid').value=teacherID;
   document.getElementById('teacherTitle').innerHTML=teacherStrings.editTeacher;
   document.getElementById('clearTeacherForm').innerHTML=teacherStrings.cancel;
   jQuery('#message').remove();
   isDirty = 1;
   return false;
}

jQuery(function ($) {
    //submit form
    $('#insert-updateTeacher').click(function(){
      //data
      var teacherID = $('#teacherid').val();
      var teacherName = $('#firstname').val();
      var teacherSurName = $('#lastname').val();
      var regexName = /^[α-ωΑ-ΩA-Za-zΆ-Ώά-ώ0-9_\s-.\/]{0,35}$/;
      var regexSurName = /^[α-ωΑ-ΩA-Za-zΆ-Ώά-ώ0-9_\s-.\/]{3,35}$/;
      var success = 0;
      //validation
	  /*
      if (!regexSurName.test(teacherSurName)) {
         alert(teacherStrings.surnameVal);
         return false;
      }
      if (!regexName.test(teacherName)) {
         alert(teacherStrings.nameVal);
         return false;
      }*/
      //ajax data
      var data = {
         action: 'utt_insert_update_teacher',
         teacher_id: teacherID,
         teacher_name: teacherName,
         teacher_surname: teacherSurName
      };
      //ajax call
      $.get('admin-ajax.php' , data, function(data){
         success = data;
         //success
         if (success == 1) {
            //insert
            if (teacherID == 0) {
               jQuery('#messages').html("<div id='message' class='updated'>"+teacherStrings.successAdd+"</div>");
            //edit
            }else{
               jQuery('#messages').html("<div id='message' class='updated'>"+teacherStrings.successEdit+"</div>"); 
            }
            //clear form
            jQuery('#teacherid').val(0);
            jQuery('#firstname').val("");
            jQuery('#lastname').val("");
            jQuery('#teacherTitle').html(teacherStrings.insertTeacher);
            jQuery('#clearTeacherForm').html(teacherStrings.reset);
            isDirty = 0;
         //fail
         }else{
            //insert
            if (teacherID == 0) {
               jQuery('#messages').html("<div id='message' class='error'>"+teacherStrings.failAdd+"</div>");
            //edit
            }else{
               jQuery('#messages').html("<div id='message' class='error'>"+teacherStrings.failEdit+"</div>");
            }
         }
         //ajax data
         data = {
            action: 'utt_view_teachers'
         };
         //ajax call
         jQuery.get('admin-ajax.php' , data, function(data){
            //load new data
            jQuery('#teachersResults').html(data);
         });
      });
      return false;
   })
   //clear form
   $('#clearTeacherForm').click(function(){
      $('#teacherTitle').html(teacherStrings.insertTeacher);
      $('#firstname').val("");
      $('#lastname').val("");
      $('#teacherid').val(0);
      $('#clearTeacherForm').html(teacherStrings.reset);
      $('#message').remove();
      isDirty = 0;
      return false;
   })
   //form is dirty
    $('.dirty').change(function(){
      isDirty = 1;
    })
    
    
    
    
    
    $('#lastname').on('input', function() {
       check_lastname(this);
    });
    $('#lastname').on('change', function() {
       check_lastname(this);
    });
    
});






function check_lastname(docitem)
{
    docitem.value = docitem.value.toLocaleUpperCase('tr-TR');
    //alert( docitem.value );
}

