<?php

/*
Plugin Name: UniTimetable
Plugin URI: 
Description: A plugin to be used by an Educational Institute, in order to store information about the timetable of a department.
Version: 1.1
Author: Fotis Kokkoras, Antonis Roussos
Author URI: https://www.linkedin.com/pub/antonis-roussos/47/25b/9a5
License: GPLv2
*/

//edit2
//include ([^aeıioöuü]+)
//exclude ([aeıioöuü]+)
//([aeıioöuü]+)

$departments = [];
$departmentusers = [];
$departments_colors = [];
$departments_short = [];

//$wpdb->suppress_errors = false;
//$wpdb->show_errors = true;

$departmentsTable = $wpdb->prefix."utt_departments";
$departments_q = $wpdb->get_results("SELECT * FROM $departmentsTable");

//echo "{".$wpdb->print_error()."}";

foreach($departments_q as $depart)
{
    $depsuffix = "";
    if($depart->programLevel == 1)
    {
        $depsuffix = "(YÜKSEK LİSANS PROG.)";
    }
    else if($depart->programLevel == 2)
    {
        $depsuffix = "(DOKTORA PROG.)";
    }
    array_push($departments, $depart->name." ".$depsuffix);
    array_push($departmentusers, $depart->authUserID);
    array_push($departments_colors, $depart->color);
    
    $dn_output = preg_replace( '([aeıioöuü]+)', '', $depart->name." ".$depsuffix);
    array_push($departments_short, $dn_output);
    
    //echo $depart->authUserID.";";
}



/*
$subjectsTable = $wpdb->prefix."utt_subjects";
$subjects_q = $wpdb->get_results("SELECT * FROM $subjectsTable");

foreach($subjects_q as $subj)
{
    $getid = $subj->subjectID;
    $getcode = $subj->title;
    
    preg_match('/\d+/',$getcode,$matches);
    $lvl = intval($matches[0]);
    $classLevel=''.$lvl.'';
    $classLevel = substr($classLevel,0,1);
    
    echo $getid." @ ".$getcode." -> ".$classLevel;

    
    $safeSql = $wpdb->prepare("UPDATE $subjectsTable SET class_level=%d WHERE subjectID=%d;",$classLevel,$getid);
    $affectedRows = $wpdb->query($safeSql);
}
*/
//SELECT * FROM `wp_mdbfutt_subjects` WHERE title LIKE "%MECHT0517%"
// SELECT * FROM `wp_mdbfutt_subjects` WHERE title LIKE "%MECHT0520%" AND semester IN (25)

				
/*

$departments = [
    "Bilgisayar Mühendisliği (Türkçe)",
    "Bilgisayar Mühendisliği (İngilizce, ücretli)",

    "Biyomühendislik",

    "Çevre Mühendisliği (Türkçe)",

    "Elektrik Elektronik Mühendisliği (Türkçe)",
    "Elektrik Elektronik Mühendisliği (Özel öğrenci)",

    "Endüstri Mühendisliği (Türkçe)",
    "Endüstri Mühendisliği (%100 İngilizce, özel öğrenci)",
    "Endüstri Mühendisliği (Türkçe, özel öğrenci)",

"Fizik",

    "Gıda Mühendisliği",

    "İnşaat Mühendisliği (Türkçe)",
    "Kimya (%30 İngilizce)",
    "İnşaat Mühendisliği (%100 İngilizce, Ücretli)",

"Kimya",

    "Kimya Mühendisliği",

    "Lif ve Polimer Mühendisliği (%30 İngilizce)",

    "Makine Mühendisliği (%30 İngilizce, sep öncesi) ",
    "Makine Mühendisliği (%30 İngilizce, sep)",
    "Makine Mühendisliği (Türkçe, özel öğrenci)",
    "Makine Mühendisliği (%100 İngilizce, özel öğrenci)",
    "Makine Mühendisliği (2+2 uolp)",
    "Makine Mühendisliği (Mühendislik tamamlama)",

    "Matematik",

    "Mekatronik Mühendisliği (%30 İngilizce)",

    "Metalürji ve Malzeme Mühendisliği (sep öncesi)",
    "Metalürji ve Malzeme Mühendisliği (sep)",
	
	
];






$departmentusers = [
    8,
    8,

    9,

    10,

    11,
    11,

    13,
    13,
    13,

7,

    14,

    15,
    16,
    15,

16,

    17,

    18,

    19,
    19,
    19,
    19,
    19,
    19,

    20,

    21,

    22,
    22,
	
	
];




$departments_colors = [
"E78C8C",
"732A2A",

"F290D8",

"C582E1",

"9E71DD",
"4F2D80",

"9097E3",
"5C63B1",
"2A3074",

"8EC9E8",

"73DBD8",

"82D2B7",
"529B82",
"2B624F",

"93D77F",

"C7DB82",

"CD48F0",

"E0BB90",
"A68157",
"6D4F2B",
"38240E",
"593105",
"965002",

"D6006F",

"2900D4",

"08B300",
"0CF502"
];
*/
$onlyallowedscanmodify = false;
$alloweds = [
    "bmadmin"
    ];//"bmadmin", 
