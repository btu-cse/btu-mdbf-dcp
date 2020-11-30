<?php
//include js
function utt_joint_scripts(){
    //load jointScripts.js file
    wp_enqueue_script( 'jointScripts',  plugins_url('js/jointScripts.js?v=1576159001', __FILE__) );
    //localize joint strings
    wp_localize_script( 'jointScripts', 'jointStrings', array(
        'deleteForbidden' => __( 'Formu doldururken silmek yasaktır!', 'UniTimetable' ),
        'deleteJoint' => __( 'Bu birleşmeyi silmek istiyor musunuz?', 'UniTimetable' ),
        'jointDeleted' => __( 'Birleşme başarıyla silindi!', 'UniTimetable' ),
        'jointNotDeleted' => __( 'Birleşme silinemedi. Birleşen derslerin eklenen dersler ile bağlantılı olup olmadığını kontrol edin.', 'UniTimetable' ),
        'editForbidden' => __( 'Lütfen bir birleşme kuralı seçin!', 'UniTimetable' ),
        'editJoint' => __( 'Birleşmeyi güncelle', 'UniTimetable' ),
        'cancel' => __( 'İptal', 'UniTimetable' ),
        'yearVal' => __( 'Yıl alanı limitlerin dışında.', 'UniTimetable' ),
        'semesterVal' => __( 'Konu düzenlenemedi. Konunun var olup olmadığı kontrol kontrolü edin', 'UniTimetable' ),
        'insertJoint' => __( 'Dersleri birleştir', 'UniTimetable' ),
        'reset' => __( 'Sıfırla', 'UniTimetable' ),
        'failAdd' => __( 'Birleşme eklenemedi. Birleşmenin zaten var olup olmadığını kontrol edin.', 'UniTimetable' ),
        'successAdd' => __( 'Dersler birleştirildi!', 'UniTimetable' ),
        'failEdit' => __( 'Birleşme güncellenemedi. Birleşimin zaten var olup olmadığını kontrol edin.', 'UniTimetable' ),
        'successEdit' => __( 'Birleşme başarıyla güncellendi!', 'UniTimetable' ),
    ));
    
    
    
	wp_enqueue_script( 'sumojs',  plugins_url('sumo/jquery.sumoselect.js?v=1576159000', __FILE__) );
    wp_enqueue_style( 'sumocss',  plugins_url('sumo/sumoselect.css?v=1576159001', __FILE__) );
    
}



