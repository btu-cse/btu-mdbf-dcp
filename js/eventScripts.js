////used to decline delete and edit when form is being completed
var isDirty = 0;
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
   var d = date[0];
   var m = date[1];
   var y = date[2];
   
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
//validate time
function isTime(time) {
   if (time == 0) {
      return false;
   }
   var regexTime = /^(\d{1,2}):(\d{2})$/;
   if (time.match(regexTime) == null) {
      return false;
   }
   time = time.split(":");
   var h = time[0];
   var m = time[1];
   if (h>23 || h<0) {
      return false;
   }
   return h+":"+m+":00";
}
//show events
function viewEvents(){
   //filter
    var selectedYear = document.getElementById('yearFilter').value;
    //ajax data
    var data = {
        action: 'utt_view_events',
        selectedYear: selectedYear
    }
    //ajax call
    jQuery.get('admin-ajax.php', data, function(data){
      //show data
      jQuery('#eventsResults').html(data);
    })
    return false;
}
//delete event function
function deleteEvent(eventID){
   //if form is being completed it does not let you delete
    if (isDirty==1) {
        alert(eventStrings.deleteForbidden)
        return false;
    }
    //ajax data
    var data = {
        action: 'utt_delete_event',
        eventID: eventID
    }
    //confirm delete
    if (confirm(eventStrings.deleteEvent)) {
         //ajax call
         jQuery.get('admin-ajax.php' , data, function(data){
            if (data == 1) {
               //deleted
                jQuery('#'+eventID).remove();
                jQuery('#messages').html("<div id='message' class='updated'>"+eventStrings.eventDeleted+"</div>");
            }else{
               //not deleted
                jQuery('#messages').html("<div id='message' class='error'>"+eventStrings.eventNotDeleted+"</div>");
            }
        });
    }
    return false;
}
//edit event
function editEvent(eventID,eventType,eventTitle,eventDescr,classroomID,eventStart,eventEnd) {
   //if form is being completed it does not let you edit
    if (isDirty==1) {
        alert(eventStrings.editForbidden);
        return false;
    }
    //complete form
    document.getElementById('eventID').value=eventID;
    document.getElementById('eventType').value=eventType;
    document.getElementById('title').value=eventTitle;
    document.getElementById('eventDescr').value=eventDescr;
    document.getElementById("classroom").value=classroomID;
    var datetime = eventStart.split(" ");
    var date = datetime[0].split("-");
    date = date[2]+"/"+date[1]+"/"+date[0];
    var start = datetime[1].split(":");
    start = start[0]+":"+start[1];
    datetime = eventEnd.split(" ");
    var end = datetime[1].split(":");
    end = end[0]+":"+end[1];
    document.getElementById("date").value = date;
    document.getElementById('time').value=start;
    document.getElementById('endTime').value=end;
    document.getElementById('eventTitle').innerHTML=eventStrings.editEvent;
    document.getElementById('clearEventForm').innerHTML=eventStrings.cancel;
    jQuery('#message').remove();
    //form is now dirty
    isDirty = 1;
    return false;
}

jQuery(function ($) {
    //submit button
    $('#insert-updateEvent').click(function(){
      //data
        var eventID = $('#eventID').val();
        var eventType = $('#eventType').val();
        var eventTitle = $('#title').val();
        var eventDescr = $('#eventDescr').val();
        var classroom = $('#classroom').val();
        var date = $('#date').val();
        var time = $('#time').val();
        var endTime = $('#endTime').val();
        var success = 0;
        //validation
        if (eventType == 0) {
           alert(eventStrings.typeVal);
           return false;
        }
        if (eventTitle=="") {
            alert(eventStrings.titleVal);
            return false;
        }
        if (classroom == 0) {
           alert (eventStrings.classroomVal);
           return false;
        }
        date = isDate(date);
        if (!date){
           alert (eventStrings.dateVal);
           return false;
        }
        time = isTime(time);
        if (!time) {
           alert (eventStrings.startTimeVal);
           return false;
        }
        endTime = isTime(endTime);
        if (!endTime) {
           alert (eventStrings.endTimeVal);
           return false;
        }
        if (time>=endTime) {
           alert (eventStrings.timeVal);
           return false;
        }
        //ajax data
        var data = {
           action: 'utt_insert_update_event',
           eventID: eventID,
           eventType: eventType,
           eventTitle: eventTitle,
           eventDescr: eventDescr,
           classroom: classroom,
           date: date,
           time: time,
           endTime: endTime
        };
        //ajax call
        $.get('admin-ajax.php' , data, function(data){
           success = data;
           //success
           if (success == 1) {
               //insert
               if (eventID == 0) {
                  $('#messages').html("<div id='message' class='updated'>"+eventStrings.successAdd+"</div>");
               //edit
               }else{
                  $('#messages').html("<div id='message' class='updated'>"+eventStrings.successEdit+"</div>"); 
               }
               //clear form
               $('#eventTitle').html(eventStrings.insertEvent);
               $('#eventID').val(0);
               $('#eventType').val(0);
               $('#title').val("");
               $('#eventDescr').val("");
               $('#classroom').val(0);
               $('#date').val("");
               $('#time').val("08:00");
               $('#endTime').val("10:00");
               $('#clearEventForm').html(eventStrings.reset);
               isDirty = 0;
            //fail
            }else{
               //insert
               if (eventID == 0) {
                  $('#messages').html("<div id='message' class='error'>"+eventStrings.failAdd+"</div>");
               //edit
               }else{
                  $('#messages').html("<div id='message' class='error'>"+eventStrings.failEdit+"</div>");
               }
            }
            //load new data
            data = {
               action: 'utt_view_events',
               selectedYear: $('#yearFilter').val()
            };
            $.get('admin-ajax.php' , data, function(data){
               $('#eventsResults').html(data);
            });
        });
        return false;
    });
    //clear form
    $('#clearEventForm').click(function(){
        $('#eventTitle').html(eventStrings.insertEvent);
        $('#eventID').val(0);
        $('#eventType').val(0);
        $('#title').val("");
        $('#eventDescr').val("");
        $('#classroom').val(0);
        $('#date').val("");
        $('#time').val("08:00");
        $('#endTime').val("10:00");
        $('#clearEventForm').html(eventStrings.reset);
        $('#message').remove();
        $('#messages').show();
        isDirty = 0;
        return false;
    })
    //datepicker function to date element
    $( "#date" ).datepicker({dateFormat: 'dd/mm/yy'});
    //timespinner options
    $.widget( "ui.timespinner", $.ui.spinner, {
        options: {
            // seconds
            step: 60 * 60 * 1000,
            // hours
            page: 60
        },
 
        _parse: function( value ) {
            if ( typeof value === "string" ) {
                // already a timestamp
                if ( Number( value ) == value ) {
                   return Number( value );
                }
                return +Globalize.parseDate( value );
            }
            return value;
        },
 
        _format: function( value ) {
            return Globalize.format( new Date(value), "t" );
        }
    });
 
    Globalize.culture("de-DE");
    //timespinner function to time and endtime element
    $("#time").timespinner();
    $("#endTime").timespinner();
     //form is dirty
    $('.dirty').change(function(){
        isDirty = 1;
    })
    
});