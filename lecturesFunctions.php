<?php
//include js
function utt_lecture_scripts(){
    
    

    //include lecture scripts
    wp_enqueue_script( 'lectureScripts',  plugins_url('js/lectureScripts.js?v=15761589991', __FILE__) );
    //localize lecture scripts
    wp_localize_script( 'lectureScripts', 'lectureStrings', array(
        'deleteForbidden' => __( 'Formu tamamlarken silme işlemi gerçekleştirilemez!', 'UniTimetable' ),
        'deleteLecture' => __( 'Bu gruptaki tüm dersleri silmek istediğinize emin misiniz ?', 'UniTimetable' ),
        'lectureDeleted' => __( 'Ders başarılı bir şekilde silindi!', 'UniTimetable' ),
        'lecturesDeleted' => __( 'Dersler başarıyla silindi!', 'UniTimetable' ),
        'lecturesNotDeleted' => __( 'Sadece kendi yerleştirdiğiniz dersleri kaldırabilirsiniz!', 'UniTimetable' ),
        'editForbidden' => __( 'Formu tamamlarken güncelleme işlemi gerçekleştirilemez!', 'UniTimetable' ),
        'editLecture' => __( 'Dersi güncelle', 'UniTimetable' ),
        'cancel' => __( 'İptal', 'UniTimetable' ),
        'periodVal' => __( 'Lütfen dönem seçin!', 'UniTimetable' ),
        'subjectVal' => __( 'Lütfen ders seçin!', 'UniTimetable' ),
        'groupVal' => __( 'Lütfen grup seçin!', 'UniTimetable' ),
        'teacherVal' => __( 'Lütfen öğretim üyesi seçin!', 'UniTimetable' ),
        'classroomVal' => __( 'Lütfen sınıf seçin!.', 'UniTimetable' ),
        'dateVal' => __( 'Yanlış tarih.', 'UniTimetable' ),
        'startTimeVal' => __( 'Hatalı başlangıç tarihi.', 'UniTimetable' ),
        'endTimeVal' => __( 'Hatalı bitiş tarihi.', 'UniTimetable' ),
        'timeVal' => __( 'Başlangıç zamanı bitiş zamanından sonra olamaz.', 'UniTimetable' ),
        'insertLecture' => __( 'Ders ekle', 'UniTimetable' ),
        'reset' => __( 'Sıfırla', 'UniTimetable' ),
        'lang' => __( 'tr', 'UniTimetable' ),/*edit: dil değişimi burdan*/
        'failAdd' => __( 'Ders eklenemedi. Öğretim üyesi, sınıf veya grup çakışmasını kontrol edin.', 'UniTimetable' ),
        'successAdd' => __( 'Dersler başarıyla eklendi!', 'UniTimetable' ),
        'failEdit' => __( 'Ders güncellenemedi. Öğretim üyesi, sınıf veya grup çakışmasını kontrol edin.', 'UniTimetable' ),
		'failEdit2' => __( 'Sadece kendi eklediğiniz dersleri düzenleyebilirsiniz.', 'UniTimetable' ),
        'successEdit' => __( 'Dersler başarıyla güncellendi!', 'UniTimetable' ),
        'notAllowed' => __( 'Bölümünüz değişiklik yapmaya kapatılmıştır.', 'UniTimetable' ),
    ));
    //include scripts and styles
    wp_enqueue_script( 'moment',  plugins_url('js/moment.min.js', __FILE__) );
    wp_enqueue_script( 'fullcalendar',  plugins_url('js/fullcalendar.js?v=157616017', __FILE__) );
    if(get_locale()=="el"){
        wp_enqueue_script( 'fullcalendargreek',  plugins_url('js/el.js', __FILE__) );
    }
    wp_enqueue_style( 'fullcalendarcss',  plugins_url('css/fullcalendar.css', __FILE__) );
    wp_enqueue_style( 'jqueryui_style', plugins_url('css/jquery-ui.css', __FILE__) );
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('jquery-ui-widget');
    wp_enqueue_script('jquery-ui-spinner');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_style( 'smoothnesscss',  plugins_url('css/smoothness-jquery-ui.css', __FILE__) );
    wp_enqueue_script('jquerymousewheel', plugins_url('js/jquery.mousewheel.js', __FILE__));
    wp_enqueue_script('globalize', plugins_url('js/globalize.js', __FILE__));
    wp_enqueue_script('globalizede', plugins_url('js/globalize.culture.de-DE.js', __FILE__));
    wp_enqueue_script('qtipjs', plugins_url('js/jquery.qtip.min.js', __FILE__));
    wp_enqueue_style( 'qtipcss',  plugins_url('css/jquery.qtip.min.css', __FILE__) );
	
	
	//edit
	//required for excel file download
	wp_register_script( 'exceljs', 'https://cdnjs.cloudflare.com/ajax/libs/exceljs/3.3.1/exceljs.min.js', null, null, true );
	wp_enqueue_script('exceljs');
	wp_register_script( 'filesaver', 'http://cdn.jsdelivr.net/g/filesaver.js', null, null, true );
	wp_enqueue_script('filesaver');
	
	

    
	
	wp_enqueue_script( 'sumojs',  plugins_url('sumo/jquery.sumoselect.js?v=1576159000', __FILE__) );
    wp_enqueue_style( 'sumocss',  plugins_url('sumo/sumoselect.css?v=1576159000', __FILE__) );
    
    
	    
}

