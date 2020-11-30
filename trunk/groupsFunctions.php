<?php
//include js
function utt_group_scripts(){
    //include groupScripts
    wp_enqueue_script( 'groupScripts',  plugins_url('js/groupScripts.js', __FILE__) );
    //localize groupScripts
    wp_localize_script( 'groupScripts', 'groupStrings', array(
        'deleteForbidden' => __( 'Delete is forbidden while completing the form!', 'UniTimetable' ),
        'deleteGroup' => __( 'Are you sure that you want to delete this Group?', 'UniTimetable' ),
        'groupDeleted' => __( 'Group deleted successfully!', 'UniTimetable' ),
        'groupNotDeleted' => __( 'Failed to delete Group. Check if Group is used by a Lecture.', 'UniTimetable' ),
        'editForbidden' => __( 'Edit is forbidden while completing the form!', 'UniTimetable' ),
        'editGroup' => __( 'Edit Group', 'UniTimetable' ),
        'cancel' => __( 'Cancel', 'UniTimetable' ),
        'periodVal' => __( 'Please select a Period.', 'UniTimetable' ),
        'semesterVal' => __( 'Please select Semester.', 'UniTimetable' ),
        'subjectVal' => __( 'Please select Subject.', 'UniTimetable' ),
        'nameVal' => __( 'Please avoid using special characters and do not use long names.', 'UniTimetable' ),
        'insertGroup' => __( 'Insert Group', 'UniTimetable' ),
        'reset' => __( 'Reset', 'UniTimetable' ),
        'group' => __( 'Group', 'UniTimetable' ),
        'failAdd' => __( 'Failed to add Groups. Check if there are another Groups with the same attributes.', 'UniTimetable' ),
        'successAdd' => __( 'Groups added successfully!', 'UniTimetable' ),
        'failEdit' => __( 'Failed to edit Group. Check if there is another Group with the same attributes.', 'UniTimetable' ),
        'successEdit' => __( 'Group successfully edited!', 'UniTimetable' ),
    ));
}
//groups page
function utt_create_groups_page(){
    //group form
    ?>
    <div class="wrap">
        <h2 id="groupTitle"> <?php _e("Insert Group","UniTimetable"); ?> </h2>
        <form action="" name="groupForm" method="post">
            <input type="hidden" name="groupID" id="groupID" value=0 />
            <div class="element firstInRow">
            <?php _e("Period:","UniTimetable"); ?><br/>
            <select name="period" id="period" class="dirty">
                <option value="0"><?php _e("- select -","UniTimetable"); ?></option>
                <?php
                global $wpdb;
                //show registered periods
                $periodsTable=$wpdb->prefix."utt_periods";
                $periods = $wpdb->get_results( "SELECT * FROM $periodsTable ORDER BY year DESC");
                //translate periods semester
                foreach($periods as $period){
                    if($period->semester == "W"){
                        $semester = __("W","UniTimetable");
                    }else{
                        $semester = __("S","UniTimetable");
                    }
                    echo "<option value='$period->periodID'>$period->year $semester</option>";
                }
                ?>
            </select>
            </div>
            <div class="element">
            <?php _e("Semester:","UniTimetable"); ?><br/>
            <select name="semester" id="semester" class="dirty" onchange="loadSubjects(0);">
                <option value="0"><?php _e("- select -","UniTimetable"); ?></option>
                <?php
                //show semester numbers
                for( $i=1 ; $i<11 ; $i++ ){
                    echo "<option value='$i'>$i</option>";
                }
                ?>
            </select>
            </div>
            <div class="element firstInRow">
            <?php _e("Subject:","UniTimetable"); ?><br/>
            <!-- load subjects when semester number is selected -->
            <div id="subjects">
            <select name="subject" id="subject" class="dirty">
                <option value='0'><?php _e("- select -","UniTimetable"); ?></option>
            </select>
            </div>
            </div>
            <div class="element">
            <!-- select number of groups to be created -->
            <?php _e("Number of Groups:","UniTimetable"); ?><br/>
            <select name="groupsNumber" id="groupsNumber" class="dirty">
                <?php
                for($i=1;$i<16;$i++){
                    echo "<option value=$i>$i</option>";
                }
                ?>
            </select>
            </div>
            <div class="element firstInRow groupsName">
            <?php _e("Name of Groups (Prefix):","UniTimetable"); ?><br/>
            <!-- prefix of groups' names -->
            <input type="text" name="groupsName" id="groupsName" class="dirty" value="<?php _e("Group","UniTimetable"); ?>"/>
            </div>
            <div class="element counterStart">
            <?php _e("Counter Starτ:","UniTimetable"); ?>
            <!-- starting number of groups that will be created -->
            <select name="counterStart" id="counterStart" class="dirty">
                <?php
                for($i=1;$i<16;$i++){
                    echo "<option value=$i>$i</option>";
                }
                ?>
            </select>
            </div>
            <div id="secondaryButtonContainer">
                <input type="submit" value="<?php _e("Submit","UniTimetable"); ?>" id="insert-updateGroup" class="button-primary"/>
                <a href='#' class='button-secondary' id="clearGroupForm"><?php _e("Reset","UniTimetable"); ?></a>
            </div>
        </form>
        <!-- place to view messages -->
        <div id="messages"></div>
        <!-- filters to filter shown groups -->
        <span id="filter1">
    <?php _e("Period:","UniTimetable"); ?>
    <select name="periodFilter" id="periodFilter" onchange="viewGroups();">
        <?php
        $periodsTable=$wpdb->prefix."utt_periods";
        $periods = $wpdb->get_results( "SELECT * FROM $periodsTable ORDER BY year DESC");
        //current date
        $date = date("Y-m-d");
        echo "<option value=''>".__("- select -","UniTimetable")."</option>";
        foreach($periods as $period){
                //echo current and next year and select current
                $startWinter = $period->year."-09-01";
                $nextYear = $period->year +1;
                $endWinter = $nextYear . "-03-01";
                $startSpring = $period->year . "-03-01";
                $selected = "";
                if($date >= $startWinter && $date < $endWinter && $period->semester == "W"){
                        $selected = "selected='selected'";
                        $periodID = $period->periodID;
                }else if($date >= $startSpring && $date<$startWinter && $period->semester == "S"){
                        $selected = "selected='selected'";
                        $periodID = $period->periodID;
                }
                if($period->semester == "W"){
                    $semester = __("W","UniTimetable");
                }else{
                    $semester = __("S","UniTimetable");
                }
            echo "<option value='$period->periodID' $selected>$period->year $semester</option>";
        }
        ?>
    </select>
    </span>
    <span id="filter2">
        <?php _e("Semester:","UniTimetable"); ?>
        <select name="semesterFilter" id="semesterFilter" onchange="viewGroups();">
            <option value="0"><?php _e("All","UniTimetable"); ?></option>
            <?php
            for($i=1;$i<11;$i++){
                //select 1st semester
                if($i == 1){
                    $selected = "selected='selected'";
                }else{
                    $selected = "";
                }
                echo "<option value='$i' $selected>$i</option>";
            }
            ?>
        </select>
    </span>
    <!-- place to view inserted groups -->
    <div id="groupsResults">
        <?php utt_view_groups(); ?>
    </div>
    <?php
}

