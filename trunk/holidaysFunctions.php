<?php
//include js
function utt_holiday_scripts(){
    //include holiday scripts
    wp_enqueue_script( 'holidayScripts',  plugins_url('js/holidayScripts.js', __FILE__) );
    //localize holiday scripts
    wp_localize_script( 'holidayScripts', 'holidayStrings', array(
        'deleteForbidden' => __( 'Delete is forbidden while completing the form!', 'UniTimetable' ),
        'deleteHoliday' => __( 'Are you sure that you want to delete this Holiday?', 'UniTimetable' ),
        'holidayDeleted' => __( 'Holiday deleted successfully!', 'UniTimetable' ),
        'editForbidden' => __( 'Edit is forbidden while completing the form!', 'UniTimetable' ),
        'editHoliday' => __( 'Edit Holiday', 'UniTimetable' ),
        'cancel' => __( 'Cancel', 'UniTimetable' ),
        'nameVal' => __( 'Holiday name is required. Please avoid using special characters.', 'UniTimetable' ),
        'dateVal' => __( 'Date is invalid.', 'UniTimetable' ),
        'insertHoliday' => __( 'Insert Holiday', 'UniTimetable' ),
        'reset' => __( 'Reset', 'UniTimetable' ),
        'failAdd' => __( 'Failed to add Holiday. Check if there is a Holiday at that date.', 'UniTimetable' ),
        'successAdd' => __( 'Holiday successfully added!', 'UniTimetable' ),
        'successEdit' => __( 'Holiday successfully edited!', 'UniTimetable' ),
    ));
    //include styles and scripts
    wp_enqueue_style( 'smoothnesscss',  plugins_url('css/smoothness-jquery-ui.css', __FILE__) );
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');
}

//holidays page
function utt_create_holidays_page(){
    //holidays form
?>
<div class="wrap" >
    <h2 id="holidayTitle"> <?php _e("Insert Holiday","UniTimetable"); ?> </h2>
    <form action="" name="holidayForm" method="post">
        <input type="hidden" name="isEdit" id="isEdit" value=0 />
        <?php _e("Holiday name:","UniTimetable"); ?><br/>
        <input type="text" name="holidayName" id="holidayName" class="dirty" required placeholder="<?php _e("Required","UniTimetable"); ?>"/>
        <br/>
        <?php _e("Date:","UniTimetable"); ?><br/>
        <input type="text" name="holidayDate" id="holidayDate" class="dirty" value=""/>
        <br/>
        <div id="secondaryButtonContainer">
        <input type="submit" value="<?php _e("Submit","UniTimetable"); ?>" id="insert-updateHoliday" class="button-primary"/>
        <a href='#' class='button-secondary' id="clearHolidayForm"><?php _e("Reset","UniTimetable"); ?></a>
        </div>
    </form>
    <!-- place to view messages -->
    <div id="messages"></div>
    <?php 
    _e("Holidays of Year:","UniTimetable"); ?>
    <!-- show filter, select current year on first load -->
    <select name="yearFilter" id="yearFilter" onchange="viewHolidays();">
        <option value="0"><?php _e("All","UniTimetable"); ?></option>
        <?php
            $curYear = date("Y");
            $nextYear = $curYear+1;
            echo "<option value='$curYear' selected='selected'>$curYear</option>";
            echo "<option value='$nextYear'>$nextYear</option>";
        ?>
    </select>
    <!-- show holidays results -->
    <div id="holidaysResults">
        <?php utt_view_holidays(); ?>
    </div>
</div>

<?php
}