function utt_create_lectures_page(){
    ?>
    <div class="wrap">
    <h2 id="lectureTitle"><?php _e("Ders Yerleştir","UniTimetable"); ?></h2>
    <div id="dialog-confirm" title="<?php _e("Bu Şubedeki tüm dersler silinsin mi?","UniTimetable"); ?>">
        <p></p>
    </div>
    <form action="" name="lectureForm" method="post" style="display:flex;flex-direction:column;">
        <input type="hidden" name="lectureID" id="lectureID" value=0 />
        <div class="element firstInRow">
        <?php _e("Dönem:","UniTimetable"); ?><br/>
        <select name="period" id="period" class="dirty" data-search="true">
            <?php
            //fill select with periods
            global $wpdb;
			//edit
			global $departments;
            $periodsTable=$wpdb->prefix."utt_periods";
            $periods = $wpdb->get_results( "SELECT * FROM $periodsTable ORDER BY periodID DESC");
            echo "<option value='0'>".__("- seç -","UniTimetable")."</option>";
            //$periods = array_reverse($periods);
            foreach($periods as $period)
            {
                echo "<option value='$period->periodID'>$period->period</option>";
            }
            ?>
        </select>
        </div>
        <div class="element">
            <?php _e("Bölüm Müfredatı:","UniTimetable"); ?><br/>
            <select name="semester" id="semester" class="dirty" onchange="loadSubjects(0);"  <?php /*placeholder="<?php _e("Müfredat Seçiniz","UniTimetable"); ?>"*/?> data-search="true">
                <option value="0"><?php _e("- seç -","UniTimetable"); ?></option>
                <?php
                //fill select with semester numbers
                //old: $i<11
                for( $i=1 ; $i<count($departments)+1 ; $i++ ){
                    echo "<option value='$i'>$i) ".$departments[$i-1]."</option>";
                }
                ?>
            </select>
        </div>
        <div class="element firstInRow">
			<?php _e("Ders:","UniTimetable"); ?><br/>
			<div id="subjects">
				<!-- place subjects into select when period and semester selected -->
			<select name="subject" id="subject" class="dirty" data-search="true">
				<option value='0'><?php _e("- seç -","UniTimetable"); ?></option>
			</select>
			</div>
			<span style="color:red" id="quotawarning2"></span>
			<br>
        </div>
        <div class="element">
            <?php _e("Şube:","UniTimetable"); ?><br/>
            <div id="groups">
                <!-- place groups when subject selected -->
                <select name="group" id="group" class="dirty" data-search="true">
                    <option value="0"><?php _e("- seç -","UniTimetable"); ?></option>
                </select>
            </div>
        </div>
        <div class="element firstInRow">
            <?php _e("Öğretim Üyesi:","UniTimetable"); ?><br/>
            <select name="teacher" id="teacher" class="dirty" data-search="true">
                <?php
                $teachersTable=$wpdb->prefix."utt_teachers";
                $teachers = $wpdb->get_results( "SELECT * FROM $teachersTable ORDER BY name, surname");
                echo "<option value='0'>".__("- seç -","UniTimetable")."</option>";
                foreach($teachers as $teacher){
                    echo "<option value='$teacher->teacherID'>$teacher->name $teacher->surname</option>";
                }
                ?>
            </select>
        </div>
        
		
        
		<br/>
		<div class="element">
			Açıklama (<span name="descharleft" id="descharleft">250</span>)<br><textarea style="width: 83%;" name="description" id="description" class="dirty" oninput="CheckDescription();"></textarea><br>
        </div>
        
		
        <div class="element firstInRow datetimeElements">
            <?php _e("Tarih:","UniTimetable"); ?>
            <br/>
            <?php /* <input type="text" name="date" id="date" class="dirty" size="14"/> */ ?>
			<select name="date" id="date" class="dirty">
				<option value='09/12/2019' selected="selected">Pazartesi</option>
				<option value='10/12/2019'>Salı</option>
				<option value='11/12/2019'>Çarşamba</option>
				<option value='12/12/2019'>Perşembe</option>
				<option value='13/12/2019'>Cuma</option>
				<option value='14/12/2019'>Cumartesi</option>
			</select>
        </div>
        
        <div class="element">
            <?php _e("Başlangıç saati:","UniTimetable"); ?><br/>
            <?php /*<input name="time" id="time" class="dirty" value="8:00" size="10"/>*/?>
			
			<select name="time1" id="time1" class="dirty">
				<?php
				
				
				$start_time = 8;
				$exclude_time = 12;
				$end_time = 19;
				for($ti = $start_time; $ti <= $end_time; $ti++)
				{
					if($ti==$exclude_time)
					{
						continue;
					}
					$selected = "";
					if($ti==$start_time)
					{
						$selected = ' selected="selected"';
					}
					$timestring = "0".$ti.":00";
					if(strlen($timestring)>5)
					{
						$timestring = substr($timestring,1,strlen($timestring));
					}

				?>
				<option value='<?php echo $timestring; ?>'<?php echo $selected; ?>><?php echo $timestring; ?></option>
				<?php
				}
				
				
				/*
				$start_time = 8;
				$exclude_time = 12;
				$end_time = 17;
				for($ti = $start_time; $ti < $end_time; $ti++)
				{
					if($ti==$exclude_time)
					{
						continue;
					}
					$selected = "";
					if($ti==$start_time)
					{
						$selected = ' selected="selected"';
					}
					$timestring = "0".$ti.":00";
					if(strlen($timestring)>5)
					{
						$timestring = substr($timestring,1,strlen($timestring));
					}

				?>
				<option value='<?php echo $timestring; ?>'<?php echo $selected; ?>><?php echo $timestring; ?></option>
				<?php
				}*/
				?>
			</select>
        </div>
        
        <div class="element datetimeElements">
            <?php _e("Bitiş saati:","UniTimetable"); ?><br/>
            <?php /*<input name="endTime" id="endTime" class="dirty" value="10:00" size="10"/>*/?>
			
				<select name="time2" id="time2" class="dirty">
				<?php
				
				
				$start_time = 8;
				$exclude_time = 12;
				$end_time = 19;
				for($ti = $start_time; $ti <= $end_time; $ti++)
				{
					if($ti==$exclude_time)
					{
						continue;
					}
					$selected = "";
					if($ti==$start_time)
					{
						$selected = ' selected="selected"';
					}
					$timestring = "0".$ti.":45";
					if(strlen($timestring)>5)
					{
						$timestring = substr($timestring,1,strlen($timestring));
					}

				?>
				<option value='<?php echo $timestring; ?>'<?php echo $selected; ?>><?php echo $timestring; ?></option>
				<?php
				}
				
				
				
				/*
				$start_time = 8;
				$exclude_time = 12;
				$end_time = 17;
				for($ti = $start_time; $ti < $end_time; $ti++)
				{
					if($ti==$exclude_time)
					{
						continue;
					}
					$selected = "";
					if($ti==$start_time)
					{
						$selected = ' selected="selected"';
					}
					$timestring = "0".$ti.":45";
					if(strlen($timestring)>5)
					{
						$timestring = substr($timestring,1,strlen($timestring));
					}

				?>
				<option value='<?php echo $timestring; ?>'<?php echo $selected; ?>><?php echo $timestring; ?></option>
				<?php
				}*/
				?>
			</select>
        </div>
        
        
        <div class="element">
            <?php _e("Sınıf:","UniTimetable"); ?><br/>
            <select name="classroom" id="classroom" class="dirty" onchange="QuotaWarning();" data-search="true">
                <?php
                //fill select with classrooms
                $classroomsTable=$wpdb->prefix."utt_classrooms";
                $classrooms = $wpdb->get_results( "SELECT * FROM $classroomsTable ORDER BY name");
                echo "<option value='0'>".__("- seç -","UniTimetable")."</option>";
                //translate classroom type
                foreach($classrooms as $classroom){
                    if($classroom->is_available=="0")
                    {
                        continue;
                    }
                    /*if($classroom->type == "Lecture"){
                        $classroomType = __("Lecture","UniTimetable");
                    }else{
                        $classroomType = __("Laboratory","UniTimetable");
                    }*/
					$classroomType = __("".$classroom->type,"UniTimetable");
                    echo "<option value='$classroom->classroomID'>$classroom->name $classroomType ($classroom->capacity)</option>";
                }
                ?>
            </select>
			<br>
			<span style="color:red" id="quotawarning"></span>
        </div>
		<br/>
        
		
		<?php /*bu kısım form için gerekli*/ ?>
		<div class="element weekDiv" style="display:none;">
			<input type="hidden" name="weeks" id="weeks" value="1">
			<?php
			/*
            <?php _e("Number of weeks:","UniTimetable"); ?><br/>
            <select name="weeks" id="weeks" class="dirty">
                <?php
                for( $i=1 ; $i<26 ; $i++ ){
                    echo "<option value='$i'>$i</option>";
                }
                ?>
            </select>
			*/
			?>
        </div>
		
		
		<br/>
            <div id="secondaryButtonContainer">
                <input type="submit" value="<?php _e("Ekle","UniTimetable"); ?>" id="insert-updateLecture" class="button-primary"/>
                <a href='#' class='button-secondary' id="clearLectureForm"><?php _e("Sıfırla","UniTimetable"); ?></a>
            </div>
		

    </form>
    <div id="messages"></div>
    <div id="filters" class="fc">

        <span id="filter0">
            <?php _e("Filtrele:","UniTimetable"); ?>&nbsp;<br><br>
            <select name="filterSelect0" id="filterSelect0" onchange='filterFunction();'>
                <?php
                global $wpdb;
                global $departments;
                $periodsTable=$wpdb->prefix."utt_periods";
                $periods = $wpdb->get_results( "SELECT * FROM $periodsTable ORDER BY periodID DESC");
                foreach($periods as $period)
                {
                    echo "<option value='$period->periodID'>$period->period</option>";
                }
                ?>
            </select>
        </span>
        
        <span id="filter1">
            <select name="filterSelect1" id="filterSelect1">
                <option value="semester" selected="selected"><?php _e("Bölüm","UniTimetable"); ?></option>
                <option value="teacher"><?php _e("Öğretim Üyesi","UniTimetable"); ?></option>
                <option value="classroom"><?php _e("Sınıf","UniTimetable"); ?></option>
                <option value="subject"><?php _e("Ders","UniTimetable"); ?></option>
            </select>
        </span>
        
        <span id="filter2">
            <select name="filterSelect2" id="filterSelect2" onchange='filterFunction();' data-search='true'>
                <option value="0"><?php _e("- seç -","UniTimetable"); ?></option>
                <?php for($i=1;$i<count($departments)+1;$i++){
                    /*if($i==1){
                        $selected = "selected='selected'";
                    }else{
                        $selected = "";
                    }*/
                    echo "<option value='$i' $selected>$i) ".$departments[$i-1]."</option>";
                } ?>
            </select>
        </span>
		
		<span id="filter3">
            <select name="filterSelect3" id="filterSelect3" onchange='filterFunction();'>
                <option value="0" selected="selected"><?php _e("Tüm Sınıflar","UniTimetable"); ?></option>
                <option value="1"><?php _e("1. Sınıf","UniTimetable"); ?></option>
                <option value="2"><?php _e("2. Sınıf","UniTimetable"); ?></option>
                <option value="3"><?php _e("3. Sınıf","UniTimetable"); ?></option>
                <option value="4"><?php _e("4. Sınıf","UniTimetable"); ?></option>
                <option value="11"><?php _e("Yüksek Lisans","UniTimetable"); ?></option>
                <option value="21"><?php _e("Doktora","UniTimetable"); ?></option>
            </select>
        </span>
		
		
		<?php
		/*
		<button onclick="goToStart();" class="fc-button fc-state-default fc-corner-left fc-corner-right">
			<span class="fc-icon fc-icon-left-double-arrow"></span>
			<?php _e("Dönem Başına Git","UniTimetable"); ?>
		</button>*/
		?>
		
		<button onclick="downloadCalendar();" class="fc-button fc-state-default fc-corner-left fc-corner-right">
			<span class="fc-icon">⇩</span>
			<?php _e("Haftalık Programı İndir (*.xlsx)","UniTimetable"); ?>
		</button>
		
		<button onclick="downloadCalendar2();" class="fc-button fc-state-default fc-corner-left fc-corner-right">
			<span class="fc-icon">⇩</span>
			<?php _e("Ders Programı Listesini İndir (*.xlsx)","UniTimetable"); ?>
		</button>
		
        <img id="loadingImg" src="<?php echo plugins_url('icons/spinner.gif', __FILE__); ?>"/>
    </div>
    <div id="calendar"></div>
    </div>
    <?php
}
//load groups combo-box when period and subject selected
add_action('wp_ajax_utt_load_groups','utt_load_groups');
function utt_load_groups(){
        $period = $_GET['period'];
        $subject = $_GET['subject'];
        if(isset($_GET['selected'])){
            $selected = $_GET['selected'];
        }
        global $wpdb;
        $groupsTable = $wpdb->prefix."utt_groups";
        $safeSql = $wpdb->prepare("SELECT * FROM $groupsTable WHERE periodID=%d AND subjectID=%d ORDER BY groupName;",$period,$subject);
        $groups = $wpdb->get_results($safeSql);
        echo "<select name='group' id='group' class='dirty'>";
        echo "<option value='0'>".__("- seç -","UniTimetable")."</option>";
        foreach($groups as $group){
            //choose group selected when edit
            if($selected==$group->groupID){
                $select = "selected='selected'";
            }else{
                $select = "";
            }
            echo "<option value='$group->groupID' $select>$group->groupName</option>";
        }
        echo "</select>";
        die();
}
//load subjects combo-box when period and semester selected
add_action('wp_ajax_utt_load_subjects','utt_load_subjects');
function utt_load_subjects(){
    $semester = $_GET['semester'];
    $selected = $_GET['selected'];
    global $wpdb;
    $subjectsTable = $wpdb->prefix."utt_subjects";
    $safeSql = $wpdb->prepare("SELECT * FROM $subjectsTable WHERE semester=%d ORDER BY title;",$semester);
    $subjects = $wpdb->get_results($safeSql);
    echo "<select name='subject' id='subject' class='dirty' onchange='loadGroups(0,0,0)' data-search='true'>";
    echo "<option value='0'>".__("- seç -","UniTimetable")."</option>";
    foreach($subjects as $subject){
        //choose selected subjects when edit
        if($selected==$subject->subjectID){
            $select = "selected='selected'";
        }else{
            $select = "";
        }
        //translate subject type
        if($subject->type == "T"){
            $subjectType = __("T","UniTimetable");
        }else if($subject->type == "L"){
            $subjectType = __("Lab/Uyg","UniTimetable");
        }else{
            $subjectType = __("PE","UniTimetable");
        }
        echo "<option value='$subject->subjectID' $select>$subject->title $subjectType ($subject->quota)</option>";
    }
    echo "</select>";
    die();
}

