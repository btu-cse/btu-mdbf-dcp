


//used to decline delete and edit when form is being completed
var isDirty = 0;
//shows groups
function viewGroups(){
    //filters
    var periodID = document.getElementById('periodFilter').value;
    var semester = document.getElementById('semesterFilter').value;
    //ajax data
    var data = {
        action: 'utt_view_groups',
        period_id: periodID,
        semester: semester
    }
    //ajax call
    jQuery.get('admin-ajax.php', data, function(data){
        //return data
        jQuery('#groupsResults').html(data);
    })
    return false;
}
//edit function
function editGroup(groupID,periodID,semester,subjectID,groupName) {
    //if form is being completed it does not let you edit
    //yasağı kaldır 2
    if (isDirty==1) {
        alert(groupStrings.editForbidden);
        return false;
    }
    ReloadSearchableSelect1();
    //fill form with data
    document.getElementById('groupID').value=groupID;
    document.getElementById('period').value=periodID;
    document.getElementById('semester').value=semester;
    loadGroupSubjects(subjectID);
    //document.getElementById("groupsNumber").value=1;
    //document.getElementById("groupsNumber").disabled = true;
    document.getElementById('groupsName').value=groupName;
    document.getElementById('groupTitle').innerHTML=groupStrings.editGroup;
    document.getElementById('clearGroupForm').innerHTML=groupStrings.cancel;
    jQuery('.counterStart').hide();
    jQuery('#message').remove();
    //form is now dirty
    isDirty = 1;
    ReloadSearchableSelect2();
    return false;
}
//load subjects combo-box depending on semester selected
function loadGroupSubjects(selected){
   semester = jQuery('#semester').val();
   //ajax data
   var data = {
      action: 'utt_load_groupsubjects',
      semester: semester,
      selected: selected
   };
   //ajax call
   jQuery.get('admin-ajax.php', data, function(data){
        //load combo-box
      jQuery('#subjects').html(data);
   });
}
//delete group function
function deleteGroup(groupID){
    //if form is being completed it does not let you delete
    //yasağı kaldır
    if (isDirty==1) {
        alert(groupStrings.deleteForbidden)
        return false;
    }
    //ajax data
    var data = {
        action: 'utt_delete_group',
        group_id: groupID
    }
    jQuery('#message').remove();
    //confirm deletion
    if (confirm(groupStrings.deleteGroup)) {
        //ajax call
        jQuery.get('admin-ajax.php' , data, function(data){
            //delete succeeded
            if (data == 1) {
                //remove record and show message
                jQuery('#'+groupID).remove();
                jQuery('#messages').html("<div id='message' class='updated'>"+groupStrings.groupDeleted+"</div>");
            //delete failed
            }else{
                //show message
                jQuery('#messages').html("<div id='message' class='error'>"+groupStrings.groupNotDeleted+"</div>");
            }
        });
    }
    return false;
}
//load subjects combo-box depending on selected semester. Parameter selected is used for edit purposes
function loadSubjects(selected){
    
    ReloadSearchableSelect1();
   semester = jQuery('#semester').val();
   //ajax data
   var data = {
      action: 'utt_load_groupsubjects',
      semester: semester,
      selected: selected
   };
   //ajax call
   jQuery.get('admin-ajax.php', data, function(data){
    //load combo-box
      jQuery('#subjects').html(data);
   });
   
    ReloadSearchableSelect2();
}


function loadGetGroupNumber(){
	
	period_id = jQuery('#period').val();
	subject_id = jQuery('#subject').val();
	
	
   //ajax data
   var data = {
      action: 'utt_get_group_number',
	  period_id: period_id,
      subject_id: subject_id
   };
   //ajax call
   jQuery.get('admin-ajax.php', data, function(data){
    //load text input
      jQuery('#groupsName').val(groupStrings.group+" "+(parseInt(data)+1));
   });
}

jQuery(function ($) {
    //submit form
    $('#insert-updateGroup').click(function(){
        //data
        var groupID = $('#groupID').val();
        var period = $('#period').val();
        var semester = $('#semester').val();
        var subject = $('#subject').val();
        var groupsNumber = $('#groupsNumber').val();
        var groupsName = $('#groupsName').val();
        var counterStart = $('#counterStart').val();
        var regexGroupsName = /^[()α-ωΑ-ΩA-Za-zΆ-Ώά-ώ0-9\s-_.ğüşıöçĞÜŞİÖÇ]{0,26}$/;// /^[()α-ωΑ-ΩA-Za-zΆ-Ώά-ώ0-9\s-_.]{0,26}$/;
        var success = 0;
        //validation
        if (period == 0) {
            alert(groupStrings.periodVal)
            return false;
        }
        if (semester == 0) {
            alert(groupStrings.semesterVal);
            return false;
        }
        if (subject == 0) {
            alert(groupStrings.subjectVal);
            return false;
        }
        if (!regexGroupsName.test(groupsName)) {
            alert(groupStrings.nameVal);
            return false;
        }
        //ajax data
        var data = {
            action: 'utt_insert_update_group',
            group_id: groupID,
            period_id: period,
            subject_id: subject,
            group_name: groupsName,
            counter_start: counterStart,
            groups_number: groupsNumber
        };
        //ajax call
        $.get('admin-ajax.php' , data, function(data){
			console.log(data);
            success = data;
            //success
            if (success == 1) {
                //insert
               if (groupID == 0) {
                  $('#messages').html("<div id='message' class='updated'>"+groupStrings.successAdd+"</div>");
                //edit
               }else{
                  $('#messages').html("<div id='message' class='updated'>"+groupStrings.successEdit+"</div>"); 
               }
               //clear form
                $('#groupTitle').html(groupStrings.insertGroup);
                $('#clearGroupForm').html(groupStrings.reset);
                $('#groupsNumber').removeAttr("disabled");
                //$('#groupsName').val(groupStrings.group);
                $('.counterStart').show();
                $('#counterStart').val(1);
                $('#groupID').val(0);
                $('#subject').val(0);
                $('#groupsNumber').val(1);
                isDirty = 0;
                ReloadSearchableSelect();
            //fail
            }else{
                //insert
               if (groupID == 0) {
                  $('#messages').html("<div id='message' class='error'>"+groupStrings.failAdd+"</div>");
                //edit
               }else{
                  $('#messages').html("<div id='message' class='error'>"+groupStrings.failEdit+"</div>");
               }
            }
            //ajax data
            data = {
               action: 'utt_view_groups',
               period_id: $('#periodFilter').val(),
               semester: $('#semesterFilter').val()
            };
            //ajax call, reload table with data from database
            $.get('admin-ajax.php' , data, function(data){
               $('#groupsResults').html(data);
            });
        });
        return false;
    })
    //clear form
    $('#clearGroupForm').click(function(){
        $('#groupTitle').html(groupStrings.insertGroup);
        $('#groupID').val(0);
        $('#period').val(0);
        $('#semester').val(0);
        $('#subject').val(0);
        //document.getElementById("groupsNumber").disabled = false;
        $('#groupsNumber').val(1);
        $('#groupsName').val(groupStrings.group);
        $('.counterStart').show();
        $('#counterStart').val(1);
        $('#clearGroupForm').html(groupStrings.reset);
        $('#message').remove();
        isDirty = 0;
        ReloadSearchableSelect();
        return false;
    })
    //form is dirty
    $('.dirty').change(function(){
        isDirty = 1;
		loadGetGroupNumber();
		
        ReloadSearchableSelect();
    })
    
	viewGroups();
	loadGetGroupNumber();
});