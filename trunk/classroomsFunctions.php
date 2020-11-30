<?php
//include js
function utt_classroom_scripts(){
    //load classroomScripts
    wp_enqueue_script( 'classroomScripts',  plugins_url('js/classroomScripts.js', __FILE__) );
    //localize classroomScripts
    wp_localize_script( 'classroomScripts', 'classroomStrings', array(
        'deleteForbidden' => __( 'Delete is forbidden while completing the form!', 'UniTimetable' ),
        'deleteClassroom' => __( 'Are you sure that you want to delete this Classroom?', 'UniTimetable' ),
        'classroomDeleted' => __( 'Classroom deleted successfully!', 'UniTimetable' ),
        'classroomNotDeleted' => __( 'Failed to delete Classroom. Check if Classroom is used by a Lecture or Event.', 'UniTimetable' ),
        'editForbidden' => __( 'Edit is forbidden while completing the form!', 'UniTimetable' ),
        'editClassroom' => __( 'Edit Classroom', 'UniTimetable' ),
        'cancel' => __( 'Cancel', 'UniTimetable' ),
        'nameVal' => __( 'Classroom name is required. Please avoid using special characters.', 'UniTimetable' ),
        'typeVal' => __( 'Please select Classroom type.', 'UniTimetable' ),
        'insertClassroom' => __( 'Insert Classroom', 'UniTimetable' ),
        'reset' => __( 'Reset', 'UniTimetable' ),
        'failAdd' => __( 'Failed to add Classroom. Check if Classroom already exists.', 'UniTimetable' ),
        'successAdd' => __( 'Classroom successfully added!', 'UniTimetable' ),
        'failEdit' => __( 'Failed to edit Classroom. Check if Classroom already exists.', 'UniTimetable' ),
        'successEdit' => __( 'Classroom successfully edited!', 'UniTimetable' ),
    ));
}
//classroom page
function utt_create_classrooms_page(){
    //classroom form
    ?>
    <div class="wrap">
        <h2 id="classroomTitle"> <?php _e("Insert Classroom","UniTimetable"); ?> </h2>
        <form action="" name="classroomForm" method="post">
            <input type="hidden" name="classroomID" id="classroomID" value=0 />
            <?php _e("Classroom name:","UniTimetable"); ?><br/>
            <input type="text" name="classroomName" id="classroomName" class="dirty" value="" placeholder="<?php _e("Required","UniTimetable"); ?>"/>
            <br/>
            <?php _e("Classroom type:","UniTimetable"); ?><br/>
            <select name="classroomType" id="classroomType" class="dirty">
                <option value="0"><?php _e("- select -","UniTimetable"); ?></option>
                <option value="Lecture"><?php _e("Lecture","UniTimetable"); ?></option>
                <option value="Laboratory"><?php _e("Laboratory","UniTimetable"); ?></option>
            </select>
            <br/>
            <div id="secondaryButtonContainer">
                <input type="submit" value="<?php _e("Submit","UniTimetable"); ?>" id="insert-updateClassroom" class="button-primary"/>
                <a href='#' class='button-secondary' id="clearClassroomForm"><?php _e("Reset","UniTimetable"); ?></a>
            </div>
        </form>
        <!-- place to put messages -->
    <div id="messages"></div>
    <!-- place to put table with classroom results -->
    <div id="classroomsResults">
        <?php utt_view_classrooms(); ?>
    </div>
    <?php
}

//ajax response view classrooms
add_action('wp_ajax_utt_view_classrooms','utt_view_classrooms');
function utt_view_classrooms(){
    global $wpdb;
    //define classrooms table
    $classroomsTable=$wpdb->prefix."utt_classrooms";
    //select all classrooms
    $classrooms = $wpdb->get_results( "SELECT * FROM $classroomsTable ORDER BY name");
    ?>
    <!-- show classrooms table -->
    <table class="widefat bold-th">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?php _e("Name","UniTimetable"); ?></th>
                    <th><?php _e("Type","UniTimetable"); ?></th>
                    <th><?php _e("Actions","UniTimetable"); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th><?php _e("Name","UniTimetable"); ?></th>
                    <th><?php _e("Type","UniTimetable"); ?></th>
                    <th><?php _e("Actions","UniTimetable"); ?></th>
                </tr>
            </tfoot>
            <tbody>
    <?php
        //show grey and white records in order to be more recognizable
        $bgcolor = 1;
        foreach($classrooms as $classroom){
            if($bgcolor == 1){
                $addClass = "class='grey'";
                $bgcolor = 2;
            }else{
                $addClass = "class='white'";
                $bgcolor = 1;
            }
            if($classroom->type == "Lecture"){
                $type = __("Lecture","UniTimetable");
            }else{
                $type = __("Laboratory","UniTimetable");
            }
            //a record
            echo "<tr id='$classroom->classroomID' $addClass><td>$classroom->classroomID</td><td>$classroom->name</td><td>$type</td>
            <td><a href='#' onclick='deleteClassroom($classroom->classroomID);' class='deleteClassroom'><img id='edit-delete-icon' src='".plugins_url('icons/delete_icon.png', __FILE__)."'/> ".__("Delete","UniTimetable")."</a>&nbsp;
            <a href='#' onclick=\"editClassroom($classroom->classroomID,'$classroom->name','$classroom->type');\" class='editClassroom'><img id='edit-delete-icon' src='".plugins_url('icons/edit_icon.png', __FILE__)."'/> ".__("Edit","UniTimetable")."</a></td></tr>";
        }
    ?>
            </tbody>
        </table>
    <?php
    die();
}

//ajax response insert-update classroom
add_action('wp_ajax_utt_insert_update_classroom','utt_insert_update_classroom');
function utt_insert_update_classroom(){
    global $wpdb;
    //data to insert or update
    $classroomID=$_GET['classroom_id'];
    $classroomName=$_GET['classroom_name'];
    $classroomType=$_GET['classroom_type'];
    //define classrooms table
    $classroomsTable=$wpdb->prefix."utt_classrooms";
    //if classroomID is 0, so it is insert
    if($classroomID==0){
        $safeSql = $wpdb->prepare("INSERT INTO $classroomsTable (name, type) VALUES (%s,%s)",$classroomName,$classroomType);
        $success = $wpdb->query($safeSql);
        //if insert succeeded echo 1
        if($success == 1){
            echo 1;
        //else echo 0
        }else{
            echo 0;
        }
    //if classroomID is not 0, so it is edit
    }else{
        $safeSql = $wpdb->prepare("UPDATE $classroomsTable SET name=%s, type=%s WHERE classroomID=%d ",$classroomName,$classroomType,$classroomID);
        $success = $wpdb->query($safeSql);
        //if update succeeded echo 1
        if($success == 1){
            echo 1;
        //else echo 0
        }else{
            echo 0;
        }
    }
    die();
}

//ajax response delete classroom
add_action('wp_ajax_utt_delete_classroom', 'utt_delete_classroom');
function utt_delete_classroom(){
    global $wpdb;
    $classroomsTable=$wpdb->prefix."utt_classrooms";
    $safeSql = $wpdb->prepare("DELETE FROM $classroomsTable WHERE classroomID=%d",$_GET['classroom_id']);
    $success = $wpdb->query($safeSql);
    //if delete succeeds it echoes 1
    echo $success;
    die();
}
?>