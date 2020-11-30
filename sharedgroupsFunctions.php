<?php
//include js
function utt_sharedgroup_scripts(){
    //load sharedgroupScripts.js file
    wp_enqueue_script( 'sharedgroupScripts',  plugins_url('js/sharedgroupScripts.js', __FILE__) );
    //localize sharedgroup strings
    wp_localize_script( 'sharedgroupScripts', 'sharedgroupStrings', array(
        'deleteForbidden' => __( 'Formu doldururken silmek yasaktır!', 'UniTimetable' ),
        'deleteSharedgroup' => __( 'Bu şube paylaşımını silmek istiyor musunuz?', 'UniTimetable' ),
        'sharedgroupDeleted' => __( 'Şube paylaşımı başarıyla silindi!', 'UniTimetable' ),
        'sharedgroupNotDeleted' => __( 'Şube paylaşımı silinemedi. Paylaşılan şubelerin eklenen dersler ile bağlantılı olup olmadığını kontrol edin.', 'UniTimetable' ),
        'editForbidden' => __( 'Lütfen bir şube paylaşım kuralı seçin!', 'UniTimetable' ),
        'editSharedgroup' => __( 'Şube paylaşımını güncelle', 'UniTimetable' ),
        'cancel' => __( 'İptal', 'UniTimetable' ),
        'yearVal' => __( 'Yıl alanı limitlerin dışında.', 'UniTimetable' ),
        'semesterVal' => __( 'Konu düzenlenemedi. Konunun var olup olmadığı kontrol kontrolü edin', 'UniTimetable' ),
        'insertSharedgroup' => __( 'Şubeleri paylaştır', 'UniTimetable' ),
        'reset' => __( 'Sıfırla', 'UniTimetable' ),
        'failAdd' => __( 'Şube paylaşımı eklenemedi. Şube paylaşımının zaten var olup olmadığını kontrol edin.', 'UniTimetable' ),
        'successAdd' => __( 'Şube paylaştırıldı!', 'UniTimetable' ),
        'failEdit' => __( 'Şube paylaşımı güncellenemedi. Şube paylaşımının zaten var olup olmadığını kontrol edin.', 'UniTimetable' ),
        'successEdit' => __( 'Şube paylaşımı başarıyla güncellendi!', 'UniTimetable' ),
    ));
}