//joints page
function utt_create_joints_page(){
    global $wpdb;
    
	global $departments;
	
    $lecturesTable = $wpdb->prefix."utt_lectures";
    $groupsTable = $wpdb->prefix."utt_groups";
    $subjectsTable = $wpdb->prefix."utt_subjects";


    //joint form
    ?>
    <div class="wrap">
        <h2 id="jointTitle"> <?php _e("Dersleri birleştir","UniTimetable"); ?> </h2>
        <form action="" name="jointForm" method="post">
            <input type="hidden" name="jointid" id="jointid" value=0 />
            
            <?php _e("Birleşme:","UniTimetable"); ?><br/>


            <select name="s1" id="s1" onchange='checkSelectedSubjects();' class="dirty" data-search="true">
            <option value='0'><?php _e("- seç -","UniTimetable"); ?></option>
                <?php
                    $subjects = $wpdb->get_results("SELECT S.subjectID, S.title, S.semester, S.type FROM $subjectsTable S;");
                    foreach($subjects as $subject)
                    {
                        echo "<option value='$subject->subjectID'>".$departments[$subject->semester-1]." » $subject->title ".SbjType($subject->type)."</option>";
                    }
                ?>
            </select>
            
            <select name="s2" id="s2" onchange='checkSelectedSubjects();' class="dirty" disabled data-search="true">
            <option value='0'><?php _e("- seç -","UniTimetable"); ?></option>
                <?php
                    $subjects = $wpdb->get_results("SELECT S.subjectID, S.title, S.semester, S.type FROM $subjectsTable S;");
                    foreach($subjects as $subject)
                    {
                        echo "<option value='$subject->subjectID'>".$departments[$subject->semester-1]." » $subject->title ".SbjType($subject->type)."</option>";
                    }
                ?>
            </select>
            
            <select name="s3" id="s3" onchange='checkSelectedSubjects();' class="dirty" disabled data-search="true">
            <option value='0'><?php _e("- seç -","UniTimetable"); ?></option>
                <?php
                    $subjects = $wpdb->get_results("SELECT S.subjectID, S.title, S.semester, S.type FROM $subjectsTable S;");
                    foreach($subjects as $subject)
                    {
                        echo "<option value='$subject->subjectID'>".$departments[$subject->semester-1]." » $subject->title ".SbjType($subject->type)."</option>";
                    }
                ?>
            </select>
            
            <select name="s4" id="s4" onchange='checkSelectedSubjects();' class="dirty" disabled data-search="true">
            <option value='0'><?php _e("- seç -","UniTimetable"); ?></option>
                <?php
                    $subjects = $wpdb->get_results("SELECT S.subjectID, S.title, S.semester, S.type FROM $subjectsTable S;");
                    foreach($subjects as $subject)
                    {
                        echo "<option value='$subject->subjectID'>".$departments[$subject->semester-1]." » $subject->title ".SbjType($subject->type)."</option>";
                    }
                ?>
            </select>
            
            <select name="s5" id="s5" onchange='checkSelectedSubjects();' class="dirty" disabled data-search="true">
            <option value='0'><?php _e("- seç -","UniTimetable"); ?></option>
                <?php
                    $subjects = $wpdb->get_results("SELECT S.subjectID, S.title, S.semester, S.type FROM $subjectsTable S;");
                    foreach($subjects as $subject)
                    {
                        echo "<option value='$subject->subjectID'>".$departments[$subject->semester-1]." » $subject->title ".SbjType($subject->type)."</option>";
                    }
                ?>
            </select>
            
            
            <select name="s6" id="s6" onchange='checkSelectedSubjects();' class="dirty" disabled data-search="true">
            <option value='0'><?php _e("- seç -","UniTimetable"); ?></option>
                <?php
                    $subjects = $wpdb->get_results("SELECT S.subjectID, S.title, S.semester, S.type FROM $subjectsTable S;");
                    foreach($subjects as $subject)
                    {
                        echo "<option value='$subject->subjectID'>".$departments[$subject->semester-1]." » $subject->title ".SbjType($subject->type)."</option>";
                    }
                ?>
            </select>
            
            
            
            <select name="s7" id="s7" onchange='checkSelectedSubjects();' class="dirty" disabled data-search="true">
            <option value='0'><?php _e("- seç -","UniTimetable"); ?></option>
                <?php
                    $subjects = $wpdb->get_results("SELECT S.subjectID, S.title, S.semester, S.type FROM $subjectsTable S;");
                    foreach($subjects as $subject)
                    {
                        echo "<option value='$subject->subjectID'>".$departments[$subject->semester-1]." » $subject->title ".SbjType($subject->type)."</option>";
                    }
                ?>
            </select>
            
            
            


            <br/>
            
            
            <div id="secondaryButtonContainer">
                <input type="submit" value="<?php _e("Birleştir","UniTimetable"); ?>" id="insert-updateJoint" class="button-primary"/>
                <?php /*<a href='#' class='button-secondary' id="clearJointForm"><?php _e("Sıfırla","UniTimetable"); ?></a> */ ?>
            </div>
        </form>
    <!-- place to show messages -->
    <div id="messages"></div>
    <!-- place to show results table -->
    <div id="jointsResults">
        <?php utt_view_joints(); ?>
    </div>
    </div>
    <?php
}
//show registered joints
add_action('wp_ajax_utt_view_joints', 'utt_view_joints');
function utt_view_joints(){
    global $wpdb;
    global $departments, $departmentusers;

    
    $userid = get_current_user_id();

    $wpdb->suppress_errors = false;
	$wpdb->show_errors = true;


    $jointsTable=$wpdb->prefix."utt_joint";
    $subjectsTable=$wpdb->prefix."utt_subjects";
    $joints = $wpdb->get_results( "SELECT J.jointID jointID, J.s1ID s1ID, J.s2ID s2ID, J.s3ID s3ID, J.s4ID s4ID, J.s5ID s5ID, J.s6ID s6ID, J.s7ID s7ID, J.s2ok s2ok, J.s3ok s3ok, J.s4ok s4ok, J.s5ok s5ok, J.s6ok s6ok, J.s7ok s7ok, S1.title s1title, S2.title s2title, S3.title s3title, S4.title s4title, S5.title s5title, S6.title s6title, S7.title s7title, S1.semester s1department, S2.semester s2department, S3.semester s3department, S4.semester s4department, S5.semester s5department, S6.semester s6department, S7.semester s7department, S1.type s1type, S2.type s2type, S3.type s3type, S4.type s4type, S5.type s5type, S6.type s6type, S7.type s7type FROM $jointsTable J LEFT JOIN $subjectsTable S1 ON J.s1ID=S1.subjectID LEFT JOIN $subjectsTable S2 ON J.s2ID=S2.subjectID LEFT JOIN $subjectsTable S3 ON J.s3ID=S3.subjectID LEFT JOIN $subjectsTable S4 ON J.s4ID=S4.subjectID LEFT JOIN $subjectsTable S5 ON J.s5ID=S5.subjectID LEFT JOIN $subjectsTable S6 ON J.s6ID=S6.subjectID LEFT JOIN $subjectsTable S7 ON J.s7ID=S7.subjectID  ORDER BY J.jointID DESC");
    

    ?>
    <!-- show table with joints -->
    <table class="widefat bold-th">
            <thead>
                <tr>
                    <th><?php _e("Ders 1","UniTimetable"); ?></th>
                    <th><?php _e("Eylemler","UniTimetable"); ?></th>
                    
                    <th><?php _e("Ders 2","UniTimetable"); ?></th>
                    <th><?php _e("Ders 2 Onay","UniTimetable"); ?></th>
                    
                    <th><?php _e("Ders 3","UniTimetable"); ?></th>
                    <th><?php _e("Ders 3 Onay","UniTimetable"); ?></th>
                    
                    <th><?php _e("Ders 4","UniTimetable"); ?></th>
                    <th><?php _e("Ders 4 Onay","UniTimetable"); ?></th>
                    
                    <th><?php _e("Ders 5","UniTimetable"); ?></th>
                    <th><?php _e("Ders 5 Onay","UniTimetable"); ?></th>
                    
                    
                    <th><?php _e("Ders 6","UniTimetable"); ?></th>
                    <th><?php _e("Ders 6 Onay","UniTimetable"); ?></th>
                    
                    
                    <th><?php _e("Ders 7","UniTimetable"); ?></th>
                    <th><?php _e("Ders 7 Onay","UniTimetable"); ?></th>
                    
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><?php _e("Ders 1","UniTimetable"); ?></th>
                    <th><?php _e("Eylemler","UniTimetable"); ?></th>
                    
                    <th><?php _e("Ders 2","UniTimetable"); ?></th>
                    <th><?php _e("Ders 2 Onay","UniTimetable"); ?></th>
                    
                    <th><?php _e("Ders 3","UniTimetable"); ?></th>
                    <th><?php _e("Ders 3 Onay","UniTimetable"); ?></th>
                    
                    <th><?php _e("Ders 4","UniTimetable"); ?></th>
                    <th><?php _e("Ders 4 Onay","UniTimetable"); ?></th>
                    
                    <th><?php _e("Ders 5","UniTimetable"); ?></th>
                    <th><?php _e("Ders 5 Onay","UniTimetable"); ?></th>
                    
                    
                    <th><?php _e("Ders 6","UniTimetable"); ?></th>
                    <th><?php _e("Ders 6 Onay","UniTimetable"); ?></th>
                    
                    <th><?php _e("Ders 7","UniTimetable"); ?></th>
                    <th><?php _e("Ders 7 Onay","UniTimetable"); ?></th>
                    
                </tr>
            </tfoot>
            <tbody>
    <?php
        //show grey and white records in order to be more recognizable
        $bgcolor = 1;
        foreach($joints as $joint)
        {
            if($bgcolor == 1)
            {
                $addClass = "class='grey'";
                $bgcolor = 2;
            }
            else
            {
                $addClass = "class='white'";
                $bgcolor = 1;
            }
            

            $s2okdisabled = "";
            if($joint->s2department==null || (!IsAdmin() && $userid != $departmentusers[$joint->s2department-1]))
            {
                $s2okdisabled = "disabled";
            }
            $s3okdisabled = "";
            if($joint->s3department==null || (!IsAdmin() && $userid != $departmentusers[$joint->s3department-1]))
            {
                $s3okdisabled = "disabled";
            }
            $s4okdisabled = "";
            if($joint->s4department==null || (!IsAdmin() && $userid != $departmentusers[$joint->s4department-1]))
            {
                $s4okdisabled = "disabled";
            }
            $s5okdisabled = "";
            if($joint->s5department==null || (!IsAdmin() && $userid != $departmentusers[$joint->s5department-1]))
            {
                $s5okdisabled = "disabled";
            }
            $s6okdisabled = "";
            if($joint->s6department==null || (!IsAdmin() && $userid != $departmentusers[$joint->s6department-1]))
            {
                $s6okdisabled = "disabled";
            }
            $s7okdisabled = "";
            if($joint->s7department==null || (!IsAdmin() && $userid != $departmentusers[$joint->s7department-1]))
            {
                $s7okdisabled = "disabled";
            }

            $s2okchecked = "";
            if($joint->s2ok == 1)
            {
                $s2okchecked = "checked";
            }
            $s3okchecked = "";
            if($joint->s3ok == 1)
            {
                $s3okchecked = "checked";
            }
            $s4okchecked = "";
            if($joint->s4ok == 1)
            {
                $s4okchecked = "checked";
            }
            $s5okchecked = "";
            if($joint->s5ok == 1)
            {
                $s5okchecked = "checked";
            }
            $s6okchecked = "";
            if($joint->s6ok == 1)
            {
                $s6okchecked = "checked";
            }
            $s7okchecked = "";
            if($joint->s7ok == 1)
            {
                $s7okchecked = "checked";
            }
            
            
            $depart2 = "";
            $depart3 = "";
            $depart4 = "";
            $depart5 = "";
            $depart6 = "";
            $depart7 = "";
            if($joint->s2department != "")
            {
                $depart2 = $departments[$joint->s2department-1];
            }
            if($joint->s3department != "")
            {
                $depart3 = $departments[$joint->s3department-1];
            }
            if($joint->s4department != "")
            {
                $depart4 = $departments[$joint->s4department-1];
            }
            if($joint->s5department != "")
            {
                $depart5 = $departments[$joint->s5department-1];
            }
            if($joint->s5department != "")
            {
                $depart6 = $departments[$joint->s6department-1];
            }
            if($joint->s5department != "")
            {
                $depart7 = $departments[$joint->s7department-1];
            }
        

            echo "<tr id='$joint->jointID' $addClass>
            <td><i>{$departments[$joint->s1department-1]}</i> <br> $joint->s1title ".SbjType($joint->s1type)."</td>
            
            <td>
            <a href='#' onclick='deleteJoint($joint->jointID);' class='deleteJoint'><img id='edit-delete-icon' src='".plugins_url('icons/delete_icon.png', __FILE__)."'/> ". __("Sil","UniTimetable") ."</a>
            </td>
            
            <td><i>$depart2</i> <br> $joint->s2title ".SbjType($joint->s2type)."</td>
            <td><input type='checkbox' id='s2ok' name='s2ok' value='$joint->s2ID' onchange='verifyJoint($joint->jointID,$joint->s2ID,this.checked)' $s2okdisabled $s2okchecked></td>

            <td><i>$depart3</i> <br> $joint->s3title ".SbjType($joint->s3type)."</td>
            <td><input type='checkbox' id='s3ok' name='s3ok' value='$joint->s3ID' onchange='verifyJoint($joint->jointID,$joint->s3ID,this.checked)' $s3okdisabled $s3okchecked></td>
            
            <td><i>$depart4</i> <br> $joint->s4title ".SbjType($joint->s4type)."</td>
            <td><input type='checkbox' id='s4ok' name='s4ok' value='$joint->s4ID' onchange='verifyJoint($joint->jointID,$joint->s4ID,this.checked)' $s4okdisabled $s4okchecked></td>
            
            <td><i>$depart5</i> <br> $joint->s5title ".SbjType($joint->s5type)."</td>
            <td><input type='checkbox' id='s5ok' name='s5ok' value='$joint->s5ID' onchange='verifyJoint($joint->jointID,$joint->s5ID,this.checked)' $s5okdisabled $s5okchecked></td>


            <td><i>$depart6</i> <br> $joint->s6title ".SbjType($joint->s6type)."</td>
            <td><input type='checkbox' id='s6ok' name='s6ok' value='$joint->s6ID' onchange='verifyJoint($joint->jointID,$joint->s6ID,this.checked)' $s6okdisabled $s6okchecked></td>


            <td><i>$depart7</i> <br> $joint->s7title ".SbjType($joint->s7type)."</td>
            <td><input type='checkbox' id='s7ok' name='s7ok' value='$joint->s7ID' onchange='verifyJoint($joint->jointID,$joint->s7ID,this.checked)' $s7okdisabled $s7okchecked></td>


            </tr>";

            //<a href='#' onclick=\"editJoint($joint->jointID,'$joint->joint');\" class='editJoint'><img id='edit-delete-icon' src='".plugins_url('icons/edit_icon.png', __FILE__)."'/> ". __("Güncelle","UniTimetable") ."</a>
        }
    ?>
            </tbody>
        </table><?php
        die();
}

