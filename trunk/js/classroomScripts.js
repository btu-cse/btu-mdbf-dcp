//used to decline delete and edit when form is being completed
var isDirty = 0;
//deletes classroom record
function deleteClassroom(classroomID){
    //if form is being completed it does not let you delete
    if (isDirty==1) {
        alert(classroomStrings.deleteForbidden)
        return false;
    }
    //data for ajax call
    var data = {
        action: 'utt_delete_classroom',
        classroom_id: classroomID
    }
    //asks user's permision to delete
    if (confirm(classroomStrings.deleteClassroom)) {
        //ajax call
        jQuery.get('admin-ajax.php' , data, function(data){
            //if deleted remove record from table and show message
            if (data == 1) {
                jQuery('#'+classroomID).remove();
                jQuery('#messages').html("<div id='message' class='updated'>"+classroomStrings.classroomDeleted+"</div>");
            //not deleted, show message
            }else{
                jQuery('#messages').html("<div id='message' class='error'>"+classroomStrings.classroomNotDeleted+"</div>");
            }
        });
    }
    return false;
}
//completes the form for edit
function editClassroom(classroomID,classroomName,classroomType) {
    //if form is being completed it does not let you edit
    if (isDirty==1) {
        alert(classroomStrings.editForbidden)
        return false;
    }
    document.getElementById('classroomName').value=classroomName;
    document.getElementById('classroomType').value=classroomType;
    document.getElementById('classroomID').value=classroomID;
    document.getElementById('classroomTitle').innerHTML=classroomStrings.editClassroom;
    document.getElementById('clearClassroomForm').innerHTML=classroomStrings.cancel;
    jQuery('#message').remove();
    isDirty = 1;
    return false;
}

jQuery(function ($) {
    //form submit
    $('#insert-updateClassroom').click(function(){
        //data
        var classroomID = $('#classroomID').val();
        var classroomName = $('#classroomName').val();
        var classroomType = $('#classroomType').val();
        var regexClassroomName = /^[α-ωΑ-ΩA-Za-zΆ-Ώά-ώ0-9\s-_.\/&]{2,35}$/;
        var success = 0;
        //validation
        if (!regexClassroomName.test(classroomName)) {
            alert(classroomStrings.nameVal);
            return false;
        }
        if (classroomType == 0) {
            alert(classroomStrings.typeVal);
            return false;
        }
        //ajax data
        var data = {
            action: 'utt_insert_update_classroom',
            classroom_id: classroomID,
            classroom_name: classroomName,
            classroom_type: classroomType
        };
        //ajax call
        $.get('admin-ajax.php' , data, function(data){
            success = data;
            //success
            if (success == 1) {
                //insert
               if (classroomID == 0) {
                  $('#messages').html("<div id='message' class='updated'>"+classroomStrings.successAdd+"</div>");
                //edit
               }else{
                  $('#messages').html("<div id='message' class='updated'>"+classroomStrings.successEdit+"</div>"); 
               }
               //clear form
                $('#classroomID').val(0);
                $('#classroomName').val("");
                $('#classroomType').val(0);
                $('#classroomTitle').html(classroomStrings.insertClassroom);
                $('#clearClassroomForm').html(classroomStrings.reset);
                isDirty = 0;
            //fail
            }else{
                //insert
               if (classroomID == 0) {
                  $('#messages').html("<div id='message' class='error'>"+classroomStrings.failAdd+"</div>");
                //edit
               }else{
                  $('#messages').html("<div id='message' class='error'>"+classroomStrings.failEdit+"</div>");
               }
            }
            //load new data ajax
            data = {
               action: 'utt_view_classrooms',
            };
            $.get('admin-ajax.php' , data, function(data){
               $('#classroomsResults').html(data);
            });
        });
        return false;
    })
    //clear button
    $('#clearClassroomForm').click(function(){
        $('#classroomTitle').html(classroomStrings.insertClassroom);
        $('#classroomID').val(0);
        $('#classroomName').val("");
        $('#classroomType').val(0);
        $('#clearClassroomForm').html(classroomStrings.reset);
        $('#message').remove();
        isDirty = 0;
        return false;
    })
    //form is dirty
    $('.dirty').change(function(){
      isDirty = 1;
    })
    
});