//sharedgroups page
function utt_create_sharedgroups_page()
{
    global $wpdb;
    
	global $departments;
	
    $lecturesTable = $wpdb->prefix."utt_lectures";
    $groupsTable = $wpdb->prefix."utt_groups";
    $subjectsTable = $wpdb->prefix."utt_subjects";


    $wpdb->suppress_errors = false;
	$wpdb->show_errors = true;
	

    //sharedgroup form
    ?>
    <div class="wrap">
        <h2 id="sharedgroupTitle"> <?php _e("Şube paylaştır","UniTimetable"); ?> </h2>
        <form action="" name="sharedgroupForm" method="post">
            <input type="hidden" name="sgID" id="sgID" value=0 />
            
            <br/><?php _e("Ana Ders:","UniTimetable"); ?><br/>


            <select name="groupID" id="groupID" class="dirty">
            <option value='0'><?php _e("- seç -","UniTimetable"); ?></option>
                <?php
                    $groups = $wpdb->get_results("SELECT G.groupID, G.groupName, S.subjectID, S.title, S.semester, S.type FROM $subjectsTable S INNER JOIN $groupsTable G ON G.subjectID = S.subjectID;");
                    foreach($groups as $group)
                    {
                        echo "<option value='$group->groupID'>".$departments[$group->semester-1]." » $group->title ($group->type) » $group->groupName</option>";
                    }
                ?>
            </select>
            
            
            <br/><?php _e("Diğer Bölümdeki Karşılığı:","UniTimetable"); ?><br/>
            
            <select name="subjectID" id="subjectID" class="dirty">
            <option value='0'><?php _e("- seç -","UniTimetable"); ?></option>
                <?php
                    $subjects = $wpdb->get_results("SELECT S.subjectID, S.title, S.semester, S.type FROM $subjectsTable S;");
                    foreach($subjects as $subject)
                    {
                        echo "<option value='$subject->subjectID'>".$departments[$subject->semester-1]." » $subject->title ($subject->type)</option>";
                    }
                    
	                echo "{".$wpdb->print_error()."}";
                ?>
            </select>
            


            <br/>
            
            
            <div id="secondaryButtonContainer">
                <input type="submit" value="<?php _e("Paylaştır","UniTimetable"); ?>" id="insert-updateSharedgroup" class="button-primary"/>
                <a href='#' class='button-secondary' id="clearSharedgroupForm"><?php _e("Sıfırla","UniTimetable"); ?></a>
            </div>
        </form>
    <!-- place to show messages -->
    <div id="messages"></div>
    <!-- place to show results table -->
    <div id="sharedgroupsResults">
        <?php utt_view_sharedgroups(); ?>
    </div>
    </div>
    <?php
}
//show registered sharedgroups
add_action('wp_ajax_utt_view_sharedgroups', 'utt_view_sharedgroups');
function utt_view_sharedgroups(){
    global $wpdb;
    global $departments, $departmentusers;

    
    $userid = get_current_user_id();



    $groupsTable=$wpdb->prefix."utt_groups";
    $sharedgroupsTable=$wpdb->prefix."utt_sharedgroups";
    $subjectsTable=$wpdb->prefix."utt_subjects";
    
    //$sharedgroups = $wpdb->get_results( "SELECT SG.*, S.*, G.* FROM $sharedgroupsTable SG INNER JOIN $groupsTable G ON G.groupID = SG.groupID INNER JOIN $subjectsTable S ON S.subjectID = SG.subjectID ORDER BY SG.sgID DESC");
    $sharedgroups = $wpdb->get_results( "SELECT SG1.sgID sgID, S.semester semester1, S.title title1, G.groupName, T2.semester semester2, T2.title title2 FROM $sharedgroupsTable SG1 INNER JOIN $groupsTable G ON G.groupID = SG1.groupID INNER JOIN $subjectsTable S ON S.subjectID = G.subjectID
INNER JOIN
(SELECT SG2.sgID sgID, S2.semester, S2.title FROM $sharedgroupsTable SG2 INNER JOIN $subjectsTable S2 ON S2.subjectID = SG2.subjectID) T2 ON SG1.sgID=T2.sgID");
    
    
    /*

    */

    ?>
    <!-- show table with sharedgroups -->
    <table class="widefat bold-th">
            <thead>
                <tr>
                    <th><?php _e("Ana Ders ve Şube","UniTimetable"); ?></th>
                    <th><?php _e("Eylemler","UniTimetable"); ?></th>
                    
                    <th><?php _e("Paylaşıldığı Bölüm ve Ders","UniTimetable"); ?></th>
                    
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><?php _e("Ana Ders ve Şube","UniTimetable"); ?></th>
                    <th><?php _e("Eylemler","UniTimetable"); ?></th>
                    
                    <th><?php _e("Paylaşıldığı Bölüm ve Ders","UniTimetable"); ?></th>
                    
                </tr>
            </tfoot>
            <tbody>
    <?php
        //show grey and white records in order to be more recognizable
        $bgcolor = 1;
        foreach($sharedgroups as $sharedgroup)
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
            

        

            echo "<tr id='$sharedgroup->sgID' $addClass>
            <td><i>{$departments[$sharedgroup->semester1-1]}</i> <br> $sharedgroup->title1 » $sharedgroup->groupName</td>
            
            <td>
            <a href='#' onclick='deleteSharedgroup($sharedgroup->sgID);' class='deleteSharedgroup'><img id='edit-delete-icon' src='".plugins_url('icons/delete_icon.png', __FILE__)."'/> ". __("Sil","UniTimetable") ."</a>
            </td>
            
            <td><i>{$departments[$sharedgroup->semester2-1]}</i> <br> $sharedgroup->title2</td>

            </tr>";

            //<a href='#' onclick=\"editSharedgroup($sharedgroup->sgID,'$sharedgroup->sharedgroup');\" class='editSharedgroup'><img id='edit-delete-icon' src='".plugins_url('icons/edit_icon.png', __FILE__)."'/> ". __("Güncelle","UniTimetable") ."</a>
        }
    ?>
            </tbody>
        </table><?php
        die();
}