//ajax response view groups
add_action('wp_ajax_utt_view_groups','utt_view_groups');
function utt_view_groups(){
    global $wpdb;
    //get filter values
    if(isset($_GET['period_id'])){
        $periodID = $_GET['period_id'];
        $semester = $_GET['semester'];
    //if first time loaded, show results for current period and 1st semester
    }else{
        $periodsTable=$wpdb->prefix."utt_periods";
        $periods = $wpdb->get_results( "SELECT * FROM $periodsTable ORDER BY year DESC");
        $date = date("Y-m-d");
        foreach($periods as $period){
            $startWinter = $period->year."-09-01";
            $nextYear = $period->year +1;
            $endWinter = $nextYear . "-03-01";
            $startSpring = $period->year . "-03-01";
            if($date >= $startWinter && $date < $endWinter && $period->semester == "W"){
                $periodID = $period->periodID;
            }else if($date >= $startSpring && $date<$startWinter && $period->semester == "S"){
                $periodID = $period->periodID;
            }
        }
        $semester = 1;
    }
    //show registered groups
    $groupsTable = $wpdb->prefix."utt_groups";
    $subjectsTable = $wpdb->prefix."utt_subjects";
    //if not selected period, show nothing
    if($periodID == ""){
        $periodID=0;
    }
    //if not selected semester, show for all semesters
    if($semester==0){
        $safeSql = $wpdb->prepare("SELECT * FROM $groupsTable, $subjectsTable WHERE $groupsTable.subjectID=$subjectsTable.subjectID AND periodID=%d ORDER BY title, type, groupName",$periodID);
    }else{
        $safeSql = $wpdb->prepare("SELECT * FROM $groupsTable, $subjectsTable WHERE $groupsTable.subjectID=$subjectsTable.subjectID AND periodID=%d AND semester=%d ORDER BY title, type, groupName",$periodID,$semester);
    }
    $groups = $wpdb->get_results($safeSql);
    ?>
        <!-- show table of groups -->
        <table class="widefat bold-th">
            <thead>
                <tr>
                    <th><?php _e("Subject","UniTimetable"); ?></th>
                    <th><?php _e("Group","UniTimetable"); ?></th>
                    <th><?php _e("Actions","UniTimetable"); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><?php _e("Subject","UniTimetable"); ?></th>
                    <th><?php _e("Group","UniTimetable"); ?></th>
                    <th><?php _e("Actions","UniTimetable"); ?></th>
                </tr>
            </tfoot>
            <tbody>
    <?php
        //show grey and white records in order to be more recognizable
        $bgcolor = 1;
        foreach($groups as $group){
            if($bgcolor == 1){
                $addClass = "class='grey'";
                $bgcolor = 2;
            }else{
                $addClass = "class='white'";
                $bgcolor = 1;
            }
            if($group->type == "T"){
                $type = __("T","UniTimetable");
            }else if($group->type == "L"){
                $type = __("L","UniTimetable");
            }else{
                $type = __("PE","UniTimetable");
            }
            //a record
            echo "<tr id='$group->groupID' $addClass><td>$group->title $type</td><td>$group->groupName</td>
                <td><a href='#' onclick='deleteGroup($group->groupID);' class='deleteGroup'><img id='edit-delete-icon' src='".plugins_url('icons/delete_icon.png', __FILE__)."'/> ".__("Delete","UniTimetable")."</a>&nbsp;
                <a href='#' onclick=\"editGroup($group->groupID,$group->periodID,$group->semester,$group->subjectID,'$group->groupName');\" class='editGroup'><img id='edit-delete-icon' src='".plugins_url('icons/edit_icon.png', __FILE__)."'/> ".__("Edit","UniTimetable")."</a></td></tr>";
        }
    ?>
            </tbody>
        </table>
    <?php
    die();
}

