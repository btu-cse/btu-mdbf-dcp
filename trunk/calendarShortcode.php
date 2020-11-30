<?php
//register utt_calendar shortcode
add_shortcode('utt_calendar','load_utt_calendar');
//utt_calendar shortcode function
function load_utt_calendar($attr){
    //include scripts and styles to page
    wp_enqueue_script( 'moment',  plugins_url('js/moment.min.js', __FILE__) );
    wp_enqueue_script( 'fullcalendar',  plugins_url('js/fullcalendar.js', __FILE__) );
    wp_enqueue_script( 'fullcalendargreek',  plugins_url('js/el.js', __FILE__) );
    wp_enqueue_style( 'fullcalendarcss',  plugins_url('css/fullcalendar.css', __FILE__) );
    wp_enqueue_script('qtipjs', plugins_url('js/jquery.qtip.min.js', __FILE__));
    wp_enqueue_style( 'qtipcss',  plugins_url('css/jquery.qtip.min.css', __FILE__) );
    wp_enqueue_script( 'calendarScripts',  plugins_url('js/calendarScripts.js', __FILE__) );
    //localize calendar scripts
    wp_localize_script( 'calendarScripts', 'calendarStrings', array(
        'lang' => __( 'en', 'UniTimetable' ),
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ));
    wp_enqueue_style( 'utt_style',  plugins_url('css/utt_style.css', __FILE__) );
    global $wpdb;
    $sc="";
    //if no parameter is used show filters
    if(!isset($attr['teacher']) && !isset($attr['classroom']) && !isset($attr['semester'])){
    $sc= '<div id="filters">
        <span id="filter1">'
            .__("View per:","UniTimetable").
            '<select name="filterSelect1" id="filterSelect1">
                <option value="semester">'.__("Semester","UniTimetable").'</option>
                <option value="teacher">'.__("Teacher","UniTimetable").'</option>
                <option value="classroom" selected="selected">'.__("Classroom","UniTimetable").'</option>
            </select>
        </span>
        <span id="filter2">
            <select name="filterSelect2" id="filterSelect2" onchange="filterFunction();">
                <option value="0">'.__("Select","UniTimetable").'</option>';
                /*for($i=1;$i<8;$i++){
                    if($i==1){
                        $selected = "selected='selected'";
                    }else{
                        $selected = "";
                    }
                    echo "<option value='$i' $selected>$i</option>";
                }*/
                $classroomsTable = $wpdb->prefix."utt_classrooms";
                $classrooms = $wpdb->get_results("SELECT * FROM $classroomsTable ORDER BY name;");
                foreach($classrooms as $classroom){
                    if($classroom->classroomID==12){
                        $selected = "selected='selected'";
                    }else{
                        $selected = "";
                    }
                    $sc.= "<option value='$classroom->classroomID' $selected>$classroom->name </option>";
                }
                
            $sc.='</select>
        </span>
        <img id="loadingImg" src="'.plugins_url('icons/spinner.gif', __FILE__).'"/>
    </div>';
    //if a parameter is used load hidden fields to show filtered data without combo-boxes
    }else if($attr['teacher'] != ""){
        $sc.= "<input type='hidden' name='filterSelect1' id='filterSelect1' value='teacher' />";
        $sc.= "<input type='hidden' name='filterSelect2' id='filterSelect2' value='$attr[teacher]' />";
    }else if($attr['classroom'] != ""){
        $sc.= "<input type='hidden' name='filterSelect1' id='filterSelect1' value='classroom' />";
        $sc.= "<input type='hidden' name='filterSelect2' id='filterSelect2' value='$attr[classroom]' />";
    }else if($attr['semester'] != ""){
        $sc.= "<input type='hidden' name='filterSelect1' id='filterSelect1' value='semester' />";
        $sc.= "<input type='hidden' name='filterSelect2' id='filterSelect2' value='$attr[semester]' />";
    }
    
    $sc.='<div id="calendar"></div>';
    return $sc;
}