//ajax response insert-update sharedgroup
add_action('wp_ajax_utt_insert_update_sharedgroup','utt_insert_update_sharedgroup');
function utt_insert_update_sharedgroup(){
    global $wpdb;
    
    $userid = get_current_user_id();

    /*
    $wpdb->suppress_errors = false;
	$wpdb->show_errors = true;
*/
    //data
    $groupID = $_GET['groupID'];
    $subjectID = $_GET['subjectID'];

    if ($groupID <= 0 || $subjectID <=0)
    {
        echo 'Bir dersin şubesini ve bu dersin verileceği diğer bölümdeki karşılığını seçmelisiniz.';
        die();
    }

    $sgID=$_GET['sharedgroup_id'];
    $sharedgroupsTable=$wpdb->prefix."utt_sharedgroups";
    //is insert
    if($sgID==0){
        $safeSql = $wpdb->prepare("INSERT INTO $sharedgroupsTable (userid,groupID,subjectID) VALUES (%d,%d,%d)",$userid,$groupID,$subjectID);
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
        $safeSql = $wpdb->prepare("UPDATE $sharedgroupsTable SET sharedgroup=%s WHERE sgID=%d ",$sharedgroup,$sgID);
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

//ajax response delete sharedgroup
add_action('wp_ajax_utt_delete_sharedgroup', 'utt_delete_sharedgroup');
function utt_delete_sharedgroup()
{
    global $wpdb;
    $userid = get_current_user_id();

    $sharedgroupsTable=$wpdb->prefix."utt_sharedgroups";
    $safeSql = $wpdb->prepare("DELETE FROM $sharedgroupsTable WHERE sgID=%d AND userid=%d",$_GET['sharedgroup_id'],$userid);
    $success = $wpdb->query($safeSql);
    //if success is 1 then delete succeeded
    echo $success;
    die();
}



// //ajax response delete sharedgroup
// add_action('wp_ajax_utt_setok_sharedgroup', 'utt_setok_sharedgroup');
// function utt_setok_sharedgroup()
// {
//     global $wpdb;
//     global $departments, $departmentusers;

//     $userid = get_current_user_id();

    
//     $wpdb->suppress_errors = false;
// 	$wpdb->show_errors = true;
    
//     $sharedgroupsTable=$wpdb->prefix."utt_sharedgroups";
//     $subjectsTable=$wpdb->prefix."utt_subjects";


//     $sgID = intval($_GET['sharedgroup_id']);
//     $subjectID = intval($_GET['subject_id']);
//     $val = intval($_GET['val']);

//     //echo "".$userid." nolu kullanıcı olarak yapmak istediğiniz: ".$sgID." | ".$subjectID." | ".$val;
//     $success = 0;

//     $safeSqlSharedgroups = $wpdb->prepare( "SELECT SG.sgID sgID, SH.groupID groupID, SH.subjectID subjectID, S1.title groupIDtitle, S.title subjectIDtitle, S.semester groupIDdepartment FROM $sharedgroupsTable SG LEFT JOIN $subjectsTable S ON SG.groupID=S.subjectID WHERE SG.sgID=%d;",$sgID);
//     $sharedgroups = $wpdb->get_results($safeSqlSharedgroups); 
//     foreach($sharedgroups as $sharedgroup)
//     {
//         //echo "#".$sharedgroup->sgID." | ".$sharedgroup->subjectIDID." ?= ".$subjectID."#";
//         if($sharedgroup->subjectIDID == $subjectID && (IsAdmin() || $userid == $departmentusers[$sharedgroup->subjectIDsemester]))
//         {
//             $safeSql = $wpdb->prepare("UPDATE $sharedgroupsTable SET subjectIDok=%d WHERE sgID=%d",$val,$sgID);
//             $success = $wpdb->query($safeSql); 
//         }
//         else if($sharedgroup->s3ID == $subjectID && (IsAdmin() || $userid == $departmentusers[$sharedgroup->s3semester]))
//         {
//             $safeSql = $wpdb->prepare("UPDATE $sharedgroupsTable SET s3ok=%d WHERE sgID=%d",$val,$sgID);
//             $success = $wpdb->query($safeSql); 
//         }
//         else if($sharedgroup->s4ID == $subjectID && (IsAdmin() || $userid == $departmentusers[$sharedgroup->s4semester]))
//         {
//             $safeSql = $wpdb->prepare("UPDATE $sharedgroupsTable SET s4ok=%d WHERE sgID=%d",$val,$sgID);
//             $success = $wpdb->query($safeSql); 
//         }
//         else if($sharedgroup->s5ID == $subjectID && (IsAdmin() || $userid == $departmentusers[$sharedgroup->s5semester]))
//         {
//             $safeSql = $wpdb->prepare("UPDATE $sharedgroupsTable SET s5ok=%d WHERE sgID=%d",$val,$sgID);
//             $success = $wpdb->query($safeSql); 
//         }
//     }

    
//     //echo "{".$wpdb->print_error()."}";

//     if($success == 0)
//     {
//         echo "Bu işlemi yapma yetkiniz bulunmamaktadır.";
//     }
//     else
//     {
//         if($val==1)
//         echo "Bu şube paylaşımını onayladınız.";
//         else
//         echo "Bu şube paylaşımının onayını kaldırdınız.";
//     }
//     /*
//     $sharedgroupsTable=$wpdb->prefix."utt_sharedgroups";
//     $safeSql = $wpdb->prepare("DELETE FROM $sharedgroupsTable WHERE sgID=%d AND userid=%d",$_GET['sharedgroup_id'],$userid);
//     $success = $wpdb->query($safeSql);*/
//     //if success is 1 then delete succeeded
//     //echo $success;
//     die();
// }

?>