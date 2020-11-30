//used to decline delete and edit when form is being completed
var isDirty = 0;
//delete holiday function
function deleteHoliday(holidayDate) {
    //if form is being completed it does not let you delete
    if (isDirty==1) {
        alert(holidayStrings.deleteForbidden)
        return false;
    }
    //ajax data
    var data = {
        action: 'utt_delete_holiday',
        holiday_date: holidayDate
    };
    //confirm deletion
    if (confirm(holidayStrings.deleteHoliday)) {
        //ajax call
        jQuery.get('admin-ajax.php' , data, function(data){
            //remove deleted
            jQuery('#'+holidayDate).remove();
            //show message
            jQuery('#messages').html("<div id='message' class='updated'>"+holidayStrings.holidayDeleted+"</div>");
        });
    }
    return false;
}
//edit function
function editHoliday(holidayDate, holidayName) {
    //if form is being completed it does not let you edit
    if (isDirty==1) {
        alert(holidayStrings.editForbidden);
        return false;
    }
    //fill form with data
    document.getElementById('holidayName').value=holidayName;
    document.getElementById('holidayDate').value=holidayDate;
    document.getElementById('holidayDate').disabled=true;
    document.getElementById('isEdit').value=1;
    document.getElementById('holidayTitle').innerHTML=holidayStrings.editHoliday;
    document.getElementById('clearHolidayForm').innerHTML=holidayStrings.cancel;
    jQuery('#message').remove();
    isDirty = 1;
    return false;
}
//validate date
function isDate(date){
    if (date=="") {
        return false;
    }
    var regexDate = /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/;
    if (date.match(regexDate) == null) {
        return false;
    }
    date = date.split("/");
    d = date[0];
    m = date[1];
    y = date[2];
    if (m<1 || m>12) {
        return false;
    }
    if (y<1000 || y>3000) {
        return false;
    }
    if (d<1 || d>31) {
        return false;
    }
    if ((m==4 || m==6 || m==9 || m==11) && d==31) {
        return false;
    }
    if (m==2) {
        var isleap = (y % 4 == 0 && (y % 100 != 0 || y % 400 == 0));
        if (d>29 || (d==29 && !isleap)) {
            return false;
        }
    }
    return y+"-"+m+"-"+d;
}
//show holidays depending on selected year
function viewHolidays() {
    var selectedYear = document.getElementById('yearFilter').value;
    //ajax data
    var data = {
        action: 'utt_view_holidays',
        yearFilter: selectedYear
    }
    //ajax call
    jQuery.get('admin-ajax.php', data, function(data){
        //reload table with data
        jQuery('#holidaysResults').html(data);
    })
    return false;
}

jQuery(function ($) {
    //use datepicker to holidayDate element
    $( "#holidayDate" ).datepicker({dateFormat: 'dd/mm/yy'});
   //submit form
    $('#insert-updateHoliday').click(function(){
        //data
        var holidayDate = $('#holidayDate').val();
        var holidayName = $('#holidayName').val();
        var isEdit = $('#isEdit').val();
        var regexHolidayName = /^[0-9α-ωΑ-ΩA-Za-zΆ-Ώά-ώ\s-_.,\/]{3,35}$/;
        var success = 0;
        //form validation
        if (!regexHolidayName.test(holidayName)) {
            alert(holidayStrings.nameVal);
            return false;
        }
        holidayDate = isDate(holidayDate);
        if (!holidayDate){
            alert (holidayStrings.dateVal);
            return false;
        }
        //ajax data
        var data = {
            action: 'utt_insert_update_holiday',
            is_edit: isEdit,
            holiday_date: holidayDate,
            holiday_name: holidayName
        };
        //ajax call
        $.get('admin-ajax.php' , data, function(data){
            success = data;
            //success
            if (success == 1) {
                //insert
               if (isEdit == 0) {
                  $('#messages').html("<div id='message' class='updated'>"+holidayStrings.successAdd+"</div>");
                //edit
               }else{
                  $('#messages').html("<div id='message' class='updated'>"+holidayStrings.successEdit+"</div>"); 
               }
               //clear form
                $('#isEdit').val(0);
                $('#holidayDate').val("");
                $('#holidayDate').removeAttr("disabled");
                $('#holidayName').val("");
                $('#holidayTitle').html(holidayStrings.insertHoliday);
                $('#clearHolidayForm').html(holidayStrings.reset);
                isDirty = 0;
            //fail insert
            }else{
                  $('#messages').html("<div id='message' class='error'>"+holidayStrings.failAdd+"</div>");
            }
            //ajax data
            data = {
               action: 'utt_view_holidays',
               yearFilter: $('#yearFilter').val()
            };
            //ajax call
            $.get('admin-ajax.php' , data, function(data){
                //reload holidays table
               $('#holidaysResults').html(data);
            });
        });
        return false;
    })
   //clear form
    $('#clearHolidayForm').click(function(){
        $('#holidayTitle').html(holidayStrings.insertHoliday);
        $('#holidayName').val("");
        $('#holidayDate').val("");
        $('#holidayDate').removeAttr("disabled");
        $('#isEdit').val(0);
        $('#clearHolidayForm').html(holidayStrings.reset);
        $('#message').remove();
        isDirty = 0;
        return false;
    })
    //form is dirty
    $('.dirty').change(function(){
        isDirty = 1;
    })
    
});