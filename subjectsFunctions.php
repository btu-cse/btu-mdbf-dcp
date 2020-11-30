<?php
//include js
function utt_subject_scripts(){
    //include subject scripts
    wp_enqueue_script( 'subjectScripts',  plugins_url('js/subjectScripts.js?v=1576159002', __FILE__) );
    //localize subject scripts
    wp_localize_script( 'subjectScripts', 'subjectStrings', array(
        'deleteForbidden' => __( 'Formu doldururken silmek yasaktır!', 'UniTimetable' ), 
        'deleteQuestion' => __( 'Bu dersi silmek istediğinden emin misin?', 'UniTimetable' ), 
        'subjectDeleted' => __( 'Ders başarıyla silindi!', 'UniTimetable' ), 
        'subjectNotDeleted' => __( 'Ders silinemedi. Dersin şubelere bağlı olup olmadığını kontrol edin.', 'UniTimetable' ), 
        'editForbidden' => __( 'Formu doldururken düzenlemek yasaktır!', 'UniTimetable' ), 
        'editSubject' => __( 'Dersi Düzenle', 'UniTimetable' ), 
        'cancel' => __( 'İptal', 'UniTimetable' ), 
        'nameVal' => __( 'Ders Adı alanı zorunludur. Lütfen özel karakterler kullanmaktan kaçının.', 'UniTimetable' ), 
        'typeVal' => __( 'Lütfen bir ders tipi seçin.', 'UniTimetable' ), 
        'semesterVal' => __( 'Lütfen bir dönem seçin.', 'UniTimetable' ), 
        'colorVal' => __( 'Yanlış renk kodu.', 'UniTimetable' ), 
        'insertSubject' => __( 'Ders Tanımla', 'UniTimetable' ), 
        'reset' => __( 'Sıfırla', 'UniTimetable' ), 
        'failAdd' => __( 'Ders tanımlamadı. Dersin ve yetkinizin var olup olmadığını kontrol edin.', 'UniTimetable' ), 
        'successAdd' => __( 'Ders başarıyla Tanımlandı!', 'UniTimetable' ), 
        'failEdit' => __( 'Ders düzenlenemedi. Dersin ve yetkinizin var olup olmadığını kontrol edin.', 'UniTimetable' ), 
        'successEdit' => __( 'Ders başarıyla düzenlendi!', 'UniTimetable' ),
        'failAuth' => __( 'Bu işlem için yetkiniz bulunmamaktadır! Yetkili olduğunuz bölüm/müfredat üzerinde değişiklik yapıyor olup olmadığınızı kontrol ediniz.', 'UniTimetable' ),
    ));
    //include js color
    wp_enqueue_script( 'jscolor',  plugins_url('js/jscolor.js', __FILE__) );
    
    
    
	
	wp_enqueue_script( 'sumojs',  plugins_url('sumo/jquery.sumoselect.js?v=1576159000', __FILE__) );
    wp_enqueue_style( 'sumocss',  plugins_url('sumo/sumoselect.css?v=1576159000', __FILE__) );
    
    
    
    
}
//subjects page
function utt_create_subjects_page(){
	global $departments;
    //subject form
    ?>
    <div class="wrap">
        <h2 id="subjectTitle"> <?php _e("Ders Tanımla","UniTimetable"); ?> </h2>
        <form action="" name="subjectForm" method="post" style="display:flex;flex-direction:column;">
            <input type="hidden" name="subjectid" id="subjectid" value=0 />
			
			
            <div class="element">
            <?php _e("Ders Kodu:","UniTimetable"); ?><br/>
            <input type="text" name="subjectcode" id="subjectcode" class="dirty" size="40" placeholder="<?php _e("","UniTimetable"); ?>"/>
            </div>
			
            <div class="element">
            <?php _e("Ders Adı:","UniTimetable"); ?><br/>
            <input type="text" name="subjectname" id="subjectname" class="dirty" size="40" placeholder="<?php _e("","UniTimetable"); ?>"/>
            </div>
					
			
            <div class="element">
            <?php _e("Kaçıncı Sınıf:","UniTimetable"); ?><br/>
            <select name="subjectclasslevel" id="subjectclasslevel" class="dirty">
                <option value="1"><?php _e("1. Sınıf","UniTimetable"); ?></option>
                <option value="2"><?php _e("2. Sınıf","UniTimetable"); ?></option>
                <option value="3"><?php _e("3. Sınıf","UniTimetable"); ?></option>
                <option value="4"><?php _e("4. Sınıf","UniTimetable"); ?></option>
                <?php /*<option value="0"><?php _e("Hazırlık","UniTimetable"); ?></option>*/ ?>
                <option value="11"><?php _e("Yüksek Lisans","UniTimetable"); ?></option>
                <option value="21"><?php _e("Doktora","UniTimetable"); ?></option>
            </select>
            </div>
			
            <div class="element">
            <?php _e("Kontenjan:","UniTimetable"); ?><br/>
            <input type="text" name="subjectquota" id="subjectquota" class="dirty" size="40" placeholder="<?php _e("","UniTimetable"); ?>"/>
            </div>
			
			
			<?php
			/*
            <div class="element">
            <?php _e("Ders Numarası:","UniTimetable"); ?><br/>
            <select name="subjectnumber" id="subjectnumber" class="dirty" id="subjecttype">
                <option value="-"><?php _e("","UniTimetable"); ?></option>
                <option value="I"><?php _e("I","UniTimetable"); ?></option>
                <option value="II"><?php _e("II","UniTimetable"); ?></option>
                <option value="III"><?php _e("III","UniTimetable"); ?></option>
                <option value="IV"><?php _e("IV","UniTimetable"); ?></option>
            </select>
            </div>
			*/
			?>
			
            <div class="element2 firstInRow last">
            <?php _e("Tip:","UniTimetable"); ?><br/>
            <select name="subjecttype" class="dirty" id="subjecttype">
                <option value="0"><?php _e("- seç -","UniTimetable"); ?></option>
                <option value="T"><?php _e("Teori","UniTimetable"); ?></option>
                <option value="L"><?php _e("Lab/Uyg","UniTimetable"); ?></option>
            
            </select>
            </div>
            
            <div class="element2">
            <?php _e("Bölüm:","UniTimetable"); ?><br/>
            <select name="semester" id="semester" class="dirty" data-search="true">
                <option value="0"><?php _e("- seç -","UniTimetable"); ?></option>
                <?php
                for($i=1;$i<count($departments)+1;$i++){
                    echo "<option value=$i>$i) ".$departments[$i-1]."</option>";
                }
                ?>
            </select>
            </div>
            
            
            <div class="element2">
            <br/><?php _e("Fakülte ortak dersi: ","UniTimetable"); ?> 
                <input type="checkbox" id="is_common" name="is_common" <?php if(!IsAdmin()){_e("disabled","UniTimetable");} ?>>
            </div><br/>
            
			
            <div class="element2" style="display:none;">
            <?php _e("Renk:","UniTimetable"); ?><br/>
            <input type="text" id="color" class="color dirty" size="10" name="color"/>
            </div>
			
            <div id="secondaryButtonContainer">
                <input type="submit" value="<?php _e("Ekle","UniTimetable"); ?>" id="insert-updateSubject" class="button-primary"/>
                <a href='#' class='button-secondary' id="clearSubjectForm"><?php _e("Sıfırla","UniTimetable"); ?></a>
            </div>
        </form>
    <!-- place to view messages -->
    <div id="messages"></div>
    <?php _e("Bölüm Seçiniz:","UniTimetable"); ?>
    <!-- semester filter -->
    <select name="semesterFilter" id="semesterFilter" onchange="viewSubjects();"  data-search="true">
        <option value='0'><?php _e("Hepsi","UniTimetable"); ?></option>
        <?php
        
        for($i=1;$i<count($departments)+1;$i++){
            echo "<option value='$i'>$i) ".$departments[$i-1]."</option>";
        }
        ?>
    </select>
    <!-- place to view subjects table -->
    <div id="subjectsResults">
        <?php utt_view_subjects(); ?>
    </div>
    <?php
}