//json response view lectures (returns json)
add_action('wp_ajax_utt_json_calendar_front','utt_json_calendar_front');
//used to enable simple visitors to use ajax functions
add_action('wp_ajax_nopriv_utt_json_calendar_front','utt_json_calendar_front');
function utt_json_calendar_front(){
    global $wpdb;
    //get data selected from combo-boxes or hidden fields
    $viewType = $_POST['viewType'];
    $viewFilter = $_POST['viewFilter'];
    $lecturesView = $wpdb->prefix."utt_lectures_view";
    $start = $_POST['start'];
    $end = $_POST['end'];
    //add limitations to query
    switch ($viewType){
        case "semester":
            $safeSql = $wpdb->prepare("SELECT * FROM $lecturesView WHERE DATE(start) BETWEEN %s AND %s AND semester IN (%d);",$start,$end,$viewFilter);
            break;
        case "teacher":
            $safeSql = $wpdb->prepare("SELECT * FROM $lecturesView WHERE DATE(start) BETWEEN %s AND %s AND teacherID IN (%d);",$start,$end,$viewFilter);
            break;
        case "classroom":
            $safeSql = $wpdb->prepare("SELECT * FROM $lecturesView WHERE DATE(start) BETWEEN %s AND %s AND classroomID IN (%d);",$start,$end,$viewFilter);
            break;
    }
    $lecturesTable = $wpdb->prefix."utt_lectures";
    //select filtered lectures
    
    $lectures = $wpdb->get_results($safeSql);
    //array which is going to be converted to json response
    $jsonResponse = array();
    //load different colors when filtered by teacher
    require('calendarColors.php');
    foreach($lectures as $lecture){
        //if filtered by teacher load colors from calendarColors.php
        if($viewType=="teacher"){
            switch($lecture->subjectType){
                case "L":
                    $color = $colors[$lecture->semester-1][0];
                    break;
                case "T":
                    $color = $colors[$lecture->semester-1][1];
                    break;
                case "PE":
                    $color = $colors[$lecture->semester-1][2];
                    break;
            }
        }else{
            $color = "#".$lecture->color;
        }
        //translate subject type
        if($lecture->subjectType == "T"){
            $subjectType = __("T","UniTimetable");
        }else if($lecture->subjectType == "L"){
            $subjectType = __("L","UniTimetable");
        }else{
            $subjectType = __("PE","UniTimetable");
        }
        //lecture response data
        $result = array();
        $result['title'] = $lecture->subjectTitle." ".$subjectType.", ".$lecture->groupName.", ".$lecture->teacherSurname." ".$lecture->teacherName.", ".$lecture->classroomName;
        $result['start'] = $lecture->start;
        $result['end'] = $lecture->end;
        $result['periodID'] = $lecture->periodID;
        $result['semester'] = $lecture->semester;
        $result['subjectID'] = $lecture->subjectID;
        $result['groupID'] = $lecture->groupID;
        $result['teacherID'] = $lecture->teacherID;
        $result['classroomID'] = $lecture->classroomID;
        $result['lectureID'] = $lecture->lectureID;
        $result['start2'] = $lecture->start;
        $result['end2'] = $lecture->end;
        $result['color'] = $color;
        $result['textColor'] = "black";
        $result['descr'] = "";
        array_push($jsonResponse,$result);
    }
    //define holidays table
    $holidaysTable = $wpdb->prefix."utt_holidays";
    //select holidays of selected week
    $holidays = $wpdb->get_results($wpdb->prepare("SELECT * FROM $holidaysTable WHERE holidayDate BETWEEN %s AND %s;",$start,$end));
    foreach($holidays as $holiday){
        //holiday response data
        $result = array();
        $result['title'] = $holiday->holidayName;
        $result['allDay'] = true;
        $result['start'] = $holiday->holidayDate;
        $result['color'] = "red";
        $result['textColor'] = "black";
        $result['descr'] = "";
        $result['buttons'] = false;
        array_push($jsonResponse,$result);
    }
    //define events table
    $eventsTable = $wpdb->prefix."utt_events";
    //define classrooms table
    $classroomsTable = $wpdb->prefix."utt_classrooms";
    //if filtered by classroom show events for selected classroom
    if($viewType=="classroom"){
        $events = $wpdb->get_results($wpdb->prepare("SELECT * FROM $eventsTable,$classroomsTable WHERE $eventsTable.classroomID=$classroomsTable.classroomID AND DATE(eventStart) BETWEEN %s AND %s AND $eventsTable.classroomID=%d;",$start,$end,$viewFilter));
        $queryExtension = "";
    }else{
        $events = $wpdb->get_results($wpdb->prepare("SELECT * FROM $eventsTable,$classroomsTable WHERE $eventsTable.classroomID=$classroomsTable.classroomID AND DATE(eventStart) BETWEEN %s AND %s;",$start,$end));
        $queryExtension = "";
    }
    //select events for selected week
    
    foreach($events as $event){
        //translate event type
        switch($event->eventType){
            case "Thesis":
                $eventType = __("Thesis","UniTimetable");
                break;
            case "Speech":
                $eventType = __("Speech","UniTimetable");
                break;
            case "Presentation":
                $eventType = __("Presentation","UniTimetable");
                break;
            case "Students Team":
                $eventType = __("Students Team","UniTimetable");
                break;
            case "Graduation":
                $eventType = __("Graduation","UniTimetable");
                break;
        }
        //event response data
        $result = array();
        $result['title'] = $eventType.", ".$event->eventTitle.", ".$event->name;
        $result['start'] = $event->eventStart;
        $result['end'] = $event->eventEnd;
        $result['color'] = "black";
        $result['textColor'] = "white";
        $result['descr'] = ", ".$event->eventDescr;
        $result['buttons'] = false;
        array_push($jsonResponse,$result);
    }
    //echo json to let calendar use it to show lectures, events and holidays
    echo json_encode($jsonResponse);
    die();
}

//ajax response load filter
add_action('wp_ajax_utt_load_filter_front', 'utt_load_filter_front');
add_action('wp_ajax_nopriv_utt_load_filter_front', 'utt_load_filter_front');
function utt_load_filter_front(){
    global $wpdb;
    $viewType = $_GET['viewType'];
    echo "<select name='filterSelect2' id='filterSelect2' onchange='filterFunction();'>";
    echo "<option value='0'>".__("- select -","UniTimetable")."</option>";
    //load second filter depending on the first one
    switch($viewType){
        case "semester":
            for($i=1;$i<11;$i++){
                echo "<option value='$i'>$i</option>";
            }
            break;
        case "teacher":
            $teachersTable = $wpdb->prefix."utt_teachers";
            $teachers = $wpdb->get_results("SELECT * FROM $teachersTable ORDER BY surname, name;");
            foreach($teachers as $teacher){
                echo "<option value='$teacher->teacherID'>$teacher->surname $teacher->name </option>";
            }
            break;
        case "classroom":
            $classroomsTable = $wpdb->prefix."utt_classrooms";
            $classrooms = $wpdb->get_results("SELECT * FROM $classroomsTable ORDER BY name;");
            foreach($classrooms as $classroom){
                echo "<option value='$classroom->classroomID'>$classroom->name </option>";
            }
            break;
    }
    echo "</select>";
    die();
}

?>