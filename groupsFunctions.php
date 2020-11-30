<?php
//include js
function utt_group_scripts(){
    //include groupScripts
    wp_enqueue_script( 'groupScripts',  plugins_url('js/groupScripts.js?v=1576043133', __FILE__) );
    //localize groupScripts
    wp_localize_script( 'groupScripts', 'groupStrings', array(
        'deleteForbidden' => __( 'Formu doldururken silmek yasaktır. Lütfen sayfayı yenileyiniz.', 'UniTimetable' ),
        'deleteGroup' => __( 'Bu Şubeyi silmek istediğinize emin misiniz?', 'UniTimetable' ),
        'groupDeleted' => __( 'Şube başarıyla silindi!', 'UniTimetable' ),
        'groupNotDeleted' => __( 'Şube silinemedi. Çizelgede yerleştirilmiş halde bulunan şubeler silinemez. Lütfen önce şubeyi çizelgeden kaldırınız.', 'UniTimetable' ),
        'editForbidden' => __( 'Formu doldururken düzenleme yapmak yasaktır! Lütfen sayfayı yenileyiniz.', 'UniTimetable' ),
        'editGroup' => __( 'Şubeyi düzenle', 'UniTimetable' ),
        'cancel' => __( 'İptal', 'UniTimetable' ),
        'periodVal' => __( 'Lütfen dönem seçin.', 'UniTimetable' ),
        'semesterVal' => __( 'Lütfen bölüm seçin.', 'UniTimetable' ),
        'subjectVal' => __( 'Lütfen ders seçin.', 'UniTimetable' ),
        'nameVal' => __( 'Lütfen özel karakterler kullanmaktan kaçının ve uzun isimler kullanmayın.', 'UniTimetable' ),
        'insertGroup' => __( 'Şube ekle', 'UniTimetable' ),
        'reset' => __( 'Sıfırla', 'UniTimetable' ),
        'group' => __( 'Şube', 'UniTimetable' ),
        'failAdd' => __( 'Şubeler eklenemedi. Aynı özelliklere sahip başka şubeler olup olmadığını kontrol edin.', 'UniTimetable' ),
        'successAdd' => __( 'Şubeler başarıyla eklendi!', 'UniTimetable' ),
        'failEdit' => __( 'Şube düzenlenemedi. Aynı özelliklere sahip başka bir şube olup olmadığını kontrol edin.', 'UniTimetable' ),
        'successEdit' => __( 'Şube başarıyla düzenlendi!', 'UniTimetable' ),
    ));
    
    
    
	
	wp_enqueue_script( 'sumojs',  plugins_url('sumo/jquery.sumoselect.js?v=1576159000', __FILE__) );
    wp_enqueue_style( 'sumocss',  plugins_url('sumo/sumoselect.css?v=1576159000', __FILE__) );
    
    
    
}
//groups page
function utt_create_groups_page(){
    //group form
    ?>
    <div class="wrap">
        <h2 id="groupTitle"> <?php _e("Şube Ekle","UniTimetable"); ?> </h2>
        <form action="" name="groupForm" method="post" style="display: flex; flex-direction: column;">
            <input type="hidden" name="groupID" id="groupID" value=0 />
            <div class="element firstInRow">
            <?php _e("Dönem:","UniTimetable"); ?><br/>
            <select name="period" id="period" class="dirty">
                <option value="0"><?php _e("- seç -","UniTimetable"); ?></option>
                <?php
                global $wpdb;
				global $departments;
                //show registered periods
                $periodsTable=$wpdb->prefix."utt_periods";
                $periods = $wpdb->get_results( "SELECT * FROM $periodsTable ORDER BY periodID DESC");
                foreach($periods as $period)
                {
                    echo "<option value='$period->periodID'>$period->period</option>";
                }
                ?>
            </select>
            </div>
            <div class="element">
            <?php _e("Bölüm:","UniTimetable"); ?><br/>
            <select name="semester" id="semester" class="dirty" onchange="loadSubjects(0);"  data-search="true">
                <option value="0"><?php _e("- seç -","UniTimetable"); ?></option>
                <?php
                //show semester numbers
                for( $i=1 ; $i<count($departments)+1; $i++ ){
                    echo "<option value='$i'>$i) ".$departments[$i-1]."</option>";
                }
                ?>
            </select><br/>
            </div>
            <div class="element firstInRow">
            <?php _e("Ders:","UniTimetable"); ?><br/>
            <!-- load subjects when semester number is selected -->
            <div id="subjects">
            <select name="subject" id="subject" class="dirty" onchange="loadGetGroupNumber(0);">
                <option value='0'><?php _e("- seç -","UniTimetable"); ?></option>
            </select>
            </div>
            </div>
			
			
            <div class="element firstInRow groupsName">
            <?php _e("Şube Adı:","UniTimetable"); ?><br/>
            <!-- prefix of groups' names NOT ANYMORE it's name of the new entry from now on -->
            <input type="text" name="groupsName" id="groupsName" class="notdirty" value="<?php _e("Şube","UniTimetable"); ?> "/>
            </div>
			
			
			<?php /*
            <div class="element">
            <!-- select number of groups to be created -->
            <?php _e("Şube Numarası:","UniTimetable"); ?><br/>
            <select name="groupsNumber" id="groupsNumber" class="dirty">
                <?php
                for($i=1;$i<=5;$i++){
                    echo "<option value=$i>$i</option>";
                }
                ?>
            </select>
            </div>
			*/
			?>
			
         <!--   <div class="element counterStart">
            <?php #_e("Counter Starτ:","UniTimetable"); ?>
             starting number of groups that will be created
            <select name="counterStart" id="counterStart" class="dirty">
                <?php
                # for($i=1;$i<16;$i++){
                #    echo "<option value=$i>$i</option>";
                # }
                ?>
            </select>
            </div> -->
            <div id="secondaryButtonContainer">
                <input type="submit" value="<?php _e("Ekle","UniTimetable"); ?>" id="insert-updateGroup" class="button-primary"/>
                <a href='#' class='button-secondary' id="clearGroupForm"><?php _e("Sıfırla","UniTimetable"); ?></a>
            </div>
        </form>
        <!-- place to view messages -->
        <div id="messages"></div>
        <!-- filters to filter shown groups -->
        <span id="filter1">
    <?php _e("Dönem:","UniTimetable"); ?>
    <select name="periodFilter" id="periodFilter" onchange="viewGroups();">
        <?php
        $periodsTable=$wpdb->prefix."utt_periods";
        $periods = $wpdb->get_results( "SELECT * FROM $periodsTable ORDER BY periodID ASC");
        //current date
        $date = date("Y-m-d");
        echo "<option value=''>".__("- seç -","UniTimetable")."</option>";
        $it_pers = 0;
        $periods = array_reverse($periods);
        foreach($periods as $period)
        {
                $selected = "";
                if($it_pers == 0)
                {
                        $selected = "selected='selected'";
                        $it_pers = 1;
                }
                
            echo "<option value='$period->periodID' $selected>$period->period</option>";
        }
        ?>
    </select>
    </span>
    <span id="filter2">
        <?php _e("Bölüm:","UniTimetable"); ?>
        <select name="semesterFilter" id="semesterFilter" onchange="viewGroups();"  data-search="true">
            <option value="0" selected='selected'><?php _e("Hepsi","UniTimetable"); ?></option>
            <?php
            for($i=1;$i<count($departments)+1;$i++){
                //select 1st semester
                if($i == 1){
                    $selected = "";
                }else{
                    $selected = "";
                }
                echo "<option value='$i' $selected>$i) ".$departments[$i-1]."</option>";
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
	global $departments;
	/*$wpdb->suppress_errors = false;
	$wpdb->show_errors = true;*/
	
    //get filter values
    if(isset($_GET['period_id'])){
        $periodID = $_GET['period_id'];
        $semester = $_GET['semester'];
    //if first time loaded, show results for current period and 1st semester
    }else{
        $periodsTable=$wpdb->prefix."utt_periods";
        $periods = $wpdb->get_results( "SELECT * FROM $periodsTable ORDER BY period DESC");
        $date = date("Y-m-d");
        /*foreach($periods as $period){
            $startWinter = $period->year."-09-01";
            $nextYear = $period->year +1;
            $endWinter = $nextYear . "-03-01";
            $startSpring = $period->year . "-03-01";
            if($date >= $startWinter && $date < $endWinter && $period->semester == "W"){
                $periodID = $period->periodID;
            }else if($date >= $startSpring && $date<$startWinter && $period->semester == "S"){
                $periodID = $period->periodID;
            }
        }*/
        $semester = 1;
    }
    //show registered groups
    $groupsTable = $wpdb->prefix."utt_groups";
    $subjectsTable = $wpdb->prefix."utt_subjects";
    //if not selected period, show nothing
    
    if($periodID == ""){
        $periodID=0;
        $safeSql = $wpdb->prepare("SELECT * FROM $groupsTable, $subjectsTable WHERE $groupsTable.subjectID=$subjectsTable.subjectID ORDER BY title, type, groupName");
    }
	else if($semester==0)
	{
        $safeSql = $wpdb->prepare("SELECT * FROM $groupsTable, $subjectsTable WHERE $groupsTable.subjectID=$subjectsTable.subjectID AND periodID=%d ORDER BY title, type, groupName",$periodID);
    }
	else
	{
        $safeSql = $wpdb->prepare("SELECT * FROM $groupsTable, $subjectsTable WHERE $groupsTable.subjectID=$subjectsTable.subjectID AND periodID=%d AND semester=%d ORDER BY title, type, groupName",$periodID,$semester);
    }
	
	
	//echo "{".$wpdb->print_error()."}";
    $groups = $wpdb->get_results($safeSql);
    ?>
        <!-- show table of groups -->
        <table class="widefat bold-th">
            <thead>
                <tr>
                    <th><?php _e("Bölüm","UniTimetable"); ?></th>
                    <th><?php _e("Ders","UniTimetable"); ?></th>
                    <th><?php _e("Şube","UniTimetable"); ?></th>
                    <th><?php _e("Eylemler","UniTimetable"); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><?php _e("Bölüm","UniTimetable"); ?></th>
                    <th><?php _e("Ders","UniTimetable"); ?></th>
                    <th><?php _e("Şube","UniTimetable"); ?></th>
                    <th><?php _e("Eylemler","UniTimetable"); ?></th>
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
                $type = __("L/U","UniTimetable");
            }else{
                $type = __("PE","UniTimetable");
            }
            //a record
            echo "<tr id='$group->groupID' $addClass>
			<td>".$departments[$group->semester -1]."</td>
			<td>$group->title $type</td>
			<td>$group->groupName</td>
                <td><a href='#' onclick='deleteGroup($group->groupID);' class='deleteGroup'><img id='edit-delete-icon' src='".plugins_url('icons/delete_icon.png', __FILE__)."'/> ".__("Sil","UniTimetable")."</a>&nbsp;
                <a href='#' onclick=\"editGroup($group->groupID,$group->periodID,$group->semester,$group->subjectID,'$group->groupName');\" class='editGroup'><img id='edit-delete-icon' src='".plugins_url('icons/edit_icon.png', __FILE__)."'/> ".__("Düzenle","UniTimetable")."</a></td></tr>";
        }
    ?>
            </tbody>
        </table>
    <?php
    die();
}

