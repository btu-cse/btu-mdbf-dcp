

//used to decline delete and edit when form is being completed
var isDirty = 0;
//delete function
function deleteSubject(subjectID){
   //if form is being completed it does not let you delete
   if (isDirty==1) {
      alert(subjectStrings.deleteForbidden)
      return false;
   }
   //ajax data
   var data = {
      action: 'utt_delete_subject',
      subject_id: subjectID
   }
   //delete confirmation
   if (confirm(subjectStrings.deleteQuestion)) {
      //ajax call
      jQuery.get('admin-ajax.php' , data, function(data){
         //success
         if (data == 1) {
            //remove deleted
            jQuery('#'+subjectID).remove();
            jQuery('#messages').html("<div id='message' class='updated'>"+subjectStrings.subjectDeleted+"</div>");
         //failed
         }else{
            jQuery('#messages').html("<div id='message' class='error'>"+subjectStrings.subjectNotDeleted+"</div>");
         }
         
      });
   }
   return false;
}
//edit function
function editSubject(subjectID,title,type,semester,color,quota,class_level,is_common) {
   //if form is being completed it does not let you delete
   if (isDirty==1) {
      alert(subjectStrings.editForbidden)
      return false;
   }
   
   ReloadSearchableSelect1();
   //fill form
   document.getElementById('subjectid').value=subjectID;
	
   document.getElementById('subjectname').value=getOnlySubjectName(title);
   document.getElementById('subjectcode').value=getSubjectCode(title);
   //document.getElementById('subjectnumber').value=getSubjectNumber(title);
	//document.getElementById('subjectnumber').value=getSubjectNumber(title);

   document.getElementById('subjectquota').value=quota;
	
   document.getElementById('subjecttype').value=type;
   document.getElementById('semester').value=semester;
   document.getElementById('color').value=color;
   document.getElementById('subjectclasslevel').value=class_level;
    if (is_common==1)
    {
        document.getElementById('is_common').checked = true;
    }
    else
    {
        document.getElementById('is_common').checked = false;
    }
   jQuery('#color').css("background-color","#"+color);
   document.getElementById('subjectTitle').innerHTML=subjectStrings.editSubject;
   document.getElementById('clearSubjectForm').innerHTML=subjectStrings.cancel;
   jQuery('#message').remove();
   isDirty = 1;
   ReloadSearchableSelect2();
   return false;
}

function getSubjectCode(subjectName)
{
	var splitName = subjectName.split(" ");
	return splitName[0];
}
function getSubjectNumber(subjectName)
{
	var rt = "-";
	var splitName = subjectName.split(" ");
	var lastString = splitName[splitName.length-1];
	var allowedRomanNumerals = ["I","II","III","IV"];
	if(allowedRomanNumerals.includes(lastString))
	{
		rt = lastString;
	}
	return rt;
}
function getOnlySubjectName(subjectName,subjectCode,subjectNumber)
{
	var subjectCode = getSubjectCode(subjectName);
	//var subjectNumber = getSubjectNumber(subjectName);
	subjectName = subjectName.replace(subjectCode,"");
	/*if(subjectNumber!="-")
	{
		subjectName = subjectName.replace(subjectCode,"");
	}*/
	return subjectName.trim();
}

//show all or filtered subjects
function viewSubjects(){
    var semester = document.getElementById('semesterFilter').value;
    //ajax data
    var data = {
        action: 'utt_view_subjects',
        semester: semester
    }
    //ajax call
    jQuery.get('admin-ajax.php', data, function(data){
        jQuery('#subjectsResults').html(data);
    })
    return false;
}

