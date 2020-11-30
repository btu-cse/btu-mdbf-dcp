//this function runs every time filterSelect2 is changed and refreshes calendar content with new filtered
function filterFunction() {
   var viewType = jQuery('#filterSelect1').val();
   var viewFilter = jQuery('#filterSelect2').val();
   var events = {
      url: calendarStrings.ajax_url,
      type: 'POST',
      data: {
         action: 'utt_json_calendar_front',    // an option!
         viewType: jQuery('#filterSelect1').val(),  // an option!
         viewFilter: jQuery('#filterSelect2').val()
      }
   }
   //refresh calendar content
   jQuery('#calendar').fullCalendar('removeEventSource', events);
   jQuery('#calendar').fullCalendar('addEventSource', events);
   loadCalendar();
}
//reloads json data
function loadCalendar() {
   jQuery('#calendar').fullCalendar( 'refetchEvents' );
}
jQuery(function ($) {
    
   $(document).ready(function() {
      
      // page is now ready, initialize the calendar...
      $('#calendar').fullCalendar({
         lang: calendarStrings.lang,
         eventSources: [

            // event source
            {
               url: calendarStrings.ajax_url,
               type: 'POST',
               data: {
                  action: 'utt_json_calendar_front',    // an option!
                  viewType: $('#filterSelect1').val(),  // an option!
                  viewFilter: $('#filterSelect2').val()
               },
               error: function() {
                   alert('there was an error while fetching events!');
               }
            },
         ],
         loading: function (bool) { 
            if (bool)
               //show spinner when loading
               $('#loadingImg').show(); 
             else 
               $('#loadingImg').hide(); 
         },
         timeFormat: 'H:mm' ,
         eventRender: function(event, element) {
            //show popup on mouseover
            element.qtip({
               show: 'hover',
               hide: { when: { event: 'unfocus' } },
               content: event.title + ", " + event.descr
            });
         },
      
        
    
  
      });
   });
   //loads a new filterSelect2 on change
   $('#filterSelect1').change(function(){
      viewType = $('#filterSelect1').val();
      var data = {
         action: 'utt_load_filter_front',
         viewType: viewType
      };
      $.get(calendarStrings.ajax_url, data, function(data){
         $('#filter2').html(data);
      });
   });
});