add_action('wp_ajax_utt_get_group_number','utt_get_group_number');
function utt_get_group_number()
{
	global $wpdb;
    $groupsTable = $wpdb->prefix."utt_groups";
	
	//debug
	//$wpdb->suppress_errors = false;
	//$wpdb->show_errors = true;
		
    $periodID=$_GET['period_id'];
    $subjectID=$_GET['subject_id'];
	
	
	$prev_groups_query = $wpdb->prepare( "SELECT COUNT(*) num FROM $groupsTable WHERE periodID=%d AND subjectID=%d", $periodID, $subjectID);
	$prev_groups = $wpdb->get_results($prev_groups_query);
	//print_r($prev_groups);
	$prev_group_num = $prev_groups[0]->num;
	echo $prev_group_num;
	die();
}

//ajax response insert-update group
add_action('wp_ajax_utt_insert_update_group','utt_insert_update_group');
function utt_insert_update_group()
{
    global $wpdb;
	//debug
	/*$wpdb->suppress_errors = false;
	$wpdb->show_errors = true;*/
    //data to be inserted/updated
    $groupID=$_GET['group_id'];
    $periodID=$_GET['period_id'];
    $subjectID=$_GET['subject_id'];
    $groupName=$_GET['group_name'];
    $counterStart=$_GET['counter_start'];
    //$groupsNumber=$_GET['groups_number'];
    $groupsTable=$wpdb->prefix."utt_groups";
    $success = 0;
    // if groupID is 0, it is insert
    if($groupID==0)
	{
		//get the number of previously-created groups with the same features
		//$safeSql_chk = $wpdb->prepare("SELECT NUM(*) AS num FROM $groupsTable WHERE $groupsTable.subjectID=%d AND periodID=%d AND groupName=%s",$subjectID,$periodID,$nameUsed);
		//$groups_chk = $wpdb->get_results($safeSql_chk);
		//$groups_num = $groups_chk->num;

        //transaction, so if an insert fails, it rolls back
        $wpdb->query('START TRANSACTION');
        //for($i=($groups_num+1);$i<=$groupsNumber;$i++)
		//{
            //name is generated by prefix(groupName) and a number, starting from counterstart
            $nameUsed = $groupName;//$groupName." ".$groupsNumber;//$i
            $safeSql = $wpdb->prepare("INSERT INTO $groupsTable (periodID, subjectID, groupName) VALUES (%d,%d,%s)",$periodID,$subjectID,$nameUsed);
            $success = $wpdb->query($safeSql);
            $counterStart ++;
            if($success != 1)
			{
                //if an insert fails, for breaks
                $success = 0;
                //break;
            }
        //}
        //if every insert succeeds, commit transaction
        if($success == 1)
		{
            $wpdb->query('COMMIT');
            echo 1;
        //else rollback
        }
		else
		{
            $wpdb->query('ROLLBACK');
            echo 0;
        }
    //it is edit
    }
	else
	{
        $safeSql = $wpdb->prepare("UPDATE $groupsTable SET periodID=%d, subjectID=%d, groupName=%s WHERE groupID=%d ",$periodID,$subjectID,$groupName,$groupID);
        $success = $wpdb->query($safeSql);
        if ($success==1)
		{
            echo 1;
        }
		else
		{
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
    echo "<select name='subject' id='subject' class='dirty' onchange='loadGetGroupNumber(0);'>";
    echo "<option value='0'>".__("- seç -","UniTimetable")."</option>";
    foreach($subjects as $subject){
        //if edit, select the stored subject
        if($selected==$subject->subjectID){
            $select = "selected='selected'";
        }else{
            $select = "";
        }
        //translate subject type
        if($subject->type == "T"){
            $type = __("Teori","UniTimetable");
        }else if($subject->type == "L"){
            $type = __("Lab/Uyg","UniTimetable");
        }else{
            $type = __("PE","UniTimetable");
        }
        echo "<option value='$subject->subjectID' $select>$subject->title $type</option>";
    }
    echo "</select>";
    die();
}

?>