jQuery(function ($) {
    //submit form
    $('#insert-updateSubject').click(function(){
         //data
         var subjectID = $('#subjectid').val();
         var subjectCode = $('#subjectcode').val();
         var subjectName = $('#subjectname').val();
		subjectName = subjectCode+" "+subjectName;
         //var subjectName = $('#subjectname').val();
         //var subjectNumber = $('#subjectnumber').val();
		/*if(subjectNumber!="-")
		{
			subjectName = subjectCode+" "+subjectName+" "+subjectNumber;
		}
		else
		{
			subjectName = subjectCode+" "+subjectName;
		}*/
		var subjectQuota = $('#subjectquota').val();
         var subjectType = $('#subjecttype').val();
         var semester = $('#semester').val();
         var color = $('#color').val();
         var class_level = $('#subjectclasslevel').val();
         var is_common = 0;
         if($('#is_common').prop("checked") == true)
         {
             is_common = 1;
         }
         var regexSubjectName = /^[α-ωΑ-ΩA-Za-zΆ-Ώά-ώ0-9\s-_\/.&]{3,64}$/;
         var regexColor = /^[0-9A-F]{6}$/;
         var success = 0;
         //validation
		 /*
         if (!regexSubjectName.test(subjectName)) {
            alert(subjectStrings.nameVal);
            return false;
         }
		 */
         if (subjectType == 0) {
            alert(subjectStrings.typeVal);
            return false;
         }
         if (semester == 0) {
            alert(subjectStrings.semesterVal);
            return false;
         }
         if (!regexColor.test(color)) {
            alert(subjectStrings.colorVal);
            return false;
         }
         //ajax data
         var data = {
            action: 'utt_insert_update_subject',
            subject_id: subjectID,
            subject_name: subjectName,
			subject_quota: subjectQuota,
            subject_type: subjectType,
            semester: semester,
            color: color,
            class_level: class_level,
            is_common: is_common
         };
         //ajax call
         $.get('admin-ajax.php' , data, function(data){
            success = data;
            //success
            if (success == 1)
            {
               //insert
               if (subjectID == 0)
               {
                  $('#messages').html("<div id='message' class='updated'>"+subjectStrings.successAdd+"</div>");
               //edit
               }
               else
               {
                  $('#messages').html("<div id='message' class='updated'>"+subjectStrings.successEdit+"</div>"); 
               }
               //clear form
               $('#subjectid').val(0);
               $('#subjectquota').val(0);
               $('#subjecttype').val(0);
               $('#subjectTitle').html(subjectStrings.insertSubject);
               $('#clearSubjectForm').html(subjectStrings.reset);
               
               $('#subjectclasslevel').val("1");
               $('#is_common').prop("checked",false);
               
               
                $('#semester').val(0);
                $('#subjectcode').val("");
                $('#subjectname').val("");
                console.log("temizlendi");
               
               isDirty = 0;
               ReloadSearchableSelect();
            //fail
            }
            else if(success == -1)
            {
                console.log("Err#-1");
                $('#messages').html("<div id='message' class='error'>"+subjectStrings.failAuth+"</div>");
            }
            else{
                console.log("Err#0");
               //insert
               if (subjectID == 0) {
                  $('#messages').html("<div id='message' class='error'>"+subjectStrings.failAdd+" ("+success+")</div>");
               //edit
               }else{
                  $('#messages').html("<div id='message' class='error'>"+subjectStrings.failEdit+" ("+success+")</div>");
               }
            }
            //ajax data
            data = {
               action: 'utt_view_subjects',
               semester: $('#semesterFilter').val()
            };
            //ajax call
            $.get('admin-ajax.php' , data, function(data){
               //show registered subjects
               $('#subjectsResults').html(data);
            });
         });
         return false;
    })
    //clear form
    $('#clearSubjectForm').click(function(){
        $('#subjectTitle').html(subjectStrings.insertSubject);
        $('#subjectid').val(0);
        $('#subjectcode').val("");
        $('#subjectname').val("");
        //$('#subjectnumber').val("-");
        $('#subjectquota').val(0);
        $('#subjecttype').val(0);
        $('#semester').val(0);
        $('#color').val("FFFFFF");
        $('#color').css("background-color","white");
        $('#subjectclasslevel').val("1");
        $('#is_common').prop("checked",false);
        $('#clearSubjectForm').html(subjectStrings.reset);
        $('#message').remove();
        isDirty = 0;
        ReloadSearchableSelect();
        return false;
    })
    //form is dirty
    $('.dirty').change(function(){
        isDirty = 1;
    })
    
});