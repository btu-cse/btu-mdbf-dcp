<?php
//include js
function utt_period_scripts(){
    //load periodScripts.js file
    wp_enqueue_script( 'periodScripts',  plugins_url('js/periodScripts.js', __FILE__) );
    //localize period strings
    wp_localize_script( 'periodScripts', 'periodStrings', array(
        'deleteForbidden' => __( 'Formu doldururken silmek yasaktır!', 'UniTimetable' ),
        'deletePeriod' => __( 'Başlık alanı zorunludur. Lütfen özel karakterler kullanmaktan kaçının?', 'UniTimetable' ),
        'periodDeleted' => __( 'Dönem başarıyla silindi!', 'UniTimetable' ),
        'periodNotDeleted' => __( 'Dönem silinemedi. Dönemin şubeler ile bağlantılı olup olmadığını kontrol edin.', 'UniTimetable' ),
        'editForbidden' => __( 'Lütfen bir dönem seçin!', 'UniTimetable' ),
        'editPeriod' => __( 'Dönemi güncelle', 'UniTimetable' ),
        'cancel' => __( 'İptal', 'UniTimetable' ),
        'yearVal' => __( 'Yıl alanı limitlerin dışında.', 'UniTimetable' ),
        'semesterVal' => __( 'Konu düzenlenemedi. Konunun var olup olmadığı kontrol kontrolü edin', 'UniTimetable' ),
        'insertPeriod' => __( 'Dönem ekle', 'UniTimetable' ),
        'reset' => __( 'Sıfırla', 'UniTimetable' ),
        'failAdd' => __( 'Dönem eklenemedi. Dönemin zaten var olup olmadığını kontrol edin.', 'UniTimetable' ),
        'successAdd' => __( 'Dönem başarıyla eklendi!', 'UniTimetable' ),
        'failEdit' => __( 'Dönem güncellenemedi. Dönemin zaten var olup olmadığını kontrol edin.', 'UniTimetable' ),
        'successEdit' => __( 'Dönem başarıyla güncellendi!', 'UniTimetable' ),
    ));
}
//periods page
function utt_create_periods_page(){
    //period form
    ?>
    <div class="wrap">
        <h2 id="periodTitle"> <?php _e("Dönem ekle","UniTimetable"); ?> </h2>
        <form action="" name="periodForm" method="post">
            <input type="hidden" name="periodid" id="periodid" value=0 />
            
            <?php _e("Dönem:","UniTimetable"); ?><br/>
            <input name="period" id="period" class="dirty" value="<?php echo date("Y"); ?> "/>
            <br/>
            
            
            <div id="secondaryButtonContainer">
                <input type="submit" value="<?php _e("Ekle","UniTimetable"); ?>" id="insert-updatePeriod" class="button-primary"/>
                <a href='#' class='button-secondary' id="clearPeriodForm"><?php _e("Sıfırla","UniTimetable"); ?></a>
            </div>
        </form>
    <!-- place to show messages -->
    <div id="messages"></div>
    <!-- place to show results table -->
    <div id="periodsResults">
        <?php utt_view_periods(); ?>
    </div>
    </div>
    <?php
}
//show registered periods
add_action('wp_ajax_utt_view_periods', 'utt_view_periods');
function utt_view_periods(){
    global $wpdb;
    $periodsTable=$wpdb->prefix."utt_periods";
    $periods = $wpdb->get_results( "SELECT * FROM $periodsTable ORDER BY periodID DESC");
    

    ?>
    <!-- show table with periods -->
    <table class="widefat bold-th">
            <thead>
                <tr>
                    <th><?php _e("Dönem","UniTimetable"); ?></th>
                    <th><?php _e("Eylemler","UniTimetable"); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><?php _e("Dönem","UniTimetable"); ?></th>
                    <th><?php _e("Eylemler","UniTimetable"); ?></th>
                </tr>
            </tfoot>
            <tbody>
    <?php
        //show grey and white records in order to be more recognizable
        $bgcolor = 1;
        foreach($periods as $period)
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
            //a record
            echo "<tr id='$period->periodID' $addClass>
            <td>$period->period</td>
            <td>
            <a href='#' onclick='deletePeriod($period->periodID);' class='deletePeriod'><img id='edit-delete-icon' src='".plugins_url('icons/delete_icon.png', __FILE__)."'/> ". __("Sil","UniTimetable") ."</a>&nbsp;
            <a href='#' onclick=\"editPeriod($period->periodID,'$period->period');\" class='editPeriod'><img id='edit-delete-icon' src='".plugins_url('icons/edit_icon.png', __FILE__)."'/> ". __("Güncelle","UniTimetable") ."</a>
            </td>
            </tr>";
        }
    ?>
            </tbody>
        </table><?php
        die();
}

//ajax response insert-update period
add_action('wp_ajax_utt_insert_update_period','utt_insert_update_period');
function utt_insert_update_period(){
    global $wpdb;
    //data
    $period=$_GET['period'];
    $periodid=$_GET['period_id'];
    $periodsTable=$wpdb->prefix."utt_periods";
    //is insert
    if($periodid==0){
        $safeSql = $wpdb->prepare("INSERT INTO $periodsTable (period) VALUES (%s)",$period);
        $success = $wpdb->query($safeSql);
        if($success == 1){
            //success
            echo 1;
        }else{
            //fail
            echo 0;
        }
    //is edit
    }else{
        $safeSql = $wpdb->prepare("UPDATE $periodsTable SET period=%s WHERE periodID=%d ",$period,$periodid);
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

//ajax response delete period
add_action('wp_ajax_utt_delete_period', 'utt_delete_period');
function utt_delete_period(){
    global $wpdb;
    $periodsTable=$wpdb->prefix."utt_periods";
    $safeSql = $wpdb->prepare("DELETE FROM $periodsTable WHERE periodID=%d",$_GET['period_id']);
    $success = $wpdb->query($safeSql);
    //if success is 1 then delete succeeded
    echo $success;
    die();
}
?>