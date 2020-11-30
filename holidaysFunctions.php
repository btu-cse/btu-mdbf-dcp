<?php
//include js
function utt_holiday_scripts(){
    //include holiday scripts
    wp_enqueue_script( 'holidayScripts',  plugins_url('js/holidayScripts.js', __FILE__) );
    //localize holiday scripts
    wp_localize_script( 'holidayScripts', 'holidayStrings', array(
        'deleteForbidden' => __( 'Formu doldururken silmek yasaktır!', 'UniTimetable' ),
        'deleteHoliday' => __( 'Bu tatili silmek istediğinize emin misiniz?', 'UniTimetable' ),
        'holidayDeleted' => __( 'Tatil başarıyla silindi!', 'UniTimetable' ),
        'editForbidden' => __( 'Formu doldururken düzenleme yapmak yasaktır!', 'UniTimetable' ),
        'editHoliday' => __( 'Tatili Düzenle', 'UniTimetable' ),
        'cancel' => __( 'İptal', 'UniTimetable' ),
        'nameVal' => __( 'Tatil adı gerekli. Lütfen özel karakterler kullanmaktan kaçının.', 'UniTimetable' ),
        'dateVal' => __( 'Tarih geçersiz.', 'UniTimetable' ),
        'insertHoliday' => __( 'Tatil Ekle', 'UniTimetable' ),
        'reset' => __( 'Sıfırla', 'UniTimetable' ),
        'failAdd' => __( 'Tatil eklenemedi. O tarihte bir tatil olup olmadığını kontrol edin.', 'UniTimetable' ),
        'successAdd' => __( 'Tatil başarıyla eklendi!', 'UniTimetable' ),
        'successEdit' => __( 'Tatil başarıyla düzenlendi!', 'UniTimetable' ),
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
    <h2 id="holidayTitle"> <?php _e("Tatil Ekle","UniTimetable"); ?> </h2>
    <form action="" name="holidayForm" method="post">
        <input type="hidden" name="isEdit" id="isEdit" value=0 />
        <?php _e("Tatil ismi:","UniTimetable"); ?><br/>
        <input type="text" name="holidayName" id="holidayName" class="dirty" required placeholder="<?php _e("Zorunlu","UniTimetable"); ?>"/>
        <br/>
        <?php _e("Tarih:","UniTimetable"); ?><br/>
        <input type="text" name="holidayDate" id="holidayDate" class="dirty" value=""/>
        <br/>
        <div id="secondaryButtonContainer">
        <input type="submit" value="<?php _e("Ekle","UniTimetable"); ?>" id="insert-updateHoliday" class="button-primary"/>
        <a href='#' class='button-secondary' id="clearHolidayForm"><?php _e("Sıfırla","UniTimetable"); ?></a>
        </div>
    </form>
    <!-- place to view messages -->
    <div id="messages"></div>
    <?php 
    _e("Tatil Yılı:","UniTimetable"); ?>
    <!-- show filter, select current year on first load -->
    <select name="yearFilter" id="yearFilter" onchange="viewHolidays();">
        <option value="0"><?php _e("Hepsi","UniTimetable"); ?></option>
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
                    <th><?php _e("Tarih","UniTimetable"); ?></th>
                    <th><?php _e("Tatil ismi","UniTimetable"); ?></th>
                    <th><?php _e("Eylemler","UniTimetable"); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><?php _e("Tarih","UniTimetable"); ?></th>
                    <th><?php _e("Tatil ismi","UniTimetable"); ?></th>
                    <th><?php _e("Eylemler","UniTimetable"); ?></th>
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
                <td><a href='#' onclick=\"deleteHoliday('$holiday->holidayDate');\" class='deleteHoliday'><img id='edit-delete-icon' src='".plugins_url('icons/delete_icon.png', __FILE__)."'/> ".__("Sil","UniTimetable")."</a>&nbsp;
                <a href='#' onclick=\"editHoliday('".$holiday->changedDate."','".$holiday->holidayName."');\" class='editHoliday'><img id='edit-delete-icon' src='".plugins_url('icons/edit_icon.png', __FILE__)."'/> ".__("Düzenle","UniTimetable")."</a></td></tr>";
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