//ajax response insert-update lecture
add_action('wp_ajax_utt_insert_update_lecture','utt_insert_update_lecture');
function utt_insert_update_lecture(){
    global $wpdb;
	
	global $onlyallowedscanmodify;
	global $alloweds;
	$current_user = wp_get_current_user();
	
	//debug
	/*$wpdb->suppress_errors = false;
	$wpdb->show_errors = true;
	echo "{".$wpdb->print_error()."}";*/
	
	if($onlyallowedscanmodify)
	{
		if(!in_array($current_user->user_login,$alloweds))
		{
			die("3");
		}
	}
	
	$period = $_GET['period'];
	
    //data to be inserted/updated
    $lectureID=$_GET['lectureID'];
    $group=$_GET['group'];
    $teacher=$_GET['teacher'];
    $classroom=$_GET['classroom'];
    $description=$_GET['description'];//edit
	$userid = get_current_user_id();
	
	$description = substr($description, 0, 250);
	
            
	$isCommon = false;//TODO: seçilen zaman aralığında ders varsa ortaklık olup olmadığını kontrol et
	
	//echo "-".$classroom."-".$teacher."-";
	//belirtilmemiş sınıf ve hoca durumu
	/*if ($classroom==59 || $teacher==11)
	{
		$isCommon = true;
	}*/
	
    $date=$_GET['date'];
    $time=$_GET['time'];
    $endTime=$_GET['endTime'];
    $weeks=$_GET['weeks'];
    
    
    $subjectsTable = $wpdb->prefix."utt_subjects";
    
    
    $jointTable=$wpdb->prefix."utt_joint";
    $groupsTable=$wpdb->prefix."utt_groups";
    $lecturesTable=$wpdb->prefix."utt_lectures";
    $lecturesView=$wpdb->prefix."utt_lectures_view";
    $eventsTable=$wpdb->prefix."utt_events";
    
    
    
    if($classroom<1)
    {
        echo "0";
        die();
    }
    
    
    //is insert
    if($lectureID==0)
    {
		
        //transaction in order to cancel inserts if something goes wrong
        $wpdb->query('START TRANSACTION');
        //if conflict with a teacher, classroom or group, exists becomes 1
        $exists = 0;
        //insert records depending on weeks number
        for ($j=0;$j<=$weeks-1;$j++)
		{
            $d = new DateTime($date);
            //adds record to selected week, next loop adds to next week etc...
            $d->modify('+'.$j.' weeks');
            $usedDate = $d->format('y-m-d');
            
            $datetime = $usedDate." ".$time;
            $endDatetime = $usedDate." ".$endTime;
            //check if there is conflict
            
            // $busyTeacher = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d AND  teacherID=%d AND %s<end AND %s>start;",$period,$teacher,$datetime,$endDatetime));
            // $busyClassroom1 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d AND  classroomID=%d AND %s<end AND %s>start;",$period,$classroom,$datetime,$endDatetime));
            // $busyClassroom2 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d AND  classroomID=%d AND %s<eventEnd AND %s>eventStart;",$period,$classroom,$datetime,$endDatetime));
            // $busyGroup = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d AND  groupID=%d AND %s<end AND %s>start;",$period,$group,$datetime,$endDatetime));
            
            
            
            
            //CHECK CONF
            $getSubjectId_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $groupsTable WHERE periodID=%d AND groupID=%d;",$period,$group));
            $getSubjectId = $getSubjectId_q->subjectID;
            $getSubjectTitle_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $subjectsTable WHERE subjectID=%d;",$getSubjectId));
            $getSubjectTitle = $getSubjectTitle_q->title;
            
            
            
            $busyTeacher = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d     AND (subjectTitle!=%s OR (subjectTitle=%s AND classroomID!=%d))    AND     teacherID=%d    AND     %s<end      AND     %s>start;",     $period,$getSubjectTitle,$getSubjectTitle,$classroom,$teacher,$datetime,$endDatetime));
            $busyClassroom1 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d  AND (subjectTitle!=%s OR (subjectTitle=%s AND teacherID!=%d))    AND     classroomID=%d  AND     %s<end      AND     %s>start;",     $period,$getSubjectTitle,$getSubjectTitle,$teacher,$classroom,$datetime,$endDatetime));
            $busyClassroom2 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d  AND (subjectTitle!=%s OR (subjectTitle=%s AND teacherID!=%d))    AND     classroomID=%d  AND     %s<eventEnd AND     %s>eventStart;",$period,$getSubjectTitle,$getSubjectTitle,$teacher,$classroom,$datetime,$endDatetime));
            $busyGroup = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d       AND                             groupID=%d      AND     %s<end      AND     %s>start;",     $period,$group,$datetime,$endDatetime));
            
            //Online Education - Uzaktan Eğitim sınıfı
            if($classroom==278)
            {
                $busyClassroom1 = "";
                $busyClassroom2 = "";
            }
            //if there is conflict, exists becomes 1
            //edit
            
            
            
            
            
	        //joint check
            $findjoint_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d AND (teacherID=%d AND classroomID=%d) AND %s<end AND %s>start;",$period,$teacher,$classroom,$datetime,$endDatetime));
            if($findjoint_q!="")
            {
                $thissubjectid_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $groupsTable WHERE groupID=%d;",$group));
                $otherid = $findjoint_q->subjectID;
                $thisid = $thissubjectid_q->subjectID;
                $getthejoint_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $jointTable WHERE s1ID=%d OR s2ID=%d OR s3ID=%d OR s4ID=%d OR s5ID=%d;",$otherid,$otherid,$otherid,$otherid,$otherid));
                if($getthejoint_q!="" && ($getthejoint_q->s1ID == $thisid || ($getthejoint_q->s2ID == $thisid && $getthejoint_q->s2ok==1) || ($getthejoint_q->s3ID == $thisid && $getthejoint_q->s3ok==1) || ($getthejoint_q->s4ID == $thisid && $getthejoint_q->s4ok==1) || ($getthejoint_q->s5ID == $thisid && $getthejoint_q->s5ok==1) ))
                {
                    $isCommon = true;
                }
            }
            
            
            $explanation = array();
            if($busyTeacher!="")
            {
                $explanation[] = "öğretim elemanı";
            }
            if($busyGroup!="")
            {
                $explanation[] = "şube";
            }
            if($busyClassroom1!="" || $busyClassroom2!="")
            {
                $explanation[] = "derslik";
            }
            $expl=implode(", ",$explanation);
            
            
            if(!$isCommon && ($busyTeacher!="" || $busyGroup!="" || $busyClassroom1!="" || $busyClassroom2!=""))
			{
                $exists = 1;
                break;
            }
			else
			{
                $safeSql = $wpdb->prepare("INSERT INTO $lecturesTable (groupID, classroomID, teacherID, start, end, description,userid) VALUES( %d, %d, %d, %s, %s, %s, %d)",$group,$classroom,$teacher,$datetime,$endDatetime,$description,$userid);
                $wpdb->query($safeSql);
				
            }
        }
        //if exists is 0 then commit transaction
        if($exists==0){
            $wpdb->query('COMMIT');
            echo 1;
        //if exists is 1 rollback
        }else{
            $wpdb->query('ROLLBACK');
            echo "Muhtemel çakışmalar: ".$expl." ";
        }
    //update
    }
    else
    {
        $datetime = $date . " " . $time;
        $endDatetime = $date . " " . $endTime;
        
        
        
        // $busyTeacher = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d AND teacherID=%d AND %s<end AND %s>start AND lectureID<>%d;",$period,$teacher,$datetime,$endDatetime,$lectureID));
        // $busyClassroom1 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d AND  classroomID=%d AND %s<end AND %s>start AND lectureID<>%d;",$period,$classroom,$datetime,$endDatetime,$lectureID));
        // $busyClassroom2 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d AND  classroomID=%d AND %s<eventEnd AND %s>eventStart;",$period,$classroom,$datetime,$endDatetime));
        // $busyGroup = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d AND  groupID=%d AND %s<end AND %s>start AND lectureID<>%d;",$period,$group,$datetime,$endDatetime,$lectureID));
        //if any of the selects returns 1, fail
        
        
        
        
        
        
        
        //CHECK CONF
        $getSubjectId_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $groupsTable WHERE periodID=%d AND groupID=%d;",$period,$group));
        $getSubjectId = $getSubjectId_q->subjectID;
        $getSubjectTitle_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $subjectsTable WHERE subjectID=%d;",$getSubjectId));
        $getSubjectTitle = $getSubjectTitle_q->title;
        
        
        
        $busyTeacher = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d     AND (subjectTitle!=%s OR (subjectTitle=%s AND classroomID!=%d))    AND     teacherID=%d    AND     %s<end      AND     %s>start AND lectureID<>%d;",     $period,$getSubjectTitle,$getSubjectTitle,$classroom,$teacher,$datetime,$endDatetime,$lectureID));
        $busyClassroom1 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d  AND (subjectTitle!=%s OR (subjectTitle=%s AND teacherID!=%d))    AND     classroomID=%d  AND     %s<end      AND     %s>start AND lectureID<>%d;",     $period,$getSubjectTitle,$getSubjectTitle,$teacher,$classroom,$datetime,$endDatetime,$lectureID));
        $busyClassroom2 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d  AND (subjectTitle!=%s OR (subjectTitle=%s AND teacherID!=%d))    AND     classroomID=%d  AND     %s<eventEnd AND     %s>eventStart AND lectureID<>%d;",$period,$getSubjectTitle,$getSubjectTitle,$teacher,$classroom,$datetime,$endDatetime,$lectureID));
        $busyGroup = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d       AND                             groupID=%d      AND     %s<end      AND     %s>start AND lectureID<>%d;",     $period,$group,$datetime,$endDatetime,$lectureID));
        
        //Online Education - Uzaktan Eğitim sınıfı
        if($classroom==278)
        {
            $busyClassroom1 = "";
            $busyClassroom2 = "";
        }
        
        
        
        
        
    
    
    
        //joint check
        $findjoint_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d AND (teacherID=%d OR classroomID=%d) AND %s<end AND %s>start;",$period,$teacher,$classroom,$datetime,$endDatetime));
        if($findjoint_q!="")
        {
            $thissubjectid_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $groupsTable WHERE groupID=%d;",$group));
            $otherid = $findjoint_q->subjectID;
            $thisid = $thissubjectid_q->subjectID;
            if($thisid != $otherid)
            {
                $getthejoint_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $jointTable WHERE s1ID=%d OR s2ID=%d OR s3ID=%d OR s4ID=%d OR s5ID=%d;",$otherid,$otherid,$otherid,$otherid,$otherid));
                //echo "{".var_dump($otherid)."}";
                //echo "{".var_dump($getthejoint_q)."}";
                if($getthejoint_q!="" && ($getthejoint_q->s1ID == $thisid || ($getthejoint_q->s2ID == $thisid && $getthejoint_q->s2ok==1) || ($getthejoint_q->s3ID == $thisid && $getthejoint_q->s3ok==1) || ($getthejoint_q->s4ID == $thisid && $getthejoint_q->s4ok==1) || ($getthejoint_q->s5ID == $thisid && $getthejoint_q->s5ok==1) ))
                {
                    $isCommon = true;
                }
            }
        }
        
        
        
        
        $explanation = array();
        if($busyTeacher!="")
        {
            $explanation[] = "öğretim elemanı";
        }
        if($busyGroup!="")
        {
            $explanation[] = "şube";
        }
        if($busyClassroom1!="" || $busyClassroom2!="")
        {
            $explanation[] = "derslik";
        }
        $expl=implode(", ",$explanation);
        
        
        
        if(!$isCommon && ($busyTeacher!="" || $busyGroup!="" || $busyClassroom1!="" || $busyClassroom2!=""))
		{
            echo 0;
        }
		else
		{
			$safeSql_checklect = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesTable WHERE lectureID=%d AND userid=%d;",$lectureID,$userid));
			if($safeSql_checklect!="")
			{
				$safeSql = $wpdb->prepare("UPDATE $lecturesTable SET groupID=%d, classroomID=%d, teacherID=%d, start=%s, end=%s, description=%s WHERE lectureID=%d AND userid=%d;",$group,$classroom,$teacher,$datetime,$endDatetime,$description,$lectureID,$userid);
				$affectedRows = $wpdb->query($safeSql);
				echo 1;
			}
			else
			{
				echo 2;
			}
            
			/*if($affectedRows<1)
			{
				//echo "{".$wpdb->print_error()."}";
				echo 2;
			}
			else
			{
				echo 1;
			}*/
            //echo "".$affectedRows;
        }
    }
    die();
}