//ajax response insert-update subject
add_action('wp_ajax_utt_insert_update_subject','utt_insert_update_subject');
function utt_insert_update_subject(){
    global $wpdb;
    
    //yetki kontrolü - auth check
    global $departments, $departmentusers;
    $current_user = wp_get_current_user();
    $current_user_id = get_current_user_id();
    
    
    //data
    $subjectID=$_GET['subject_id'];
    $subjectName=$_GET['subject_name'];
    $quota=$_GET['subject_quota'];
    $subjectType=$_GET['subject_type'];
    $semester=$_GET['semester'];
    $color=$_GET['color'];
    $class_level=$_GET['class_level'];
    $is_common=$_GET['is_common'];
    
    
    
    
    //yetki kontrolü - auth check
    if($current_user_id != $departmentusers[$semester-1] && !IsAdmin())
    {
        echo -1;
        //echo "-1 ".$departmentusers[$semester-1]." ".$current_user_id;
        die();
    }
    
    
    
    $subjectsTable=$wpdb->prefix."utt_subjects";
    //insert
    if($subjectID==0)
	{
        $safeSql = $wpdb->prepare("INSERT INTO $subjectsTable (title, type, semester, color, quota, class_level,is_common) VALUES (%s,%s,%s,%s,%d,%d,%d)",$subjectName,$subjectType,$semester,$color,$quota,$class_level,$is_common);
        $success = $wpdb->query($safeSql);
        if($success == 1)
		{
            //success
            echo 1;
        }
		else
		{
            //fail
            echo 0;
        }
    //edit
    }
	else
	{
        $safeSql = $wpdb->prepare("UPDATE $subjectsTable SET title=%s, type=%s, semester=%s, color=%s, quota=%d, class_level=%s, is_common=%d WHERE subjectID=%d ",$subjectName,$subjectType,$semester,$color,$quota,$class_level,$is_common,$subjectID);
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

//ajax response delete subject
add_action('wp_ajax_utt_delete_subject', 'utt_delete_subject');
function utt_delete_subject(){
    global $wpdb;
    $subjectsTable=$wpdb->prefix."utt_subjects";
    $safeSql = $wpdb->prepare("DELETE FROM $subjectsTable WHERE subjectID=%d",$_GET['subject_id']);
    $success = $wpdb->query($safeSql);
    //if success is 1, delete succeeded
    echo $success;
    die();
}

//ajax response view subjects
add_action('wp_ajax_utt_view_subjects','utt_view_subjects');
function utt_view_subjects(){
    global $wpdb;
	global $departments;
    $subjectsTable = $wpdb->prefix."utt_subjects";
    $groupsTable = $wpdb->prefix."utt_groups";
	$periodsTable=$wpdb->prefix."utt_periods";
	
    if(isset($_GET['semester'])){
        $semester = $_GET['semester'];
    }else{
        $semester = 0;   
    }
    //if not selected semester, view all subjects
    if($semester==0 || $semester=="")
	{
		//debug
		//$wpdb->suppress_errors = false;
		//$wpdb->show_errors = true;
		
        $subjects = $wpdb->get_results("SELECT S.class_level class_level, S.is_common is_common, S.quota quota, S.subjectID subjectID, S.semester semester, S.color color, S.title title, S.type type, G.periodID periodID, P.period period FROM $subjectsTable S LEFT JOIN $groupsTable G ON G.subjectID=S.subjectID LEFT JOIN $periodsTable P ON G.periodID=P.periodID GROUP BY S.subjectID ORDER BY S.title, S.type, G.groupID, -P.periodID");
    //view filtered subjects
    //echo "{".$wpdb->print_error()."}";
    
    }
	else
	{
        $safeSql = $wpdb->prepare("SELECT S.class_level class_level, S.is_common is_common, S.quota quota, S.subjectID subjectID, S.semester semester, S.color color, S.title title, S.type type, G.periodID periodID, P.period period FROM $subjectsTable S LEFT JOIN $groupsTable G ON G.subjectID=S.subjectID LEFT JOIN $periodsTable P ON G.periodID=P.periodID WHERE S.semester=%d GROUP BY S.subjectID ORDER BY S.title, S.type, G.groupID, -P.periodID",$semester);
        $subjects = $wpdb->get_results($safeSql);
    }
	//print_r($subjects);//debug
    //show registered subjects
    ?>
        <!-- table with subjects viewed -->
        <table class="widefat bold-th">
            <thead>
                <tr>
                    <th><?php _e("Ders","UniTimetable"); ?></th>
                    <th><?php _e("Kaçıncı Sınıf","UniTimetable"); ?></th>
                    <th><?php _e("Kontenjan","UniTimetable"); ?></th>
                    <th><?php _e("Tip","UniTimetable"); ?></th>
                    <th><?php _e("Bölüm","UniTimetable"); ?></th>
                    <th><?php _e("Dönem","UniTimetable"); ?></th>
                    <?php /*<th><?php _e("Renk","UniTimetable"); ?></th>*/?>
                    <th><?php _e("Eylemler","UniTimetable"); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><?php _e("Ders","UniTimetable"); ?></th>
                    <th><?php _e("Kaçıncı Sınıf","UniTimetable"); ?></th>
                    <th><?php _e("Kontenjan","UniTimetable"); ?></th>
                    <th><?php _e("Tip","UniTimetable"); ?></th>
                    <th><?php _e("Bölüm","UniTimetable"); ?></th>
                    <th><?php _e("Dönem","UniTimetable"); ?></th>
                    <?php /*<th><?php _e("Renk","UniTimetable"); ?></th>*/?>
                    <th><?php _e("Eylemler","UniTimetable"); ?></th>
                </tr>
            </tfoot>
            <tbody>
    <?php
        //show grey and white records in order to be more recognizable
        $bgcolor = 1;
        foreach($subjects as $subject){
            if($bgcolor == 1){
                $addClass = "class='grey'";
                $bgcolor = 2;
            }else{
                $addClass = "class='white'";
                $bgcolor = 1;
            }
            //translate subject type
            if($subject->type == "T"){
                $type = __("T","UniTimetable");
            }else if($subject->type == "L"){
                $type = __("L/U","UniTimetable");
            }else{
                $type = __("PE","UniTimetable");
            }
            //a record
            ////
            $subjectPeriod = "-";
            if($subject->periodID==null)
			{
				$subject->periodID = 0;
			}
			else
			{
				$pSemester = __("Bahar","UniTimetable");
				if($subject->pSemester == "W")
				{
					$pSemester = __("Güz","UniTimetable");
				}
				$subjectPeriod = "".$subject->period."";
			}
			
			$iscommontext = "";
			if($subject->is_common == 1)
			{
			    $iscommontext = "<b>[Ortak Ders]</b>";
			}
			//<td><span style='background-color:#$subject->color'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> $subject->color</td>
			
			$classlevelecho = $subject->class_level;
			if ($subject->class_level==11)
		    {
		        $classlevelecho =  "Yüksek Lisans";
		    }
		    else
		    {
		        if ($subject->class_level==21)
		        {
		            $classlevelecho =  "Doktora";
		        }
		    }
			 
			 
            echo "<tr id='$subject->subjectID' $addClass>
			<td>$subject->title</td>
			<td>$classlevelecho</td>
			<td>$subject->quota</td>
			<td>$type</td>
			<td>".$departments[$subject->semester-1]." ".$iscommontext."</td>
			<td>".$subjectPeriod."</td>
			
            <td><a href='#' onclick='deleteSubject($subject->subjectID);' class='deleteSubject'><img id='edit-delete-icon' src='".plugins_url('icons/delete_icon.png', __FILE__)."'/> ".__("Sil","UniTimetable")."</a>&nbsp;
                <a href='#' onclick=\"editSubject($subject->subjectID, '$subject->title', '$subject->type', $subject->semester, '$subject->color',$subject->quota,$subject->class_level,$subject->is_common);\" class='editSubject'><img id='edit-delete-icon' src='".plugins_url('icons/edit_icon.png', __FILE__)."'/> ".__("Düzenle","UniTimetable")."</a></td></tr>";
        }
    ?>
            </tbody>
        </table>
    <?php
    die();
}
?>