//ajax response insert-update joint
add_action('wp_ajax_utt_insert_update_joint','utt_insert_update_joint');
function utt_insert_update_joint(){
    global $wpdb;
    
    $userid = get_current_user_id();

    
//     $wpdb->suppress_errors = false;
// 	$wpdb->show_errors = true;

    //data
    $s1id = $_GET['s1'];
    $s2id = $_GET['s2'];
    $s3id = $_GET['s3'];
    $s4id = $_GET['s4'];
    $s5id = $_GET['s5'];
    $s6id = $_GET['s6'];
    $s7id = $_GET['s7'];

    if ($s1id <= 0 || $s2id <=0)
    {
        echo 'En az iki ders birleştirmelisiniz.';
        die();
    }
    
    
    $jointsTable=$wpdb->prefix."utt_joint";
    
    
    $getthejoint_q1 = "";
    $getthejoint_q2 = "";
    $getthejoint_q3 = "";
    $getthejoint_q4 = "";
    $getthejoint_q5 = "";
    $getthejoint_q6 = "";
    $getthejoint_q7 = "";
    
    
    $getthejoint_q1 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $jointsTable WHERE s1ID=%d OR s2ID=%d OR s3ID=%d OR s4ID=%d OR s5ID=%d OR s6ID=%d OR s7ID=%d;",$s1id,$s1id,$s1id,$s1id,$s1id,$s1id,$s1id));
    $getthejoint_q2 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $jointsTable WHERE s1ID=%d OR s2ID=%d OR s3ID=%d OR s4ID=%d OR s5ID=%d OR s6ID=%d OR s7ID=%d;",$s2id,$s2id,$s2id,$s2id,$s2id,$s2id,$s2id));
    if($s3id>0)
    $getthejoint_q3 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $jointsTable WHERE s1ID=%d OR s2ID=%d OR s3ID=%d OR s4ID=%d OR s5ID=%d OR s6ID=%d OR s7ID=%d;",$s3id,$s3id,$s3id,$s3id,$s3id,$s3id,$s3id));
    if($s4id>0)
    $getthejoint_q4 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $jointsTable WHERE s1ID=%d OR s2ID=%d OR s3ID=%d OR s4ID=%d OR s5ID=%d OR s6ID=%d OR s7ID=%d;",$s4id,$s4id,$s4id,$s4id,$s4id,$s4id,$s4id));
    if($s5id>0)
    $getthejoint_q5 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $jointsTable WHERE s1ID=%d OR s2ID=%d OR s3ID=%d OR s4ID=%d OR s5ID=%d OR s6ID=%d OR s7ID=%d;",$s5id,$s5id,$s5id,$s5id,$s5id,$s5id,$s5id));
    if($s6id>0)
    $getthejoint_q6 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $jointsTable WHERE s1ID=%d OR s2ID=%d OR s3ID=%d OR s4ID=%d OR s5ID=%d OR s6ID=%d OR s7ID=%d;",$s6id,$s6id,$s6id,$s6id,$s6id,$s6id,$s6id));
    if($s7id>0)
    $getthejoint_q7 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $jointsTable WHERE s1ID=%d OR s2ID=%d OR s3ID=%d OR s4ID=%d OR s5ID=%d OR s6ID=%d OR s7ID=%d;",$s7id,$s7id,$s7id,$s7id,$s7id,$s7id,$s7id));
    // echo "--".$s5id."--";
    // var_dump($getthejoint_q1);
    // echo "{".$wpdb->print_error()."}";
    // die();
    if($getthejoint_q1 != "" || $getthejoint_q2 != "" || $getthejoint_q3 != "" || $getthejoint_q4 != "" || $getthejoint_q5 != "" || $getthejoint_q6 != "" || $getthejoint_q7 != "")
    {
        echo 'Birleştirmeye çalıştığınız derslerden biri daha önce eklenmiş!';
        die();
    }

    $jointid=$_GET['joint_id'];
    //is insert
    if($jointid==0){
        $safeSql = $wpdb->prepare("INSERT INTO $jointsTable (userid,s1ID,s2ID,s2ok,s3ID,s3ok,s4ID,s4ok,s5ID,s5ok,s6ID,s6ok,s7ID,s7ok) VALUES (%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d)",$userid,$s1id,$s2id,0,$s3id,0,$s4id,0,$s5id,0,$s6id,0,$s7id,0);
        $success = $wpdb->query($safeSql);
        if($success == 1){
            //success
            echo 1;
        }else{
            //fail
            //echo "{".$wpdb->print_error()."}";
            echo 0;
        }
    //is edit
    }/*else{
        $safeSql = $wpdb->prepare("UPDATE $jointsTable SET joint=%s WHERE jointID=%d ",$joint,$jointid);
        $success = $wpdb->query($safeSql);
        if($success == 1){
            //success
            echo 1;
        }else{
            //fail
            echo 0;
        }
    }*/
    die();
}