//, "btumak", "btuins", "btumat"

$usersotherthanadminscanviewthecalendar=true;

function IsAdmin()
{
	global $wpdb;
	$curuserisadmin = false;
	if( current_user_can('editor') || current_user_can('administrator') )
	{
		$curuserisadmin = true;
	}
	return $curuserisadmin;
}

function SbjType($typ)
{
    if($typ=="L")
    {
        return "Lab/Uyg";
    }
    else if($typ=="T")
    {
        return "Teori";
    }
    else
    {
        return $typ;
    }
}




/*
//old edit
$departments = [
"Bilgisayar Mühendisliği",
"Biyomühendislik",
"Çevre Mühendisliği",
"Elektrik Elektronik Mühendisliği",
"Endüstri Mühendisliği",
"Fizik",
"Gıda Mühendisliği",
"İnşaat Mühendisliği",
"Kimya",
"Kimya Mühendisliği",
"Lif ve Polimer Mühendisliği",
"Makine Mühendisliği",
"Matematik",
"Mekatronik Mühendisliği",
"Metalürji ve Malzeme Mühendisliği"
];

$departments_colors = [
"E78C8C",
"F290D8",
"C582E1",
"9E71DD",
"9097E3",
"8EC9E8",
"73DBD8",
"82D2B7",
"93D77F",
"C7DB82",
"CD48F0",
"E0BB90",
"D6006F",
"2900D4",
"08B300"
];
*/
/*
Renk paleti
1)
E78C8C,AD4E4E,732A2A,451111

2)
F290D8,B65A9E,81326D,501841

3)
C582E1,8747A2,5D2973,331241

4)
9E71DD,774DB2,4F2D80,271245

5)
9097E3,5C63B1,2A3074,13184A

6)
8EC9E8,498BAD,225672,0E2D3D

7)
73DBD8,39A6A3,207674,0A3A39

8)
82D2B7,529B82,2B624F,103126

9)
93D77F,629B51,39672C,193B0F

10)
C7DB82,91A54A,566521,2E370D

11)
CD48F,9F9851,6F692B,3F3A12

12)
E0BB90,A68157,6D4F2B,38240E

13)
D6006F,FF0787,FF60B2,FFABD7

14)
2900D4,5B35FF,9278FF,B5A3FF

15)
08B300,0CF502,78FF72,B7FFB4

*/


