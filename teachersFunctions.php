<?php
//include js
function utt_teacher_scripts(){
    //include teacher scripts
    wp_enqueue_script( 'teacherScripts',  plugins_url('js/teacherScripts.js', __FILE__) );
    //localize teacher scripts
    wp_localize_script( 'teacherScripts', 'teacherStrings', array(
        'deleteForbidden' => __( 'Formu doldururken silmek yasaktır', 'UniTimetable' ),
        'deleteRecord' => __( 'Bu kaydı silmek istediğinize emin misiniz?', 'UniTimetable' ),
        'teacherDeleted' => __( 'Öğretim Üyesi başarıyla silindi!', 'UniTimetable' ),
        'teacherNotDeleted' => __( 'Öğretim Üyesi silinemedi. Öğretim Üyesinin bir ders ile bağlantılı olup olmadığını kontrol edin.', 'UniTimetable' ),
        'editForbidden' => __( 'Formu doldururken düzenleme yapmak yasaktır!', 'UniTimetable' ),
        'editTeacher' => __( 'Öğretim Üyesini Düzenle', 'UniTimetable' ),
        'cancel' => __( 'İptal', 'UniTimetable' ),
        'surnameVal' => __( 'Soyadı alanı zorunludur. Lütfen özel karakterler kullanmaktan kaçının.', 'UniTimetable' ),
        'nameVal' => __( 'Lütfen İsim alanında özel karakterler kullanmaktan kaçınının', 'UniTimetable' ),
        'insertTeacher' => __( 'Öğretim Üyesi Ekle', 'UniTimetable' ),
        'reset' => __( 'Sıfırla', 'UniTimetable' ),
        'failAdd' => __( 'Öğretim Üyesi eklenemedi. Öğretim Üyesinin zaten var olup olmadığını kontrol edin.', 'UniTimetable' ),
        'successAdd' => __( 'Öğretim Üyesi başarıyla eklendi!', 'UniTimetable' ),
        'failEdit' => __( 'Öğretim Üyesi düzenlenemedi. Öğretim Üyesinin zaten var olup olmadığını kontrol edin.', 'UniTimetable' ),
        'successEdit' => __( 'Öğretim Üyesi başarıyla düzenlendi!', 'UniTimetable' ),
    ));
}

//teachers page
function utt_create_teachers_page(){
    //teachers form
?>
<div class="wrap" >
    <h2 id="teacherTitle"><?php _e('Öğretim Üyesi Ekle','UniTimetable'); ?></h2>
    <form action="" name="teacherForm" method="post">
        <input type="hidden" name="teacherid" id="teacherid" value=0 />
		
		
        <?php _e("Ad:","UniTimetable"); ?><br/>
        <input type="text" name="firstname" id="firstname" class="dirty"/>
        <br/>
		
        <?php _e("Soyad:","UniTimetable"); ?><br/>
        <input type="text" name="lastname" id="lastname" class="dirty" required placeholder="<?php _e("","UniTimetable"); ?>"/>
        <br/>
		
        <div id="secondaryButtonContainer">
        <input type="submit" value="<?php _e("Ekle","UniTimetable"); ?>" id="insert-updateTeacher" class="button-primary"/>
        <a href='#' class='button-secondary' id="clearTeacherForm"><?php _e("Sıfırla","UniTimetable"); ?></a>
        </div>
    </form>
    <!-- place to view messages -->
    <div id="messages"></div>
    <!-- place to view table with registered teachers -->
    <div id="teachersResults">
        <?php utt_view_teachers(); ?>
    </div>
</div>

<?php
}

add_action('wp_ajax_utt_view_teachers', 'utt_view_teachers');
function utt_view_teachers(){
    global $wpdb;
    $teachersTable=$wpdb->prefix."utt_teachers";
        
    //show registered teachers
    $teachers = $wpdb->get_results("SELECT * FROM $teachersTable ORDER BY surname");
    ?>
        <!-- table with registered teachers -->
        <table class="widefat bold-th">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?php _e("Adı","UniTimetable"); ?></th>
                    <th><?php _e("Soyadı","UniTimetable"); ?></th>
                    <th><?php _e("Eylemler","UniTimetable"); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th><?php _e("Adı","UniTimetable"); ?></th>
                    <th><?php _e("Soyadı","UniTimetable"); ?></th>
                    <th><?php _e("Eylemler","UniTimetable"); ?></th>
                </tr>
            </tfoot>
            <tbody>
        <?php
        //show grey and white records in order to be more recognizable
        $bgcolor = 1;
        foreach($teachers as $teacher){
            if($bgcolor == 1){
                $addClass = "class='grey'";
                $bgcolor = 2;
            }else{
                $addClass = "class='white'";
                $bgcolor = 1;
            }
            //a record
            echo "<tr id='$teacher->teacherID' $addClass>
			<td>$teacher->teacherID</td>
			<td>$teacher->name</td>
			<td>$teacher->surname</td>
			<td><a href='#' onclick='deleteTeacher($teacher->teacherID);' class='deleteTeacher'><img id='edit-delete-icon' src='".plugins_url('icons/delete_icon.png', __FILE__)."'/> ".__("Sil","UniTimetable")."</a>&nbsp; <a href='#' onclick=\"editTeacher($teacher->teacherID, '$teacher->surname', '$teacher->name');\" class='editTeacher'><img id='edit-delete-icon' src='".plugins_url('icons/edit_icon.png', __FILE__)."'/> ".__("Düzenle","UniTimetable")."</a></td></tr>";
        }
        
        ?>
            </tbody>
        </table>
        <?php
        die();
}

//ajax response delete teacher
add_action('wp_ajax_utt_delete_teacher', 'utt_delete_teacher');
function utt_delete_teacher(){
    global $wpdb;
    $teachersTable=$wpdb->prefix."utt_teachers";
    $safeSql = $wpdb->prepare("DELETE FROM $teachersTable WHERE teacherID= %d ", $_GET['teacher_id']);
    $success = $wpdb->query($safeSql);
    //if success is 1, delete succeeded
    echo $success;
    die();
}

//ajax response insert-update teacher
add_action('wp_ajax_utt_insert_update_teacher','utt_insert_update_teacher');
function utt_insert_update_teacher(){
    global $wpdb;
    //data
    $firstname=$_GET['teacher_name'];
    $lastname=$_GET['teacher_surname'];
    $teacherid=$_GET['teacher_id'];
    $teachersTable=$wpdb->prefix."utt_teachers";
    //insert
    if($teacherid==0){
        $safeSql = $wpdb->prepare("INSERT INTO $teachersTable (name, surname) VALUES (%s,%s)",$firstname,$lastname);
        $success = $wpdb->query($safeSql);
        if($success == 1){
            //success
            echo 1;
        }else{
            //fail
            echo 0; 
        }
    //edit
    }else{
        $safeSql = $wpdb->prepare("UPDATE $teachersTable SET name=%s, surname=%s WHERE teacherID=%d; ",$firstname,$lastname,$teacherid);
        $success = $wpdb->query($safeSql);
        if($success == 1){
            //success
            echo 1;
        }else{
            //fail
            echo 0;
        }
    }
die();
}

?>