//show registered holidays
add_action('wp_ajax_utt_view_holidays','utt_view_holidays');
function utt_view_holidays(){
    global $wpdb;
    $holidaysTable = $wpdb->prefix."utt_holidays";
    //get filter value
    if(isset($_GET['yearFilter'])){
        $selectedYear = $_GET['yearFilter'];
    }else{
        $selectedYear = date("Y");
    }
    //add to sql code to filter data shown
    if($selectedYear != 0 && $selectedYear != ""){
        $safeSql = $wpdb->prepare("SELECT *, DATE_FORMAT(holidayDate,'%%d/%%m/%%Y') as changedDate FROM $holidaysTable WHERE YEAR(holidayDate)=%s ORDER BY holidayDate",$selectedYear);
    }else{
        $safeSql = "SELECT *, DATE_FORMAT(holidayDate,'%%d/%%m/%%Y') as changedDate FROM $holidaysTable ORDER BY holidayDate";
    }
    ?>
    <!-- show table with holidays -->
        <table class="widefat bold-th">
            <thead>
                <tr>
                    <th><?php _e("Date","UniTimetable"); ?></th>
                    <th><?php _e("Holiday name","UniTimetable"); ?></th>
                    <th><?php _e("Actions","UniTimetable"); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><?php _e("Date","UniTimetable"); ?></th>
                    <th><?php _e("Holiday name","UniTimetable"); ?></th>
                    <th><?php _e("Actions","UniTimetable"); ?></th>
                </tr>
            </tfoot>
            <tbody>
        <?php
        //select holidays
        $holidays = $wpdb->get_results($safeSql);
        //show grey and white records in order to be more recognizable
        $bgcolor = 1;
        foreach($holidays as $holiday){
            if($bgcolor == 1){
                $addClass = "class='grey'";
                $bgcolor = 2;
            }else{
                $addClass = "class='white'";
                $bgcolor = 1;
            }
            //a record
            echo "<tr id='$holiday->holidayDate' $addClass><td>$holiday->changedDate</td><td>$holiday->holidayName</td>
                <td><a href='#' onclick=\"deleteHoliday('$holiday->holidayDate');\" class='deleteHoliday'><img id='edit-delete-icon' src='".plugins_url('icons/delete_icon.png', __FILE__)."'/> ".__("Delete","UniTimetable")."</a>&nbsp;
                <a href='#' onclick=\"editHoliday('".$holiday->changedDate."','".$holiday->holidayName."');\" class='editHoliday'><img id='edit-delete-icon' src='".plugins_url('icons/edit_icon.png', __FILE__)."'/> ".__("Edit","UniTimetable")."</a></td></tr>";
        }
        
        ?>
            </tbody>
        </table>
<?php
if(isset($_GET['yearFilter'])){
    die();
}
}

//ajax response delete holiday
add_action('wp_ajax_utt_delete_holiday', 'utt_delete_holiday');
function utt_delete_holiday(){
    global $wpdb;
    $holidaysTable=$wpdb->prefix."utt_holidays";
    $safeSql = $wpdb->prepare("DELETE FROM `$holidaysTable` WHERE holidayDate=%s",$_GET['holiday_date']);
    $success = $wpdb->query($safeSql);
    //echo 1 if delete succeeded
    echo $success;
    die();
}

//ajax response insert-update holiday
add_action('wp_ajax_utt_insert_update_holiday','utt_insert_update_holiday');
function utt_insert_update_holiday(){
    global $wpdb;
    $holidayDate=$_GET['holiday_date'];
    $holidayName=$_GET['holiday_name'];
    $isEdit=$_GET['is_edit'];
    $holidaysTable=$wpdb->prefix."utt_holidays";
    //if isedit is 0, it is insert
    if($isEdit==0){
        $safeSql = $wpdb->prepare("INSERT INTO $holidaysTable (holidayDate, holidayName) VALUES (%s,%s)",$holidayDate,$holidayName);
        $success = $wpdb->query($safeSql);
        if($success == 1){
            //insert succeeded
           echo 1;
        }else{
            //insert failed
            echo 0;
        }
    //is edit
    }else{
        $safeSql = $wpdb->prepare("UPDATE $holidaysTable SET holidayName=%s WHERE holidayDate=%s ",$holidayName,$holidayDate);
        $wpdb->query($safeSql);
        echo 1;
    }
    die();
}

?>