//register utt_activate function to run when user activates the plugin
register_activation_hook( __FILE__, 'utt_activate' );
//create tables and view for UniTimetable plugin to the Wordpress Database
function utt_activate(){
    //require upgrade.php so that we can use dbDelta function
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    global $wpdb;
    //set table names
    $periodsTable=$wpdb->prefix."utt_periods";
    $subjectsTable=$wpdb->prefix."utt_subjects";
    $groupsTable=$wpdb->prefix."utt_groups";
    $teachersTable=$wpdb->prefix."utt_teachers";
    $classroomsTable=$wpdb->prefix."utt_classrooms";
    $lecturesTable=$wpdb->prefix."utt_lectures";
    $holidaysTable=$wpdb->prefix."utt_holidays";
    $eventsTable=$wpdb->prefix."utt_events";
    $lecturesView=$wpdb->prefix."utt_lectures_view";
    $charset_collate = $wpdb->get_charset_collate();
    
    //create utt tables
    $sql = "CREATE TABLE IF NOT EXISTS `$periodsTable` (
            periodID int UNSIGNED NOT NULL AUTO_INCREMENT,
            year year NOT NULL COMMENT 'year - this way we can keep history',
            semester varchar(45) NOT NULL COMMENT 'Summer, Winter',
            PRIMARY  KEY (periodID),
            UNIQUE KEY `unique_period` (year ASC, semester ASC))
            ENGINE = InnoDB
            $charset_collate;";
    dbDelta($sql);
    
    $sql="CREATE TABLE IF NOT EXISTS `$subjectsTable` (
            subjectID int UNSIGNED NOT NULL AUTO_INCREMENT,
            title varchar(64) NOT NULL COMMENT 'Subject\' s official Name',
            type varchar(45) NOT NULL COMMENT 'Subject type ex. Theory, Lab, Practice Exercises etc.',
            semester tinyint UNSIGNED NOT NULL COMMENT 'semester where the subject belongs',
            is_enabled tinyint(1) NOT NULL DEFAULT 1 COMMENT 'if the subject is active - application will show only active subjects',
            color varchar(45) NOT NULL COMMENT 'color shown in the curriculum',
            PRIMARY KEY  (subjectID),
            UNIQUE KEY `unique_subject` (title ASC, type ASC))
            ENGINE = InnoDB
            $charset_collate;";
    dbDelta($sql);
    
    $sql="CREATE TABLE IF NOT EXISTS `$groupsTable` (
            groupID int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'unique - for use in the Lectures table',
            periodID int UNSIGNED NOT NULL COMMENT 'FKey from Periods',
            subjectID int UNSIGNED NOT NULL COMMENT 'FKey from Subjects',
            groupName varchar(30) NOT NULL COMMENT 'name of the group',
            PRIMARY KEY  (periodID, subjectID, groupName),
            KEY `fk_Groups_Periods_idx` (periodID ASC),
            KEY `fk_Groups_Subject1_idx` (subjectID ASC),
            UNIQUE KEY `groupID_UNIQUE` (groupID ASC),
            CONSTRAINT `fk_Groups_Periods`
            FOREIGN KEY (periodID)
            REFERENCES `$periodsTable` (periodID)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,
            CONSTRAINT `fk_Groups_Subjects`
            FOREIGN KEY (subjectID)
            REFERENCES `$subjectsTable` (subjectID)
            ON DELETE RESTRICT
            ON UPDATE CASCADE)
            ENGINE = InnoDB
            $charset_collate;";
    dbDelta($sql);
    
    $sql="CREATE TABLE IF NOT EXISTS `$teachersTable` (
            teacherID smallint UNSIGNED NOT NULL AUTO_INCREMENT,
            surname varchar(35) NOT NULL COMMENT 'teacher\'s surname',
            name varchar(35) NULL COMMENT 'teacher\'s name',
            PRIMARY KEY  (teacherID),
            UNIQUE KEY `unique_teacher` (surname ASC, name ASC))
            ENGINE = InnoDB
            $charset_collate;";
    dbDelta($sql);
    
    $sql="CREATE TABLE IF NOT EXISTS `$classroomsTable` (
            classroomID smallint UNSIGNED NOT NULL AUTO_INCREMENT,
            name varchar(35) NOT NULL COMMENT 'name of the classroom',
            type varchar(45) NOT NULL COMMENT 'Lecture classroom, Laboratory',
            is_available tinyint(1) NOT NULL DEFAULT 1 COMMENT 'if classroom is available',
            PRIMARY KEY  (classroomID),
            UNIQUE KEY `unique_classroom` (name ASC))
            ENGINE = InnoDB
            $charset_collate;";
    dbDelta($sql);
    //edit
    $sql="CREATE TABLE IF NOT EXISTS `$lecturesTable` (
            lectureID int UNSIGNED NOT NULL AUTO_INCREMENT,
            groupID int UNSIGNED NOT NULL COMMENT 'FKey from Groups',
            classroomID smallint UNSIGNED NOT NULL COMMENT 'FKey from Classrooms',
            teacherID smallint UNSIGNED NOT NULL COMMENT 'FKey from Teachers',
            start datetime NOT NULL COMMENT 'date-time when the event starts',
            end datetime NOT NULL COMMENT 'date-time when the event ends',
            description varchar(250) NOT NULL default '',
            KEY `fk_Lecture_Classrooms1_idx` (classroomID ASC),
            KEY `fk_Lecture_Teachers1_idx` (teacherID ASC),
            PRIMARY KEY  (lectureID),
            CONSTRAINT `fk_Lectures_Classrooms`
            FOREIGN KEY (classroomID)
            REFERENCES `$classroomsTable` (classroomID)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,
            CONSTRAINT `fk_Lectures_Teachers`
            FOREIGN KEY (teacherID)
            REFERENCES `$teachersTable` (teacherID)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,
            CONSTRAINT `fk_Lectures_Groups1`
            FOREIGN KEY (groupID)
            REFERENCES `$groupsTable` (groupID)
            ON DELETE RESTRICT
            ON UPDATE CASCADE)
            ENGINE = InnoDB
            $charset_collate;";

    dbDelta($sql);
    
    $sql="CREATE TABLE IF NOT EXISTS `$holidaysTable` (
            holidayDate date NOT NULL COMMENT 'Date of the holiday',
            holidayName varchar(45) NOT NULL COMMENT 'name of the Holiday',
            PRIMARY KEY  (holidayDate))
            ENGINE = InnoDB
            $charset_collate;";
    dbDelta($sql);
    
    $sql="CREATE TABLE IF NOT EXISTS `$eventsTable` (
            eventID int UNSIGNED NOT NULL AUTO_INCREMENT,
            eventType varchar(45) NOT NULL COMMENT 'type of the event',
            eventTitle varchar(45) NOT NULL COMMENT 'title of the event',
            eventDescr varchar(255) NULL COMMENT 'description of the event',
            classroomID smallint UNSIGNED NOT NULL COMMENT 'Fkey from classrooms',
            eventStart datetime NOT NULL COMMENT 'Date-time when the event starts',
            eventEnd datetime NOT NULL COMMENT 'date-time when the event ends',
            PRIMARY KEY  (eventID),
            KEY `fk_wp_utt_events_wp_utt_classrooms1_idx` (classroomID ASC),
            CONSTRAINT `fk_wp_utt_events_wp_utt_classrooms1`
            FOREIGN KEY (classroomID)
            REFERENCES `$classroomsTable` (classroomID)
            ON DELETE RESTRICT
            ON UPDATE CASCADE)
            ENGINE = InnoDB
            $charset_collate;";
    dbDelta($sql);
    
    //create view
    //edit
    $wpdb->query("CREATE  OR REPLACE VIEW $lecturesView AS
            SELECT
                periodID,
                lectureID,
                semester,
                $lecturesTable.groupID,
                $lecturesTable.classroomID,
                $lecturesTable.teacherID,
                start,
                end,
				$lecturesTable.description AS description,
                groupName,
                $subjectsTable.subjectID,
                $subjectsTable.title AS subjectTitle,
                $subjectsTable.type AS subjectType,
                color,
                $classroomsTable.name AS classroomName,
                $classroomsTable.type AS classroomType,
                surname as teacherSurname,
                $teachersTable.name as teacherName
            FROM
                $lecturesTable,
                $groupsTable,
                $subjectsTable,
                $classroomsTable,
                $teachersTable
            WHERE
                $lecturesTable.groupID = $groupsTable.groupID
                    AND $groupsTable.subjectID = $subjectsTable.subjectID
                    AND $lecturesTable.classroomID = $classroomsTable.classroomID
                    AND $lecturesTable.teacherID = $teachersTable.teacherID;");

}

