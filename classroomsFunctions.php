<?php
//include js
function utt_classroom_scripts(){
    //load classroomScripts
    wp_enqueue_script( 'classroomScripts',  plugins_url('js/classroomScripts.js?v=1000', __FILE__) );
    //localize classroomScripts
    wp_localize_script( 'classroomScripts', 'classroomStrings', array(
        'deleteForbidden' => __( 'Formu doldururken silmek yasaktır!', 'UniTimetable' ),
        'deleteClassroom' => __( 'Bu şubeyi silmek istediğinize emin misiniz?', 'UniTimetable' ),
        'classroomDeleted' => __( 'Sınıf başarıyla silindi!', 'UniTimetable' ),
        'classroomNotDeleted' => __( 'Sınıf silinemedi. Sınıfın bir Ders veya Etkinlik tarafından kullanılıp kullanılmadığını kontrol edin.', 'UniTimetable' ),
        'editForbidden' => __( 'Form doldurulurken düzenleme yapmak yasaktır!', 'UniTimetable' ),
        'editClassroom' => __( 'Sınıf güncelle', 'UniTimetable' ),
        'cancel' => __( 'İptal', 'UniTimetable' ),
        'nameVal' => __( 'Sınıf adı zorunludur. Lütfen özel karakter kullanmaktan kaçının.', 'UniTimetable' ),
        'typeVal' => __( 'Lütfen sınıf tipi seçiniz.', 'UniTimetable' ),
        'insertClassroom' => __( 'Sınıf Ekle.', 'UniTimetable' ),
        'reset' => __( 'Sıfırla', 'UniTimetable' ),
        'failAdd' => __( 'Sınıf eklenemedi. Sınıfın var olup olmadığını kontrol edin.', 'UniTimetable' ),
        'successAdd' => __( 'Sınıf başarıyla eklendi!', 'UniTimetable' ),
        'failEdit' => __( 'Sınıf düzenlenemedi. Sınıfın var olup olmadığını kontrol edin.', 'UniTimetable' ),
        'successEdit' => __( 'Sınıf başarıyla güncellendi!', 'UniTimetable' ),
    ));
}
//classroom page
function utt_create_classrooms_page(){
    //classroom form
    ?>
    <div class="wrap">
        <h2 id="classroomTitle"> <?php _e("Sınıf ekle","UniTimetable"); ?> </h2>
        <form action="" name="classroomForm" method="post">
            <input type="hidden" name="classroomID" id="classroomID" value=0 />
			
            <?php _e("Sınıf ismi:","UniTimetable"); ?><br/>
            <input type="text" name="classroomName" id="classroomName" class="dirty" value="" placeholder="<?php _e("","UniTimetable"); ?>"/>
            <br/>
			
            <?php _e("Ders türü:","UniTimetable"); ?><br/>
            <select name="classroomType" id="classroomType" class="dirty">
                <option value="0"><?php _e("- seç -","UniTimetable"); ?></option>
                <option value="Düz"><?php _e("Düz","UniTimetable"); ?></option>
                <option value="Laboratuvar"><?php _e("Laboratuvar","UniTimetable"); ?></option>
                <option value="Amfi"><?php _e("Amfi","UniTimetable"); ?></option>
                <option value="Toplantı Salonu"><?php _e("Toplantı Salonu","UniTimetable"); ?></option>
                <option value="Salon"><?php _e("Salon","UniTimetable"); ?></option>
            </select>
            <br/>
			
						
            <?php _e("Sınıf kapasitesi:","UniTimetable"); ?><br/>
            <input type="text" name="classroomCapacity" id="classroomCapacity" class="dirty" value="" placeholder="<?php _e("","UniTimetable"); ?>"/>
            <br/>
            
            
            
            <?php _e("Aktiflik:","UniTimetable"); ?><br/>
            <select name="classroomType" id="classroomAvailable" class="dirty">
                <option value="1"><?php _e("Aktif","UniTimetable"); ?></option>
                <option value="0"><?php _e("Pasif","UniTimetable"); ?></option>
            </select>
            <br/>
            
            
            
			
            <div id="secondaryButtonContainer">
                <input type="submit" value="<?php _e("Ekle","UniTimetable"); ?>" id="insert-updateClassroom" class="button-primary"/>
                <a href='#' class='button-secondary' id="clearClassroomForm"><?php _e("Sıfırla","UniTimetable"); ?></a>
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
                    <th><?php _e("İsim","UniTimetable"); ?></th>
                    <th><?php _e("Tip","UniTimetable"); ?></th>
                    <th><?php _e("Kapasite","UniTimetable"); ?></th>
                    <th><?php _e("Aktiflik Durumu","UniTimetable"); ?></th>
                    <th><?php _e("Hareketler","UniTimetable"); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th><?php _e("İsim","UniTimetable"); ?></th>
                    <th><?php _e("Tip","UniTimetable"); ?></th>
                    <th><?php _e("Kapasite","UniTimetable"); ?></th>
                    <th><?php _e("Aktiflik Durumu","UniTimetable"); ?></th>
                    <th><?php _e("Hareketler","UniTimetable"); ?></th>
                </tr>
            </tfoot>
            <tbody>
    <?php
        //show grey and white records in order to be more recognizable
        $bgcolor = 1;
        foreach($classrooms as $classroom)
        {
            if($bgcolor == 1){
                $addClass = "class='grey'";
                $bgcolor = 2;
            }else{
                $addClass = "class='white'";
                $bgcolor = 1;
            }
            /*if($classroom->type == "Lecture"){
                $type = __("Teori","UniTimetable");
            }else{
                $type = __("Laboratuvar","UniTimetable");
            }*/
            
			$type = __("".$classroom->type,"UniTimetable");
			
			$classAvailableStatue = "-";
			if($classroom->is_available == "1")
			{
                $classAvailableStatue = __("<b>Aktif</b>","UniTimetable");
            }
            else
            {
                $classAvailableStatue = __("<i>Pasif</i>","UniTimetable");
            }
			
            //a record
            echo "<tr id='$classroom->classroomID' $addClass>
			<td>$classroom->classroomID</td>
			<td>$classroom->name</td>
			<td>$type</td>
			<td>$classroom->capacity</td>
			<td>$classAvailableStatue</td>
            <td><a href='#' onclick='deleteClassroom($classroom->classroomID);' class='deleteClassroom'><img id='edit-delete-icon' src='".plugins_url('icons/delete_icon.png', __FILE__)."'/> ".__("Sil","UniTimetable")."</a>&nbsp;
            <a href='#' onclick=\"editClassroom($classroom->classroomID,'$classroom->name','$classroom->type',$classroom->capacity,$classroom->is_available);\" class='editClassroom'><img id='edit-delete-icon' src='".plugins_url('icons/edit_icon.png', __FILE__)."'/> ".__("Güncelle","UniTimetable")."</a></td></tr>";
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
    $classroomCapacity=$_GET['classroom_capacity'];
    $classroomAvailable=$_GET['classroom_available'];
    //define classrooms table
    $classroomsTable=$wpdb->prefix."utt_classrooms";
    //if classroomID is 0, so it is insert
    if($classroomID==0){
        $safeSql = $wpdb->prepare("INSERT INTO $classroomsTable (name, type, capacity) VALUES (%s,%s,%d)",$classroomName,$classroomType,$classroomCapacity);
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
        $safeSql = $wpdb->prepare("UPDATE $classroomsTable SET name=%s, type=%s, capacity=%d, is_available=%d WHERE classroomID=%d ",$classroomName,$classroomType,$classroomCapacity,$classroomAvailable,$classroomID);
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