//ajax response delete joint
add_action('wp_ajax_utt_delete_joint', 'utt_delete_joint');
function utt_delete_joint()
{
    global $wpdb;
    $userid = get_current_user_id();

    $jointsTable=$wpdb->prefix."utt_joint";
    $safeSql = $wpdb->prepare("DELETE FROM $jointsTable WHERE jointID=%d AND userid=%d",$_GET['joint_id'],$userid);
    $success = $wpdb->query($safeSql);
    //if success is 1 then delete succeeded
    echo $success;
    die();
}


//ajax response delete joint
add_action('wp_ajax_utt_setok_joint', 'utt_setok_joint');
function utt_setok_joint()
{
    global $wpdb;
    global $departments, $departmentusers;

    $userid = get_current_user_id();

    
    $wpdb->suppress_errors = false;
	$wpdb->show_errors = true;
    
    $jointsTable=$wpdb->prefix."utt_joint";
    $subjectsTable=$wpdb->prefix."utt_subjects";


    $jointID = intval($_GET['joint_id']);
    $subjectID = intval($_GET['subject_id']);
    $val = intval($_GET['val']);

    //echo "".$userid." nolu kullanıcı olarak yapmak istediğiniz: ".$jointID." | ".$subjectID." | ".$val;
    $success = 0;

    $safeSqlJoints = $wpdb->prepare( "SELECT J.jointID jointID, J.s1ID s1ID, J.s2ID s2ID, J.s3ID s3ID, J.s4ID s4ID, J.s5ID s5ID, J.s6ID s6ID, J.s7ID s7ID, J.s2ok s2ok, J.s3ok s3ok, J.s4ok s4ok, J.s5ok s5ok, J.s6ok s6ok, J.s7ok s7ok, S1.title s1title, S2.title s2title, S3.title s3title, S4.title s4title, S5.title s5title, S6.title s6title, S7.title s7title, S1.semester s1department, S2.semester s2department, S3.semester s3department, S4.semester s4department, S5.semester s5department, S6.semester s6department, S7.semester s7department FROM $jointsTable J LEFT JOIN $subjectsTable S1 ON J.s1ID=S1.subjectID LEFT JOIN $subjectsTable S2 ON J.s2ID=S2.subjectID LEFT JOIN $subjectsTable S3 ON J.s3ID=S3.subjectID LEFT JOIN $subjectsTable S4 ON J.s4ID=S4.subjectID LEFT JOIN $subjectsTable S5 ON J.s5ID=S5.subjectID  LEFT JOIN $subjectsTable S6 ON J.s6ID=S6.subjectID  LEFT JOIN $subjectsTable S7 ON J.s7ID=S7.subjectID WHERE J.jointID=%d;",$jointID);
    $joints = $wpdb->get_results($safeSqlJoints); 
    foreach($joints as $joint)
    {
        //echo "#".$joint->jointID." | ".$joint->s2ID." ?= ".$subjectID."#";
        if($joint->s2ID == $subjectID && (IsAdmin() || $userid == $departmentusers[$joint->s2department-1]))
        {
            $safeSql = $wpdb->prepare("UPDATE $jointsTable SET s2ok=%d WHERE jointID=%d",$val,$jointID);
            $success = $wpdb->query($safeSql); 
        }
        else if($joint->s3ID == $subjectID && (IsAdmin() || $userid == $departmentusers[$joint->s3department-1]))
        {
            $safeSql = $wpdb->prepare("UPDATE $jointsTable SET s3ok=%d WHERE jointID=%d",$val,$jointID);
            $success = $wpdb->query($safeSql); 
        }
        else if($joint->s4ID == $subjectID && (IsAdmin() || $userid == $departmentusers[$joint->s5department-1]))
        {
            $safeSql = $wpdb->prepare("UPDATE $jointsTable SET s4ok=%d WHERE jointID=%d",$val,$jointID);
            $success = $wpdb->query($safeSql); 
        }
        else if($joint->s5ID == $subjectID && (IsAdmin() || $userid == $departmentusers[$joint->s6department-1]))
        {
            $safeSql = $wpdb->prepare("UPDATE $jointsTable SET s5ok=%d WHERE jointID=%d",$val,$jointID);
            $success = $wpdb->query($safeSql); 
        }
        else if($joint->s6ID == $subjectID && (IsAdmin() || $userid == $departmentusers[$joint->s7department-1]))
        {
            $safeSql = $wpdb->prepare("UPDATE $jointsTable SET s6ok=%d WHERE jointID=%d",$val,$jointID);
            $success = $wpdb->query($safeSql); 
        }
        else if($joint->s7ID == $subjectID && (IsAdmin() || $userid == $departmentusers[$joint->s7department-1]))
        {
            $safeSql = $wpdb->prepare("UPDATE $jointsTable SET s7ok=%d WHERE jointID=%d",$val,$jointID);
            $success = $wpdb->query($safeSql); 
        }
    }

    
    //echo "{".$wpdb->print_error()."}";

    if($success == 0)
    {
        echo "Bu işlemi yapma yetkiniz bulunmamaktadır.";
    }
    else
    {
        if($val==1)
        echo "Bu birleşmeyi onayladınız.";
        else
        echo "Bu birleşmenin onayını kaldırdınız.";
    }
    /*
    $jointsTable=$wpdb->prefix."utt_joint";
    $safeSql = $wpdb->prepare("DELETE FROM $jointsTable WHERE jointID=%d AND userid=%d",$_GET['joint_id'],$userid);
    $success = $wpdb->query($safeSql);*/
    //if success is 1 then delete succeeded
    //echo $success;
    die();
}

?>