//register utt_deactivate to run when plugin is deactivated
register_deactivation_hook( __FILE__, 'utt_deactivate' );
//do nothing when plugin deactivates
function utt_deactivate(){
    
}

//register utt_uninstall to run when plugin is uninstalled/deleted
register_uninstall_hook( __FILE__, 'utt_uninstall' );
//delete tables and view on deletion of plugin
function utt_uninstall(){
    global $wpdb;
    //set table names
    $periodsTable=$wpdb->prefix."utt_periods";
    $subjectsTable=$wpdb->prefix."utt_subjects";
    $groupsTable=$wpdb->prefix."utt_groups";
    $teachersTable=$wpdb->prefix."utt_teachers";
    $classroomsTable=$wpdb->prefix."utt_classrooms";
    $lecturesTable=$wpdb->prefix."utt_lectures";
    $holidaysTable=$wpdb->prefix."utt_holidays";
    $eventsTable=$wpdb->prefix."utt_events";
    $lecturesView=$wpdb->prefix."utt_lectures_view";
    //drop view
    $sql = "DROP VIEW IF EXISTS `$lecturesView` ;";
    $wpdb->query($sql);
    //drop tables
    $sql = "DROP TABLE IF EXISTS `$eventsTable` ;";
    $wpdb->query($sql);
    
    $sql = "DROP TABLE IF EXISTS `$lecturesTable` ;";
    $wpdb->query($sql);
        
    $sql="DROP TABLE IF EXISTS `$groupsTable` ;";
    $wpdb->query($sql);

    $sql="DROP TABLE IF EXISTS `$periodsTable` ;";
    $wpdb->query($sql);
        
    $sql="DROP TABLE IF EXISTS `$subjectsTable` ;";
    $wpdb->query($sql);
        
    $sql="DROP TABLE IF EXISTS `$classroomsTable` ;";
    $wpdb->query($sql);

    $sql="DROP TABLE IF EXISTS `$teachersTable` ;";
    $wpdb->query($sql);
    
    $sql="DROP TABLE IF EXISTS `$holidaysTable` ;";
    $wpdb->query($sql);
}