//json response view lectures etc...
add_action('wp_ajax_utt_json_calendar','utt_json_calendar');
function utt_json_calendar(){
    global $wpdb;
	global $departments;
	
    //filters value
    $periodId = $_POST['periodId'];

    $viewType = $_POST['viewType'];
    $viewFilter = $_POST['viewFilter'];
    $lecturesView = $wpdb->prefix."utt_lectures_view";
    $start = $_POST['start'];
    $end = $_POST['end'];
    $lecturesTable = $wpdb->prefix."utt_lectures";
    $groupsTable = $wpdb->prefix."utt_groups";
    $subjectsTable = $wpdb->prefix."utt_subjects";
    $jointsTable = $wpdb->prefix."utt_joint";
    $lecturesView=$wpdb->prefix."utt_lectures_view";
    switch ($viewType){
        //add to sql depending on filters
        case "semester":
			if($viewFilter>0)
			{
				$safeSql = $wpdb->prepare("SELECT * FROM $lecturesView WHERE DATE(start) BETWEEN %s AND %s AND periodID=%d AND semester=%d;",$start,$end,$periodId,$viewFilter);
			}
			else
			{
				$safeSql = $wpdb->prepare("SELECT * FROM $lecturesView WHERE DATE(start) BETWEEN %s AND %s AND periodID=%d;",$start,$end,$periodId);
			}
			
            break;
        case "teacher":
            $safeSql = $wpdb->prepare("SELECT * FROM $lecturesView WHERE DATE(start) BETWEEN %s AND %s AND periodID=%d AND teacherID=%d;",$start,$end,$periodId,$viewFilter);
            break;
        case "classroom":
            $safeSql = $wpdb->prepare("SELECT * FROM $lecturesView WHERE DATE(start) BETWEEN %s AND %s AND periodID=%d AND classroomID=%d;",$start,$end,$periodId,$viewFilter);
            break;
        case "subject":
            if($viewFilter!="-1")
                $safeSql = $wpdb->prepare("SELECT * FROM $subjectsTable S LEFT JOIN $groupsTable G ON G.subjectID = S.subjectID LEFT JOIN $lecturesView L ON L.groupID = G.groupID WHERE L.lectureID IS NOT NULL AND L.periodID=%d AND S.title = %s;",$periodId,$viewFilter);
            else
                $safeSql = $wpdb->prepare("SELECT * FROM $subjectsTable S LEFT JOIN $groupsTable G ON G.subjectID = S.subjectID LEFT JOIN $lecturesView L ON L.groupID = G.groupID WHERE L.lectureID IS NOT NULL AND L.periodID=%d AND S.is_common = 1;",$periodId);
            break;
    }
    //start and end of week viewed
    
    $lectures = $wpdb->get_results($safeSql);
    //array witch will be converted to json
    $jsonResponse = array();
    require('calendarColors.php');
    foreach($lectures as $lecture){
        if($viewType=="teacher"){
            //if filtered by teacher, load colors from calendarColors.php
            switch($lecture->subjectType){
                case "L":
                    $color = $colors[$lecture->semester-1][0];
                    break;
                case "T":
                    $color = $colors[$lecture->semester-1][1];
                    break;
                case "PE":
                    $color = $colors[$lecture->semester-1][2];
                    break;
            }
        }else{
            //load colors from database
            $color = "#".$lecture->color;
        }
        //translate subject type
        if($lecture->subjectType == "T"){
            $subjectType = __("T","UniTimetable");
        }else if($lecture->subjectType == "L"){
            $subjectType = __("Lab/Uyg","UniTimetable");
        }else{
            $subjectType = __("PE","UniTimetable");
        }
        //array with a lecture
        $result = array();
		
		
		
		//get joint subjects
		$jointinfo = "";
		$thissubjID=$lecture->subjectID;
		$getthejoint_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $jointsTable WHERE s1ID=%d OR s2ID=%d OR s3ID=%d OR s4ID=%d OR s5ID=%d OR s6ID=%d OR s7ID=%d;",$thissubjID,$thissubjID,$thissubjID,$thissubjID,$thissubjID,$thissubjID,$thissubjID));
		if($getthejoint_q != "")
		{
		    
		    
		    $jsID = $getthejoint_q->s1ID;
		    if($jsID != 0 && $jsID != $thissubjID)
		    {
		        $getthejointsubj_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE subjectID=%d AND teacherID=%d AND start=%s AND end=%s;",$jsID,$lecture->teacherID,$lecture->start,$lecture->end));
		        if($getthejointsubj_q!="")
		        $jointinfo .= " ".$getthejointsubj_q->subjectTitle." - ".$getthejointsubj_q->groupName." (#".$getthejointsubj_q->semester.") "."\n";
		    }
		    $jsID = $getthejoint_q->s2ID;
		    if($jsID != 0 && $jsID != $thissubjID)
		    {
		        $getthejointsubj_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE subjectID=%d AND teacherID=%d AND start=%s AND end=%s;",$jsID,$lecture->teacherID,$lecture->start,$lecture->end));
		        if($getthejointsubj_q!="")
		        $jointinfo .= " ".$getthejointsubj_q->subjectTitle." - ".$getthejointsubj_q->groupName." (#".$getthejointsubj_q->semester.") "."\n";
		    }
		    $jsID = $getthejoint_q->s3ID;
		    if($jsID != 0 && $jsID != $thissubjID)
		    {
		        $getthejointsubj_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE subjectID=%d AND teacherID=%d AND start=%s AND end=%s;",$jsID,$lecture->teacherID,$lecture->start,$lecture->end));
		        if($getthejointsubj_q!="")
		        $jointinfo .= " ".$getthejointsubj_q->subjectTitle." - ".$getthejointsubj_q->groupName." (#".$getthejointsubj_q->semester.") "."\n";
		    }
		    $jsID = $getthejoint_q->s4ID;
		    if($jsID != 0 && $jsID != $thissubjID)
		    {
		        $getthejointsubj_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE subjectID=%d AND teacherID=%d AND start=%s AND end=%s;",$jsID,$lecture->teacherID,$lecture->start,$lecture->end));
		        if($getthejointsubj_q!="")
		        $jointinfo .= " ".$getthejointsubj_q->subjectTitle." - ".$getthejointsubj_q->groupName." (#".$getthejointsubj_q->semester.") "."\n";
		    }
		    $jsID = $getthejoint_q->s5ID;
		    if($jsID != 0 && $jsID != $thissubjID)
		    {
		        $getthejointsubj_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE subjectID=%d AND teacherID=%d AND start=%s AND end=%s;",$jsID,$lecture->teacherID,$lecture->start,$lecture->end));
		        if($getthejointsubj_q!="")
		        $jointinfo .= " ".$getthejointsubj_q->subjectTitle." - ".$getthejointsubj_q->groupName." (#".$getthejointsubj_q->semester.") "."\n";
		    }
		    $jsID = $getthejoint_q->s6ID;
		    if($jsID != 0 && $jsID != $thissubjID)
		    {
		        $getthejointsubj_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE subjectID=%d AND teacherID=%d AND start=%s AND end=%s;",$jsID,$lecture->teacherID,$lecture->start,$lecture->end));
		        if($getthejointsubj_q!="")
		        $jointinfo .= " ".$getthejointsubj_q->subjectTitle." - ".$getthejointsubj_q->groupName." (#".$getthejointsubj_q->semester.") "."\n";
		    }
		    $jsID = $getthejoint_q->s7ID;
		    if($jsID != 0 && $jsID != $thissubjID)
		    {
		        $getthejointsubj_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE subjectID=%d AND teacherID=%d AND start=%s AND end=%s;",$jsID,$lecture->teacherID,$lecture->start,$lecture->end));
		        if($getthejointsubj_q!="")
		        $jointinfo .= " ".$getthejointsubj_q->subjectTitle." - ".$getthejointsubj_q->groupName." (#".$getthejointsubj_q->semester.") "."\n";
		    }
		    
		}
		
		$getthejointsubj_qp = $wpdb->prepare("SELECT * FROM $lecturesView WHERE subjectTitle=%s AND lectureID!=%d AND teacherID=%d AND start=%s AND end=%s;",$lecture->lectureID,$lecture->subjectTitle,$lecture->teacherID,$lecture->start,$lecture->end);
		$getthejointsubj_qr = $wpdb->get_results($getthejointsubj_qp);
		foreach($getthejointsubj_qr as $lectwsametitle)
		{
		    $jointinfo .= " ".$lectwsametitle->subjectTitle." - ".$lectwsametitle->groupName." (#".$lectwsametitle->semester.") "."\n";
		}
		
		
		
		
		//edit
		$lectureDescription = "";
		if($lecture->description!="")
		{
			$lectureDescription = " (".$lecture->description.") ";
		}
		
		//$lectureDescription .= $jointinfo;
        //$result['title'] = $lecture->subjectTitle." ".$subjectType.", ".$lecture->groupName.", ".$lecture->teacherName." ".$lecture->teacherSurname.", ".$lecture->classroomName.$lectureDescription;
		
// 		if($viewFilter!="-1")
// 		{
		    $result['title'] = $lecture->subjectTitle." ".$lecture->subjectType.", ".$departments[$lecture->semester-1]." ".$lecture->groupName.", ".$lecture->teacherName." ".$lecture->teacherSurname.", ".$lecture->classroomName." ".$lectureDescription."";//print_r($lecture,true);//
// 		}
// 		else
// 		{
// 		    $result['title'] = $lecture->subjectTitle." ".$lecture->subjectType.", ".$departments[$lecture->semester-1]." ".$lecture->groupName.", ".$lecture->classroomName." ".$lectureDescription."";
// 		}
		$result['subjectTitle'] = $lecture->subjectTitle;
		$result['subjectType'] = $subjectType;
		$result['classroomName'] = $lecture->classroomName;
		$result['groupName'] = $lecture->groupName;
		$result['teacherName'] = $lecture->teacherName . " ". $lecture->teacherSurname;
		$result['department'] = $departments[$lecture->semester -1];
			
        $result['start'] = $lecture->start;
        $result['end'] = $lecture->end;
        $result['periodID'] = $lecture->periodID;
        $result['semester'] = $lecture->semester;
        $result['subjectID'] = $lecture->subjectID;
        $result['classLevel'] = $lecture->class_level;
        $result['groupID'] = $lecture->groupID;
        $result['teacherID'] = $lecture->teacherID;
        $result['classroomID'] = $lecture->classroomID;
        $result['lectureID'] = $lecture->lectureID;
        $result['start2'] = $lecture->start;
        $result['end2'] = $lecture->end;
        $result['color'] = $color;
        $result['textColor'] = "black";
		
        $result['descr'] = $lectureDescription;
        $result['joints'] = $jointinfo;
        //add lecture to jsonResponse array
        array_push($jsonResponse,$result);
    }
    $holidaysTable = $wpdb->prefix."utt_holidays";
    $safeSql = $wpdb->prepare("SELECT * FROM $holidaysTable WHERE holidayDate BETWEEN %s AND %s;",$start,$end);
    $holidays = $wpdb->get_results($safeSql);
    foreach($holidays as $holiday){
        //array with a holiday
        $result = array();
        $result['title'] = $holiday->holidayName;
        $result['allDay'] = true;
        $result['start'] = $holiday->holidayDate;
        $result['color'] = "red";
        $result['textColor'] = "black";
        $result['descr'] = "";
        $result['buttons'] = false;
        //add holiday to jsonResponse array
        array_push($jsonResponse,$result);
    }
    $eventsTable = $wpdb->prefix."utt_events";
    $classroomsTable = $wpdb->prefix."utt_classrooms";
    //if filtered by classroom, show events for selected classroom
    if($viewType=="classroom"){
        $safeSql = $wpdb->prepare("SELECT * FROM $eventsTable,$classroomsTable WHERE $eventsTable.classroomID=$classroomsTable.classroomID AND DATE(eventStart) BETWEEN %s AND %s AND $eventsTable.classroomID=%d;",$start,$end,$viewFilter);
    }else{
        $safeSql = $wpdb->prepare("SELECT * FROM $eventsTable,$classroomsTable WHERE $eventsTable.classroomID=$classroomsTable.classroomID AND DATE(eventStart) BETWEEN %s AND %s;",$start,$end);
    }
    $events = $wpdb->get_results($safeSql);
    foreach($events as $event){
        //translate event type
        switch($event->eventType){
            case "Thesis":
                $eventType = __("Thesis","UniTimetable");
                break;
            case "Speech":
                $eventType = __("Speech","UniTimetable");
                break;
            case "Presentation":
                $eventType = __("Presentation","UniTimetable");
                break;
            case "Students Team":
                $eventType = __("Students Team","UniTimetable");
                break;
            case "Graduation":
                $eventType = __("Graduation","UniTimetable");
                break;
        }
        //array with an event
        $result = array();
        $result['title'] = $eventType.", ".$event->eventTitle.", ".$event->name;
        $result['start'] = $event->eventStart;
        $result['end'] = $event->eventEnd;
        $result['color'] = "black";
        $result['textColor'] = "white";
        $result['descr'] = ", ".$event->eventDescr;
        $result['buttons'] = false;
        //add event to jsonResponse array
        array_push($jsonResponse,$result);
    }
    //convert jsonResponse array to json
    echo json_encode($jsonResponse);
    die();
}