//ajax response insert-update group
add_action('wp_ajax_utt_insert_update_group','utt_insert_update_group');
function utt_insert_update_group(){
    global $wpdb;
    //data to be inserted/updated
    $groupID=$_GET['group_id'];
    $periodID=$_GET['period_id'];
    $subjectID=$_GET['subject_id'];
    $groupName=$_GET['group_name'];
    $counterStart=$_GET['counter_start'];
    $groupsNumber=$_GET['groups_number'];
    $groupsTable=$wpdb->prefix."utt_groups";
    $success = 0;
    // if groupID is 0, it is insert
    if($groupID==0){
        //transaction, so if an insert fails, it rolls back
        $wpdb->query('START TRANSACTION');
        for($i=1;$i<=$groupsNumber;$i++){
            //name is generated by prefix(groupName) and a number, starting from counterstart
            $nameUsed = $groupName.$counterStart;
            $safeSql = $wpdb->prepare("INSERT INTO $groupsTable (periodID, subjectID, groupName) VALUES (%d,%d,%s)",$periodID,$subjectID,$nameUsed);
            $success = $wpdb->query($safeSql);
            $counterStart ++;
            if($success != 1){
                //if an insert fails, for breaks
                $success = 0;
                break;
            }
        }
        //if every insert succeeds, commit transaction
        if($success == 1){
            $wpdb->query('COMMIT');
            echo 1;
        //else rollback
        }else{
            $wpdb->query('ROLLBACK');
            echo 0;
        }
    //it is edit
    }else{
        $safeSql = $wpdb->prepare("UPDATE $groupsTable SET periodID=%d, subjectID=%d, groupName=%s WHERE groupID=%d ",$periodID,$subjectID,$groupName,$groupID);
        $success = $wpdb->query($safeSql);
        if ($success==1){
            echo 1;
        }else{
            echo 0;
        }
    }
    die();
}

//ajax response delete group
add_action('wp_ajax_utt_delete_group', 'utt_delete_group');
function utt_delete_group(){
    global $wpdb;
    $groupsTable=$wpdb->prefix."utt_groups";
    $safeSql = $wpdb->prepare("DELETE FROM `$groupsTable` WHERE groupID=%d",$_GET['group_id']);
    $success = $wpdb->query($safeSql);
    //if success is 1, delete succeeded
    echo $success;
    die();
}
//load subjects to combo-box when semester number is selected
add_action('wp_ajax_utt_load_groupsubjects','utt_load_groupsubjects');
function utt_load_groupsubjects(){
    //semester number selected
    $semester = $_GET['semester'];
    //if edit, select the stored subject
    $selected = $_GET['selected'];
    global $wpdb;
    $subjectsTable = $wpdb->prefix."utt_subjects";
    $safeSql = $wpdb->prepare("SELECT * FROM $subjectsTable WHERE semester=%d ORDER BY title;",$semester);
    $subjects = $wpdb->get_results($safeSql);
    echo "<select name='subject' id='subject' class='dirty'>";
    echo "<option value='0'>".__("- select -","UniTimetable")."</option>";
    foreach($subjects as $subject){
        //if edit, select the stored subject
        if($selected==$subject->subjectID){
            $select = "selected='selected'";
        }else{
            $select = "";
        }
        //translate subject type
        if($subject->type == "T"){
            $type = __("T","UniTimetable");
        }else if($subject->type == "L"){
            $type = __("L","UniTimetable");
        }else{
            $type = __("PE","UniTimetable");
        }
        echo "<option value='$subject->subjectID' $select>$subject->title $type</option>";
    }
    echo "</select>";
    die();
}

?>