//register utt_load languages on init hook
add_action('init','utt_load_languages');
//load translation files
function utt_load_languages(){
    load_plugin_textdomain('UniTimetable', false, 'UniTimetable/languages');
}
//register utt_UniTimetableMenu_create
add_action('admin_menu','utt_UniTimetableMenu_create');
//Create Menu-Submenus
function utt_UniTimetableMenu_create(){
	global $usersotherthanadminscanviewthecalendar;
	
    //load utt_style.css on every plugin page
    wp_enqueue_style( 'utt_style',  plugins_url('css/utt_style.css', __FILE__) );
    //add main page of plugin
    add_menu_page('UniTimeTable','Ders Programı Takvim Uygulaması','manage_options',__FILE__,'utt_UniTimetable_page' );
    
    //add submenu pages to UniTimetable menu
    $teachersPage = add_submenu_page( __FILE__, __("Öğretim Üyesi Ekle","UniTimetable"), __("Öğretim Üyeleri","UniTimetable"), 'manage_options',__FILE__.'_teachers', 'utt_create_teachers_page' );
    add_action('load-'.$teachersPage, 'utt_teacher_scripts');
    
	
	if(IsAdmin())
	{
        $periodsPage = add_submenu_page( __FILE__, __("Dönem Ekle","UniTimetable"), __("Dönemler","UniTimetable"), 'manage_options',__FILE__.'_periods', 'utt_create_periods_page' );
        add_action('load-'.$periodsPage, 'utt_period_scripts');
	}
	
    
    $subjectsPage = add_submenu_page( __FILE__, __("Ders Ekle","UniTimetable"), __("Ders Tanımlama","UniTimetable"), 'manage_options',__FILE__.'_subjects', 'utt_create_subjects_page' );
    add_action('load-'.$subjectsPage, 'utt_subject_scripts');
    
	
    $groupsPage = add_submenu_page( __FILE__, __("Şube Ekle","UniTimetable"), __("Şubeler","UniTimetable"), 'manage_options',__FILE__.'_groups', 'utt_create_groups_page' );
    add_action('load-'.$groupsPage, 'utt_group_scripts');

    	
    $jointsPage = add_submenu_page( __FILE__, __("Ders Birleştirme","UniTimetable"), __("Ders Birleştirme","UniTimetable"), 'manage_options',__FILE__.'_joints', 'utt_create_joints_page' );
    add_action('load-'.$jointsPage, 'utt_joint_scripts');
	
	
	//error_reporting(E_ALL);
    // $sharedgroupsPage = add_submenu_page( __FILE__, __("Paylaşılan Şubeler","UniTimetable"), __("Paylaşılan Şubeler","UniTimetable"), 'manage_options',__FILE__.'_sharedgroups', 'utt_create_sharedgroups_page' );
    // add_action('load-'.$sharedgroupsPage, 'utt_sharedgroup_scripts');
	
	
    
	if(IsAdmin())
	{
    	$classroomsPage = add_submenu_page( __FILE__, __("Sınıf Ekle","UniTimetable"), __("Sınıflar","UniTimetable"), 'manage_options',__FILE__.'_classrooms', 'utt_create_classrooms_page' );
    	add_action('load-'.$classroomsPage, 'utt_classroom_scripts');
	}
	
	/*
    $holidaysPage = add_submenu_page( __FILE__, __("Tail Ekle","UniTimetable"), __("Tatiller","UniTimetable"), 'manage_options',__FILE__.'_holidays', 'utt_create_holidays_page' );
    add_action('load-'.$holidaysPage, 'utt_holiday_scripts');
    
    $eventsPage = add_submenu_page( __FILE__, __("Etkinlik Ekle","UniTimetable"), __("Etkinlikler","UniTimetable"), 'manage_options',__FILE__.'_events', 'utt_create_events_page' );
    add_action('load-'.$eventsPage, 'utt_event_scripts');
    */
	
	if($usersotherthanadminscanviewthecalendar || IsAdmin())
	{
    	$lecturesPage = add_submenu_page( __FILE__, __("Çizelge Ekle","UniTimetable"), __("Takvim","UniTimetable"), 'manage_options',__FILE__.'_lectures', 'utt_create_lectures_page' );
    	add_action('load-'.$lecturesPage, 'utt_lecture_scripts');
	}
	
}