//ajax response delete group
add_action('wp_ajax_utt_delete_lecture', 'utt_delete_lecture');
function utt_delete_lecture(){
    global $wpdb;
	
	global $onlyallowedscanmodify;
	global $alloweds;
	$current_user = wp_get_current_user();
	
    $deleteAll = $_GET['delete_all'];
    $lectureID = $_GET['lecture_id'];
    $lecturesTable=$wpdb->prefix."utt_lectures";
    $safeSql = $wpdb->prepare("SELECT * FROM $lecturesTable WHERE lectureID=%d",$lectureID);
    $lecture = $wpdb->get_row($safeSql);
	
	$userid = get_current_user_id();
	$result = "";
	
	
	if($onlyallowedscanmodify)
	{
		if(!in_array($current_user->user_login,$alloweds))
		{
			die("3");
		}
	}
	
    //if delete all is 1, delete all lectures for this group
    if($deleteAll==1){
		if( current_user_can('editor') || current_user_can('administrator') )
		{
			$safeSql = $wpdb->prepare("DELETE FROM $lecturesTable WHERE groupID=%d;",$lecture->groupID);
		}
		else
		{
        	$safeSql = $wpdb->prepare("DELETE FROM $lecturesTable WHERE groupID=%d AND userid=%d;",$lecture->groupID,$userid);
		}
        $result = $wpdb->query($safeSql);
    //else delete only this lecture
    }
	else
	{
		if( current_user_can('editor') || current_user_can('administrator') )
		{
        	$safeSql = $wpdb->prepare("DELETE FROM `$lecturesTable` WHERE lectureID=%d;",$lectureID);
		}
		else
		{
        	$safeSql = $wpdb->prepare("DELETE FROM `$lecturesTable` WHERE lectureID=%d AND userid=%d;",$lectureID,$userid);
		}
        $result = $wpdb->query($safeSql);
    }
    die("".$result."");
}