//load main utt page
function utt_UniTimetable_page(){
    global $wpdb;
	
	global $onlyallowedscanmodify;
	global $alloweds;
	
	global $departments,$departmentusers,$departments_colors;
	
	
	
	$current_user = wp_get_current_user();
    $current_user_id = get_current_user_id();
	
    //set table names
    $periodsTable=$wpdb->prefix."utt_periods";
    $subjectsTable=$wpdb->prefix."utt_subjects";
    $groupsTable=$wpdb->prefix."utt_groups";
    $teachersTable=$wpdb->prefix."utt_teachers";
    $classroomsTable=$wpdb->prefix."utt_classrooms";
    
    $jointsTable=$wpdb->prefix."utt_joints";
    $sharedgroupsTable=$wpdb->prefix."utt_sharedgroups";

    $lecturesTable=$wpdb->prefix."utt_lectures";
    $holidaysTable=$wpdb->prefix."utt_holidays";
    $eventsTable=$wpdb->prefix."utt_events";
    ?>
    <div class="wrap">
        <h2><?php _e("BTU Mühendislik ve Doğa Bilimleri Fakültesi","UniTimetable"); ?></h2>
        <h3><?php _e("Ders Programı Takvim Uygulamasına Hoşgeldiniz","UniTimetable"); ?></h3>
        <p>
			<?php
	
				
				if( current_user_can('editor') || current_user_can('administrator') )
				{
					$welcometxt = "Yönetici olarak giriş yaptınız.";
				}
				else
				{
					$welcometxt = "Komisyon üyesi olarak giriş yaptınız.";
				}
				_e("<h3>".$welcometxt." (".$current_user->user_login." #".$current_user_id.")</h3>","UniTimetable");
	
				
				if($onlyallowedscanmodify)
				{
					if(in_array($current_user->user_login,$alloweds))
					{
						_e("<h3>Programda değişiklik yapma izniniz var.</h3>","UniTimetable");
						$i = 0;
						foreach($departments as $dep)
						{
						    if($departmentusers[$i] == $current_user_id || IsAdmin())
						    {
						        _e("<p>".$dep."</p>","UniTimetable");
						    }
						    $i++;
						}
					}
					else
					{
						_e("<h3>Bölümünüz programda değişiklik yapmaya kapatılmıştır.</h3>","UniTimetable");
					}
				}
				
				
				_e("<br><h3>Üzerinde yetkinizin olduğu müfredatlar:</h3>","UniTimetable");
				$i = 0;
				foreach($departments as $dep)
				{
				    if($departmentusers[$i] == $current_user_id || IsAdmin())
				    {
				        _e("<p>".$dep."</p>","UniTimetable");//" -".$departments_short[$i].
				    }
				    $i++;
				}

			?>
        </p>
		
        <h3><?php
        
        include_once "autoscr.php";
        
        
      /*  
$deps = [
    "Akıllı Sistemler Mühendisliği",
"Bilgisayar Mühendisliği",
"Biyokompozit Mühendisliği",
"Biyoteknoloji",
"Çevre Mühendisliği",
"Elektrik-Elektronik Mühendisliği",
"Enerji Sistemleri Mühendisliği",
"Fizik",
"Gıda Mühendisliği",
"İleri Teknolojiler- Malzeme Bilimi ve Mühendisliği",
"İnşaat Mühendisliği",
"İş Sağlığı ve Güvenliği (Tezsiz, İÖ)",
"Kent Ormancılığı",
"Kentsel Tasarım",
"Kimya",
"Kimya Mühendisliği",
"Lif ve Polimer Mühendisliği",
"Makine Mühendisliği",
"Mekatronik Mühendisliği (%30 İngilizce)",
"Metalurji ve Malzeme Mühendisliği",
"Orman Endüstri Mühendisliği",
"Orman Mühendisliği",
"Orman Mühendisliği (İngilizce)",
"Orman Ürünleri",
"Orman Ürünleri (İngilizce)",
"Peyzaj Mimarlığı",
"Şehir Planlama"
    ];


$departmentsTable = $wpdb->prefix."utt_departments";
$wpdb->query('START TRANSACTION');

foreach ($deps as $dep)
{
    $safeSql = $wpdb->prepare("INSERT INTO $departmentsTable (name,programLevel) VALUES (%s,%d)",$dep,1);
    $success = $wpdb->query($safeSql);
}

$wpdb->query('COMMIT');
*/

/*    
$deps = [
"Biyoteknoloji",
"Çevre Mühendisliği",
"Elektrik-Elektronik Mühendisliği",
"Enerji Sistemleri Mühendisliği",
"İleri Teknolojiler- Malzeme Bilimi ve Mühendisliği",
"İnşaat Mühendisliği",
"Kimya",
"Kimya Mühendisliği",
"Lif ve Polimer Mühendisliği (%30 İngilizce)",
"Makine Mühendisliği",
"Mekatronik Mühendisliği (%30 İngilizce)",
"Orman Endüstri Mühendisliği",
"Orman Mühendisliği"
    ];


$departmentsTable = $wpdb->prefix."utt_departments";
$wpdb->query('START TRANSACTION');

foreach ($deps as $dep)
{
    $safeSql = $wpdb->prepare("INSERT INTO $departmentsTable (name,programLevel) VALUES (%s,%d)",$dep,2);
    $success = $wpdb->query($safeSql);
}

$wpdb->query('COMMIT');
*/




	/*
        //show database records
        _e("Kayıtlar","UniTimetable"); ?>
		</h3>
        <?php $teachers = $wpdb->get_row("SELECT count(*) as counter FROM $teachersTable;") ?>
        <?php _e("Öğretim Üyeleri:","UniTimetable"); echo " ".$teachers->counter." "; _e("Kayıt","UniTimetable"); ?><br/>
        <?php $periods = $wpdb->get_row("SELECT count(*) as counter FROM $periodsTable;") ?>
        <?php _e("Dönemler:","UniTimetable"); echo " ".$periods->counter." "; _e("Kayıt","UniTimetable"); ?><br/>
        <?php $subjects = $wpdb->get_row("SELECT count(*) as counter FROM $subjectsTable;") ?>
        <?php _e("Dersler:","UniTimetable"); echo " ".$subjects->counter." "; _e("Kayıt","UniTimetable"); ?><br/>
        <?php $classrooms = $wpdb->get_row("SELECT count(*) as counter FROM $classroomsTable;") ?>
        <?php _e("Sınıflar:","UniTimetable"); echo " ".$classrooms->counter." "; _e("Kayıt","UniTimetable"); ?><br/>
        <?php $groups = $wpdb->get_row("SELECT count(*) as counter FROM $groupsTable;") ?>
        <?php _e("Gruplar:","UniTimetable"); echo " ".$groups->counter." "; _e("Kayıt","UniTimetable"); ?><br/>
        <?php $holidays = $wpdb->get_row("SELECT count(*) as counter FROM $holidaysTable;") ?>
        <?php _e("Tatiller:","UniTimetable"); echo " ".$holidays->counter." "; _e("Öğretim üyesi düzenlenemedi, halihazırda bu isimde bir öğretim üyesi var mı ?","UniTimetable"); ?><br/>
        <?php $events = $wpdb->get_row("SELECT count(*) as counter FROM $eventsTable;") ?>
        <?php _e("Olaylar:","UniTimetable"); echo " ".$events->counter." "; _e("Öğretim üyesi düzenlenemedi, halihazırda bu isimde bir öğretim üyesi var mı ?","UniTimetable"); ?><br/>
        <?php $lectures = $wpdb->get_row("SELECT count(*) as counter FROM $lecturesTable;") ?>
        <?php _e("Çizelge:","UniTimetable"); echo " ".$lectures->counter." "; _e("Öğretim üyesi düzenlenemedi, halihazırda bu isimde bir öğretim üyesi var mı ?","UniTimetable"); ?>
    </div>
    <?php
	*/
}
//require external php files
require('teachersFunctions.php');
require('periodsFunctions.php');
require('subjectsFunctions.php');
require('classroomsFunctions.php');
require('groupsFunctions.php');
require('holidaysFunctions.php');

require('jointsFunctions.php');
require('sharedgroupsFunctions.php');

require('lecturesFunctions.php');
require('eventsFunctions.php');
require('calendarShortcode.php');
?>