//ajax response load filter
add_action('wp_ajax_utt_load_filter', 'utt_load_filter');
function utt_load_filter(){
    global $wpdb;
	global $departments;
	
    $lecturesTable = $wpdb->prefix."utt_lectures";
    $groupsTable = $wpdb->prefix."utt_groups";
    $subjectsTable = $wpdb->prefix."utt_subjects";
	
    $viewType = $_GET['viewType'];
    echo "<select name='filterSelect2' id='filterSelect2' onchange='filterFunction();' data-search='true'>";
    echo "<option value='0'>".__("- seç -","UniTimetable")."</option>";
    //load second filter depending on the first one
    switch($viewType){
        case "semester":
            for($i=1;$i<count($departments)+1;$i++){
                echo "<option value='$i'>$i) ".$departments[$i-1]."</option>";
            }
            break;
        case "teacher":
            $teachersTable = $wpdb->prefix."utt_teachers";
            $teachers = $wpdb->get_results("SELECT * FROM $teachersTable ORDER BY surname, name;");
            foreach($teachers as $teacher){
                echo "<option value='$teacher->teacherID'>$teacher->name $teacher->surname </option>";
            }
            break;
        case "classroom":
            $classroomsTable = $wpdb->prefix."utt_classrooms";
            $classrooms = $wpdb->get_results("SELECT * FROM $classroomsTable ORDER BY name;");
            foreach($classrooms as $classroom){
                echo "<option value='$classroom->classroomID'>$classroom->name </option>";
            }
            break;
        case "subject":
            $subjects = $wpdb->get_results("SELECT S.title, S.semester FROM $subjectsTable S GROUP BY S.title;");
            //$subjects = $wpdb->get_results("SELECT S.title, S.semester FROM $subjectsTable S LEFT JOIN $groupsTable G ON G.subjectID = S.subjectID LEFT JOIN $lecturesTable L ON L.groupID = G.groupID GROUP BY S.title;");
            echo "<option value='-1'>[Ortak Dersler]</option>";
            foreach($subjects as $subject)
            {
                echo "<option value='$subject->title'>$subject->title</option>";
                //echo "<option value='$subject->title'>".$departments[$subject->semester-1]." » $subject->title</option>";
            }
            break;
    }
    echo "</select>";
    die();
}

?>