<?php

$runautoscr = 0;


/*
SELECT subjectID, title, REPLACE(title,SUBSTRING_INDEX(title,' ',1),'') FROM `wp_mdbfutt_subjects` WHERE REPLACE(title,CONCAT(SUBSTRING_INDEX(title,' ',1),'',' '),'') LIKE 'Elektronik I'


SELECT subjectID, title, REPLACE(title,SUBSTRING_INDEX(title,' ',1),'') FROM `wp_mdbfutt_subjects`

SELECT subjectID, title FROM `wp_mdbfutt_subjects` WHERE REPLACE(title,SUBSTRING_INDEX(title,' ',1),'') = 'Fizik I'


*/



function tr_strtoupper($text)
{
    $search=array("ç","i","ı","ğ","ö","ş","ü");
    $replace=array("Ç","İ","I","Ğ","Ö","Ş","Ü");
    $text=str_replace($search,$replace,$text);
    $text=strtoupper($text);
    return $text;
}

function formdate_to_dbdate($datestr)
{
    //"Salı 10:00-12:45";
    //"2019-12-10 10:00","2019-12-10 12:45";
    
    $find = array(
        "Pazartesi",
        "Salı",
        "Çarşamba",
        "Perşembe",
        "Cumartesi",
        "Cuma"
    );
    $repl = array(
        "09/12/2019",
        "10/12/2019",
        "11/12/2019",
        "12/12/2019",
        "14/12/2019",
        "13/12/2019"
    );
    
    $datestr = str_replace($find,$repl,$datestr);
    $datestr_split = explode(" ",$datestr);
    $datestr_split2 = explode("-",$datestr_split[1]);
    
    $date_part = $datestr_split[0];
    $date_part_splite = explode("/",$date_part);
    
    $usedDate = $date_part_splite[2]."-".$date_part_splite[1]."-".$date_part_splite[0];
    
    
    $startTime = $usedDate." ".$datestr_split2[0];
    $endTime = $usedDate." ".$datestr_split2[1];
    
    return array($startTime,$endTime);
    
    
}










//GÜZ 2020-2021



$periodID = 9;


//TITLE | T - U | Bölüm IDleri | Sınıf
//TITLE | T - U | Bölüm IDleri | Sınıf
//TITLE | T - U | Bölüm IDleri | Sınıf | Hoca | Ders Tarihi 1 | Ders Tarihi 2
//$csvcontent = "
//KİM0297 Organik Kimya|T|11,17,16|2|Ömer Koz|Salı 15:00-17:45|Çarşamba 15:00-17:45
//";


// TITLE | T - U | Bölüm IDleri | Sınıf | Hoca | Ders Tarihi 1 | Ders Tarihi 2
//Title |	T / L |	Department Ids |	Level |	Teacher |	Date1 |	Date2 |	GroupName |	ClassroomID

$csvcontent = "
FZK0101 Fizik I|T|3|1|Murat Türemiş|Çarşamba 8:00-11:00||A1|278
FZK0101 Fizik I|L|3|1|Murat Türemiş|Perşembe 15:00-17:00||A1|278
FZK0101 Fizik I|T|4|1|Mehmet Büyükyıldız|Cuma 14:00-17:00||A2|278
FZK0101 Fizik I|L|4|1|Mehmet Büyükyıldız|Perşembe 13:00-15:00||A2|278
FZK0101 Fizik I|T|11|1|Emrah Sarıca|Cuma 14:00-17:00||A3|278
FZK0101 Fizik I|L|11|1|Emrah Sarıca|Çarşamba 13:00-15:00||A3|278
FZK0101 Fizik I|T|15|1|Murat Türemiş|Pazartesi 13:00-16:00||A4|278
FZK0101 Fizik I|L|15|1|Murat Türemiş|Salı 13:00-15:00||A4|278
FZK0101 Fizik I|T|16|1|Songül Akbulut Özen|Çarşamba 8:00-11:00||A5|278
FZK0101 Fizik I|L|16|1|Songül Akbulut Özen|Çarşamba 15:00-17:00||A5|278
FZK0101 Fizik I|T|26,27|1|Hüseyin Dağ|Pazartesi 13:00-16:00||A6|278
FZK0101 Fizik I|L|26,27|1|Hüseyin Dağ|Çarşamba 10:00-12:00||A6|278
FZK0101 Fizik I|T|17|1|Murat Türemiş|Pazartesi 13:00-16:00||A4|278
FZK0101 Fizik I|L|17|1|Murat Türemiş|Salı 13:00-15:00||A4|278
FZK0101 Fizik I|T|12,14|1|Zekeriya DOĞRUYOL|Pazartesi 8:00-11:00||B1|278
FZK0101 Fizik I|L|12,14|1|Zekeriya DOĞRUYOL|Çarşamba 8:00-10:00||B1|278
FZK0101 Fizik I|T|18,19,20,21,22,23|1|Zekeriya DOĞRUYOL|Çarşamba 14:00-17:00||B2|278
FZK0101 Fizik I|L|18,19,20,21,22,23|1|Zekeriya DOĞRUYOL|Pazartesi 13:00-15:00||B2|278
FZK0101 Fizik I|T|1,2|1|Murat Türemiş|Perşembe 8:00-11:00||C1|278
FZK0101 Fizik I|L|1,2|1|Murat Türemiş|Salı 15:00-17:00||C1|278
FZK0101 Fizik I|L|1,2|1|Emrah Sarıca|Salı 14:00-17:00||C5|278
FZK0101 Fizik I|L|1,2|1|Emrah Sarıca|Cuma 10:00-12:00||C5|278
FZK0101 Fizik I|T|5,6|1|Aslı Ayten Kaya|Perşembe 8:00-11:00||C3|278
FZK0101 Fizik I|L|5,6|1|Aslı Ayten Kaya|Salı 8:00-10:00||C3|278
FZK0101 Fizik I|T|7,8,9|1|Aslı Ayten Kaya|Çarşamba 8:00-11:00||C2|278
FZK0101 Fizik I|L|7,8,9|1|Aslı Ayten Kaya|Pazartesi 10:00-12:00||C2|278
FZK0101 Fizik I|T|25|1|Hüseyin Dağ|Salı 14:00-17:00||C4|278
FZK0101 Fizik I|L|25|1|Hüseyin Dağ|Perşembe 8:00-10:00||C4|278
FZK0111 Fizik I|T|24|1|Mehmet Büyükyıldız|Çarşamba 8:00-11:00||Şube 1|278
PHY101 Fundamentals of Physics|T|12,14|1|Mehmet Büyükyıldız|Perşembe 8:00-11:00||Şube 1|278
PHY101 Fundamentals of Physics|L|12,14|1|Mehmet Büyükyıldız|Pazartesi 15:00-17:00||Şube 1|278
DDPHY0101 General Physics I|T|18,19,20,21,22,23|1|Hüseyin Dağ|Salı 10:00-12:00||Şube 1|278
DDPHY0101 General Physics I|T|18,19,20,21,22,23|1|Hüseyin Dağ|Çarşamba 13:00-15:00||Şube 1|278
DDPHY0103 General Physics Lab I|L|18,19,20,21,22,23|1|Hüseyin Dağ|Pazartesi 8:00-10:00||Şube 1|278
MAT0101 Matematik I|T|3|1|Bahar ARSLAN|Pazartesi 8:00-10:00||A1|278
MAT0101 Matematik I|T|3|1|Bahar ARSLAN|Çarşamba 13:00-15:00||A1|278
MAT0101 Matematik I|T|4|1|İbrahim TEKİN|Salı 10:00-12:00||A2|278
MAT0101 Matematik I|T|4|1|İbrahim TEKİN|Perşembe 15:00-17:00||A2|278
MAT0101 Matematik I|T|11|1|Nil ORHAN ERTAŞ|Salı 10:00-12:00||A3|278
MAT0101 Matematik I|T|11|1|Nil ORHAN ERTAŞ|Perşembe 15:00-17:00||A3|278
MAT0101 Matematik I|T|15|1|İbrahim TEKİN|Perşembe 13:00-15:00||A4|278
MAT0101 Matematik I|T|15|1|İbrahim TEKİN|Cuma 8:00-10:00||A4|278
MAT0101 Matematik I|T|16|1|Yücel ÇENESİZ|Pazartesi 8:00-10:00||A5|278
MAT0101 Matematik I|T|16|1|Yücel ÇENESİZ|Çarşamba 13:00-15:00||A5|278
MAT0101 Matematik I|T|26,27|1|Nihal ÖZDOĞAN|Perşembe 13:00-15:00||A6|278
MAT0101 Matematik I|T|26,27|1|Nihal ÖZDOĞAN|Cuma 8:00-10:00||A6|278
MAT0101 Matematik I|T|17|1|İbrahim TEKİN|Perşembe 13:00-15:00||A4|278
MAT0101 Matematik I|T|17|1|İbrahim TEKİN|Cuma 8:00-10:00||A4|278
MAT0101 Matematik I|T|12,14|1|İrem KÜPELİ ERKEN|Pazartesi 13:00-15:00||B1|278
MAT0101 Matematik I|T|12,14|1|İrem KÜPELİ ERKEN|Perşembe 13:00-15:00||B1|278
MAT0101 Matematik I|T|18,19,20,21,22,23|1|Abdullah MAĞDEN|Salı 8:00-10:00||B2|278
MAT0101 Matematik I|T|18,19,20,21,22,23|1|Abdullah MAĞDEN|Cuma 10:00-12:00||B2|278
MAT0101 Matematik I|T|1,2|1|Nihal ÖZDOĞAN|Salı 8:00-10:00||C1|278
MAT0101 Matematik I|T|1,2|1|Nihal ÖZDOĞAN|Çarşamba 15:00-17:00||C1|278
MAT0101 Matematik I|T|1,2|1|Bahar ARSLAN|Salı 8:00-10:00||C2|278
MAT0101 Matematik I|T|1,2|1|Bahar ARSLAN|Çarşamba 15:00-17:00||C2|278
MAT0101 Matematik I|T|5,6|1|Bahar ARSLAN|Pazartesi 10:00-12:00||C3|278
MAT0101 Matematik I|T|5,6|1|Bahar ARSLAN|Cuma 10:00-12:00||C3|278
MAT0101 Matematik I|T|7,8,9|1|Ayşe BORAT|Salı 8:00-10:00||C2|278
MAT0101 Matematik I|T|7,8,9|1|Ayşe BORAT|Çarşamba 15:00-17:00||C2|278
MAT0101 Matematik I|T|25|1|Burhan ALVEROĞLU|Pazartesi 10:00-12:00||C4|278
MAT0101 Matematik I|T|25|1|Burhan ALVEROĞLU|Cuma 10:00-12:00||C4|278
MAT0103 Lineer Cebir|T|5,6|1|Nihal ÖZDOĞAN|Cuma 14:00-17:00||Şube 1|278
MAT0103 Lineer Cebir|T|1,2|1|Nil ORHAN ERTAŞ|Çarşamba 8:00-11:00||Şube 1|278
MAT0103 Lineer Cebir|T|25|1|Nil ORHAN ERTAŞ|Cuma 14:00-17:00||Şube 1|278
MAT291 Diferansiyel Denklemler|T|1,2|2|Burhan ALVEROĞLU|Pazartesi 8:00-10:00||Şube 1|278
MAT291 Diferansiyel Denklemler|T|1,2|2|Burhan ALVEROĞLU|Cuma 8:00-10:00||Şube 1|278
MAT0291 Diferansiyel Denklemler|T|3,4|2|Nihal ÖZDOĞAN|Çarşamba 10:00-12:00||X3|278
MAT0291 Diferansiyel Denklemler|T|3,4|2|Nihal ÖZDOĞAN|Cuma 10:00-12:00||X3|278
MAT0291 Diferansiyel Denklemler|T|4|2|Nihal ÖZDOĞAN|Çarşamba 10:00-12:00||X3|278
MAT0291 Diferansiyel Denklemler|T|4|2|Nihal ÖZDOĞAN|Cuma 10:00-12:00||X3|278
MAT0291 Diferansiyel Denklemler|T|5,6|2|Yücel ÇENESİZ|Çarşamba 10:00-12:00||Şube 1|278
MAT0291 Diferansiyel Denklemler|T|5,6|2|Yücel ÇENESİZ|Perşembe 10:00-12:00||Şube 1|278
MAT0201 Diferansiyel Denklemler|T|7,8,9,25|2|Burhan ALVEROĞLU|Pazartesi 13:00-15:00||X4|278
MAT0201 Diferansiyel Denklemler|T|7,8,9,25|2|Burhan ALVEROĞLU|Cuma 13:00-15:00||X4|278
MAT0291 Diferansiyel Denklemler|T|12,14|2|Yücel ÇENESİZ|Perşembe 15:00-17:00||Şube 1|278
MAT0291 Diferansiyel Denklemler|T|12,14|2|Yücel ÇENESİZ|Cuma 8:00-10:00||Şube 1|278
MAT0291 Diferansiyel Denklemler|T|16,17|2|Burhan ALVEROĞLU|Salı 13:00-15:00||X5|278
MAT0291 Diferansiyel Denklemler|T|16,17|2|Burhan ALVEROĞLU|Perşembe 8:00-10:00||X5|278
MAT0291 Diferansiyel Denklemler|T|25|2|Burhan ALVEROĞLU|Pazartesi 13:00-15:00||X4|278
MAT0291 Diferansiyel Denklemler|T|25|2|Burhan ALVEROĞLU|Cuma 13:00-15:00||X4|278
?MTH201 Diferansiyel Denklemler|T|7,8,9|2|Burhan ALVEROĞLU|Pazartesi 13:00-15:00||X4|278
?MTH201 Diferansiyel Denklemler|T|7,8,9|2|Burhan ALVEROĞLU|Cuma 13:00-15:00||X4|278
MAT0291 Diferansiyel Denklemler|T|26,27|2|Yücel ÇENESİZ|Carşamba 8:00-10:00||Şube 1|278
MAT0291 Diferansiyel Denklemler|T|26,27|2|Yücel ÇENESİZ|Perşembe 8:00-10:00||Şube 1|278
MAT0291 Diferansiyel Denklemler|T|17|2|Burhan ALVEROĞLU|Salı 13:00-15:00||X5|278
MAT0291 Diferansiyel Denklemler|T|17|2|Burhan ALVEROĞLU|Perşembe 8:00-10:00||X5|278
OUMAT291 Differential Equations|T|5,6|2|Bahar ARSLAN|Salı 10:00-12:00||X1|278
OUMAT291 Differential Equations|T|5,6|2|Bahar ARSLAN|Çarşamba 10:00-12:00||X1|278
MTH201 Differential Equations|T|7,8,9|2|Bahar ARSLAN|Salı 10:00-12:00||X1|278
MTH201 Differential Equations|T|7,8,9|2|Bahar ARSLAN|Çarşamba 10:00-12:00||X1|278
MTH201 Differantial Equations|T|12,14|2|Bahar ARSLAN|Salı 10:00-12:00||X1|278
MTH201 Differantial Equations|T|12,14|2|Bahar ARSLAN|Çarşamba 10:00-12:00||X1|278
DDMATH0201 Differential Equations|T|18,19,20,21,22,23|2|Bahar ARSLAN|Salı 10:00-12:00||X1|278
DDMATH0201 Differential Equations|T|18,19,20,21,22,23|2|Bahar ARSLAN|Çarşamba 10:00-12:00||X1|278
MTH201 Differential Equations|T|1,2|2|Bahar ARSLAN|Salı 10:00-12:00||X1|278
MTH201 Differential Equations|T|1,2|2|Bahar ARSLAN|Çarşamba 10:00-12:00||X1|278
MAT0297 Mühendislik Matematiği|T|11|2|Ayşe BORAT|Perşembe 8:00-11:00||Şube 1|278
MTH101 Mathematics I|T|12,14|1|Ayşe BORAT|Salı 13:00-17:00||X2|278
DDMATH0101 Calculus 1|T|18,19,20,21,22,23|1|Ayşe BORAT|Salı 13:00-17:00||X2|278
KIM0197 Genel Kimya I|T|3|1|Ece ÜNÜR YILMAZ|Perşembe 8:00-11:00||A1|278
KİM0197 Genel Kimya I|T|16|1|Ece ÜNÜR YILMAZ|Perşembe 14:00-17:00||A2|278
KİM0197 Genel Kimya I|T|26,27|1|Ece ÜNÜR YILMAZ|Çarşamba 14:00-17:00||A3|278
KIM0199 Genel Kimya Lab I|L|3|1|Mustafa ÇİFTÇİ|Salı 8:00-10:00||A1|277
KIM0199 Genel Kimya Lab I|L|3|1|Mustafa ÇİFTÇİ|Salı 13:00-15:00||A3|277
KİM0199 Genel Kimya Laboratuvarı I|L|16|1|Mustafa ÇİFTÇİ|Salı 10:00-12:00||A2|277
KİM0199 Genel Kimya Laboratuvarı I|L|26,27|1|Mustafa ÇİFTÇİ|Salı 15:00-17:00||A4|277
KİM0199 Genel Kimya Laboratuvarı I|L|26,27|1|Mustafa ÇİFTÇİ|Pazartesi 10:00-12:00||A3|277
KİM0193 Genel Kimya|T|11|1|Mustafa Salih Hızır|Çarşamba 08:00-11:00||Şube 1|278
KİM0197 Genel Kimya Laboratuvarı|L|11|1|Mustafa ÇİFTÇİ|Pazartesi 13:00-15:00||Y1|277
KİM0197 Genel Kimya Laboratuvarı|L|11|1|Mustafa ÇİFTÇİ|Pazartesi 15:00-17:00||Y2|277
KİM0193 Genel Kimya|T|4|1|Mustafa Salih Hızır|Salı 14:00-17:00||Şube 1|278
KİM0195 Genel Kimya Laboratuarı|L|4|1|Mustafa ÇİFTÇİ|Pazartesi 08:00-10:00||Y3|277
KİM0193 Genel Kimya|T|5,6|1|Gökhan GECE|Perşembe 14:00-17:00||Şube 1|278
KIM0193 Genel Kimya|T|12,14|1|Havva Esma Okur Kutay|Çarşamba 14:00-17:00||Şube 1|278
KİM0297 Organik Kimya|T|3|2|Ömer KOZ|Pazartesi 14:00-17:00||Şube 1|278
KİM0297 Organik Kimya|T|11|2|Ömer KOZ|Salı 14:00-17:00||Şube 1|278
KİM0297 Organik Kimya|T|16|2|Gamze KOZ|Pazartesi 14:00-17:00||Şube 1|278
KİM0299 Analitik Kimya ve Enstrumental Analiz|T|16|2|Burçak KAYA ÖZSEL|Pazartesi 10:00-12:00||Şube 1|278
KİM0299 Analitik Kimya ve Enstrumental Analiz|T|16|2|Burçak KAYA ÖZSEL|Salı 15:00-17:00||Şube 1|278
KİM0293 Fizikokimya|T|16|2|Gökhan GECE|Perşembe 10:00-12:00||Şube 1|278
KİM0293 Fizikokimya|T|16|2|Gökhan GECE|Cuma 10:00-12:00||Şube 1|278
";


/*
$csvcontent = "
FZK0101 Fizik I|T|1,2,3,4,5,6,7,8,9,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27|1|Mehmet BÜYÜKYILDIZ|Pazartesi 14:00-16:45|Salı 14:00-16:45
FZK0101 Fizik I|L|1,2,3,4,5,6,7,8,9,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27|1|Mehmet BÜYÜKYILDIZ|Pazartesi 17:00-18:45|Salı 17:00-18:45
FZK0102 Fizik II|T|1,2,3,4,5,6,7,8,9,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27|1|Hüseyin DAĞ|Pazartesi 08:00-10:45|Cumartesi 08:00-10:45
FZK0102 Fizik II|L|1,2,3,4,5,6,7,8,9,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27|1|Hüseyin DAĞ|Pazartesi 11:00-12:45|Cumartesi 11:00-12:45
MAT0111 ANALİZ I|T|24|1|İbrahim TEKİN|Salı 08:00-12:45|Perşembe 13:00-17:45
MAT0111 ANALİZ I|L|24|1|İbrahim TEKİN||
MAT0101 Matematik I|T|1,2,3,4,5,6,7,8,9,11,12,14,15,16,17,18,19,20,21,22,23,25,26,27|1|Abdullah MAĞDEN|Salı 08:00-11:45|Çarşamba 13:00-16:45
MAT0291 Diferansiyel Denklemler|T|1,2,3,4,5,6,7,8,9,11,12,14,16,17,18,19,20,21,22,23,25,26,27|2|Yücel ÇENESİZ|Salı 10:00-13:45|Cuma 15:00-18:45
MAT0102 Matematik II|T|1,2,3,4,5,6,7,8,9,11,12,14,15,16,17,18,19,20,21,22,23,25,26,27|1|Abdullah MAĞDEN|Cumartesi 13:00-16:45|Perşembe 12:00-15:45
KİM0297 Organik Kimya|T|11,17,16|2|Ömer Koz|Salı 15:00-17:45|Çarşamba15:00-17:45
KİM0209 Anorganik Kimya I|T|15|2|Murat Özen|Pazartesi 09:00-12:45|Çarşamba 14:00-17:45
KİM0210 Anorganik Kimya II|T|15|2|Murat Özen|Pazartesi 14:00-17:45|Çarşamba 09:00-12:45
KİM0205 Organik Kimya I|T|15|2|Gamze Koz|Salı 10:00-13:45|Çarşamba 10:00-13:45
KİM0197 Genel Kimya I|T|15,16,26,27,3|1|Ece Ünür Yılmaz|Çarşamba 09:00-12:45|Cuma 15:00-18:45
KİM0196 Genel Kimya II|T|15,16,26,27,3|1|Ece Ünür Yılmaz|Perşembe 08:00-11:45|Cuma 09:00-12:45
KİM0293 Fizikokimya|T|16|2|Gökhan Gece|Perşembe 12:00-15:45|Cuma 08:00-11:45
KİM0301 Fizikokimya I|T|15|3|Gökhan Gece|Çarşamba 08:00-11:45|Perşembe 08:00-11:45
KİM0193 Genel Kimya|T|18,19,20,21,22,23,11,4,17,5,6,38|1|Gökhan Gece|Perşembe 16:00-18:45|Cuma 15:00-17:45
BLM0111 Algoritmalar ve Programlama|T|1,2|1|Ergün GÜMÜŞ|Çarşamba 10:00-12:45|Cuma 10:00-12:45
BLM0111 Algoritmalar ve Programlama|L|1,2|1|Ergün GÜMÜŞ|Çarşamba 13:00-14:45|Cuma 15:00-16:45
BLM0101 Bilgisayar Mühendisliğine Giriş|T|1,2|1|Turgay Tugay BİLGİN |Çarşamba 10.00-12.45|Perşembe 10.00-12.45
END402 İş Etüdü|T|7,8,9|4|Aytaç YILDIZ|Pazartesi 13:00-15:45|Salı 13:00-15:45
IE403 Systems Design I|T|7,8,9|4|Aytaç YILDIZ|Pazartesi 08:00-12:45|Salı 08:00-12:45
IE402 Work Study|T|7,8,9|4|Aytaç YILDIZ|Çarşamba 13:00-15:45|Perşembe 13:00-15:45
KMB0301 Kimya Mühendisliği Termodinamiği|T|16|3|Osman Nuri ŞARA|Pazartesi 09:00-11:45|Salı 09:00-11:45
KMB0318 Ayırma İşlemleri|T|16|3|Osman Nuri ŞARA|Pazartesi 15:00-18:45|Çarşamba 08:00-11:45
KMB0320 Kimyasal Tepkime Mühendisliği II|T|16|3|Mehmet ÇOPUR|Perşembe 13:00-15:45|Cuma 09:00-11:45
KMB0305 Kimyasal Tepkime Mühendisliği I|T|16|3|Mehmet ÇOPUR|Salı 15:00-17:45|Çarşamba 15:00-17:45
KMB0216 Termodinamik|T|16|2|Hülya KOYUNCU|Perşembe 08:00-11:45|Cumartesi 13:00-16:45
KMB0201 Kimyasal Proses Hesaplamaları|T|16|2|Halit Levent HOŞGÜN|Pazartesi 15:00-18:45|Çarşamba 08:00-11:45
KMB0212 Akışkanlar Mekaniği|T|16|2|Halit Levent HOŞGÜN|Pazartesi 08:00-11:45|Cumartesi 08:00-11:45
KMB0214 Sayısal Yöntemler|T|16|2|Seçgin KARAGÖZ|Perşembe 16:00-18:45|Cuma 12:00-14:45
KMB0218 Kimya Mühendisliğinde Yazılım Uygulamaları|T|16|2|Seçgin KARAGÖZ|Pazartesi 12:00-14:45|Çarşamba 12:00-14:45
KMB0414 Proses Dinamiği ve Kontrolü|T|16|4|Ömür ARAS|Salı 12:00-14:45|Cuma 15:00-17:45
MAK0307 Isı Transferi|T|18,19,20,21,22,23|3|Kemal Furkan SÖKMEN|Çarşamba 13:00-15:45|Perşembe 09:00-11:45
MAK0305 Makine Elemanları I|T|18,19,20,21,22,23|3|Onur SARAY|Çarşamba 10:00-12:45|Cuma 10:00-12:45
MAK0303 Makine Teorisi I|T|18,19,20,21,22,23|3|Hakan GÖKDAĞ|Perşembe 13:00-15:45|Cuma 15:00-17:45
MECH0201-1 Statics|T|18,19,20,21,22,23|2|Osman BAYRAK|Pazartesi 08:00-10:45|Cumartesi 10:00-12:45
MECH0202 Strength of Materials|T|18,19,20,21,22,23|2|Ahmet Hanifi ERTAŞ|Çarşamba 09:00-12:45|Cuma 09:00-12:45
MECH0204 Engineering Mechanics 2-Dynamics|T|18,19,20,21,22,23|2|Hüseyin LEKESİZ|Pazartesi 14:00-16:45|Salı 14:00-16:45
MAK0210 Sayısal Analiz|T|18,19,20,21,22,23|2|Emre DEMİRCİ|Perşembe 13:00-15:45|Cumartesi 13:00-15:45
MECH0206 Thermodynamics II|T|18,19,20,21,22,23|2|Tayfun TANBAY|Salı 08:00-10:45|Çarşamba 13:00-15:45
EEM0291 Elektroteknik|T|5,6|2|Hakan ÜLKER|Pazartesi 08:00-11:45|
EEM0291 Elektroteknik|L|5,6|2|Hakan ÜLKER||Perşembe 08:00-11:45
MAK0308 Kontrol Sistemleri|T|18,19,20,21,22,23|3|Hakan ÜLKER|Pazartesi 14:00-16:45|Salı 14:00-16:45
MME0304 Characterization of Materials|T|26,27|3|Yakup YÜREKTÜRK|Salı 12:00-14:45 |Çarşamba 15:00-17:45
MMM0291 Malzeme Bilimi|T|26,27|2|Yakup YÜREKTÜRK|Pazartesi 12:00-14:45 |Salı 09:00-11:45
MME0399 Chemical Metallurgy I|T|26,27|3|Nazlı AKÇAMLI|Salı 15:00-17:45 |Cuma 09:00-11:45
MMM0205 Termodinamik I|T|26,27|2|Nazlı AKÇAMLI|Perşembe 09:00-11:45 |Cuma 15:00-17:45
MAK0194 Mühendislik Mekaniği|T|18,19,20,21,22,23|1|Cihan KABOĞLU|Pazartesi 09:00-11:45 |Perşembe 15:00-17:45
MME0314 Principles of Physical Metallurgy|T|26,27|3|Deniz UZUNSOY|Çarşamba 09:00-11:45 |Perşembe 12:00-14:45 
MME0200 Engineering Materials|T|26,27|2|Ebru Devrim ŞAM PARMAK|Pazartesi 15:00-17:45|Çarşamba 12:00-14:45 
CEM0333 Gürültü Kirliliği ve Kontrolü|T|4|3|Orhan Taner CAN|Pazartesi 11:00-13:45|Pazartesi 14:00-16:45
CEM0304 Biyolojik Prosesler|T|4|3|Ahmet AYGÜN|Cumartesi 10:00-12:45|Cumartesi 14:00-16:45
CEM0304 Biyolojik Prosesler|L|4|3|Ahmet AYGÜN|Cumartesi 13:00-13:45|Cumartesi 17:00-17:45
CEM0309 Hava Kirliliği ve Kontrolü|T|4|3|Aşkın BİRGÜL|Salı 10:00-12:45|Salı 14:00-16:45
CEM0309 Hava Kirliliği ve Kontrolü|L|4|3|Aşkın BİRGÜL|Salı 13:00-13:45|Salı 17:00-17:45
CEM0436 Anaerobik Arıtım|T|4|4|Aşkın BİRGÜL|Perşembe 10:00-12:45|Perşembe 13:00-15:45
CEM0335 Deniz Kirliliği ve Kontrolü|T|4|3|Aşkın BİRGÜL|Çarşamba 10:00-12:45|Çarşamba 13:00-15:45
CEM0301 Su Kalitesi Yönetimi|T|4|3|Saadet HACISALİHOĞLU|Cuma 10:00-12:45|Cuma 13:00-15:45
CEM0203 Akışkanlar Mekaniği|T|4|2|Samet ÖZTÜRK|Perşembe 16:00-18:45|Cuma 16:00-18:45
INS0301 Betonarme I|T|12,14|3|İsa Yüksel|Salı 14:00-17.45|Perşembe 14:00-17:45
INS0301 Betonarme I|L|12,14|3|İsa Yüksel||
INS0106 Statik|T|12,14|1|Beyhan Bayhan|Çarşamba 08:00-11:45|Perşembe 08:00-11:45
INS0106 Statik|L|12,14|1|Beyhan Bayhan||
INS0450 Taze ve Sertleşmiş Betonun Özellikleri|T|12,14|4|Süleyman ÖZEN|Pazartesi 11:00-13:45|Salı 08:00-10:45
INS0207 Mukavemet I|T|12,14|2|Şeref Doğuşcan AKBAŞ|Cuma 08:00-11:45|Cumartesi 08:00-11:45
INS0207 Mukavemet I|L|12,14|2|Şeref Doğuşcan AKBAŞ||
INS0206 Akışkanlar Mekaniği|T|12,14|2|Egemen ARAS|Pazartesi 11:00-13:45|Cumartesi 12:00-14:45
INS0311 Hidrolik|T|12,14|3|Egemen ARAS|Pazartesi 15:00-17:45|Cuma 16:00-18:45
INS0311 Hidrolik|L|12,14|3|Egemen ARAS||
INS0312 Ulaşım II|T|12,14|3|Nurten AKGÜN|Pazartesi 08:00-10:45|Cumartesi 12:00-14:45
INS0308 Temel Mühendisliği I|T|12,14|3|Eyübhan AVCI|Çarşamba 14:00-17:45|Cumartesi 15:00-18:45
INS0308 Temel Mühendisliği I|L|12,14|3|Eyübhan AVCI||
INS0305 Zemin Mekaniği II|T|12,14|3|Eyübhan AVCI|Çarşamba 08:00-10:45|Perşembe 08:00-10:45
INS0305 Zemin Mekaniği II|L|12,14|3|Eyübhan AVCI||
INS0454 Şevlerin Stabilitesi|T|12,14|4|Eyübhan AVCI|Cuma 08:00-10:45|Cumartesi 08:00-10:45
EEM0201 Devre Teorisi I|T|5,6|2|GÖKAY BAYRAK|Pazartesi 14:00-16:45|Salı 14:00-16:45
EEM0203 Elektronik I|T|5,6|2|YUSUF YAŞA|Perşembe 16:00-18:45|Cuma 09:00-11:45
EEM0207 Elektromanyetik Alanlar|T|5,6|2|MEHMED SALİH BOSTAN|Pazartesi 10:00-12:45|Cumartesi 10:00-12:45
EEM0202 Sinyaller ve Sistemler|T|5,6|2|FATMATÜLZEHRA USLU|Perşembe 13:00-15:45|Cumartesi 14:00-16:45
EEM0204 Devre Teorisi II|T|5,6|2|MUSTAFA ÖZDEN|Çarşamba 09:00-11:45|Perşembe 09:00-11:45
MCH0112 Fundamentals of Electrical Circuits|T|25|1|Ahmet MERT|Çarşamba 10:00-12:45|Cuma 10:00-12:45
MCH0112 Fundamentals of Electrical Circuits|L|25|1|Ahmet MERT|Çarşamba 08:00-09:45|Cuma 08:00-09:45
MKT0211 Elektronik I|T|25|2|Ekrem DÜVEN|Perşembe 10:00-11:45|Perşembe 12:00-13:45
MKT0211 Elektronik I|L|25|2|Ekrem DÜVEN|Perşembe 08:00-09:45|Perşembe 14:00-15:45
MKT0212 Elektronik II|T|25|2|Ekrem DÜVEN|Cumartesi 10:00-11:45|Cumartesi 12:00-13:45
MKT0212 Elektronik II|L|25|2|Ekrem DÜVEN|Cumartesi 08:00-09:45|Cumartesi 14:00-15:45
MECH0201-2 Engineering Mechanics - Statics|T|18,19,20,21,22,23|2|Nurettin Gökhan ADAR|Pazartesi 10:00-12:45|Pazartesi 13:00-15:45
MKTS0309 Üretim Yöntemleri|T|25|3|Celalettin YÜCE|Çarşamba 10:00-12:45|Çarşamba 13:00-15:45
MCH0315 Measurement Techniques and Instrumentation|T|25|3|Ahmet MERT|Perşembe 10:00-11:45|Perşembe 12:00-13:45
MCH0315 Measurement Techniques and Instrumentation|L|25|3|Ahmet MERT|Perşembe 08:00-09:45|Perşembe 14:00-15:45
MKTS0307 Yapay Sinir Ağlarına Giriş|T|25|3|Saffet VATANSEVER|Cumartesi 10:00-12:45|Cumartesi 13:00-15:45
MKTS0405 Endüstriyel Robot Programlama|T|25|4|Gökhan GELEN|Salı 10:00-12:45|Salı 13:00-15:45
";
*/



//KONTROL
if($runautoscr == -1)
{
    error_reporting(E_ALL);
    
    echo "Running autoscr-checking...";
    


    $csvlines = explode("\n",$csvcontent);
    
    
    $jointTable=$wpdb->prefix."utt_joint";
    $lecturesView=$wpdb->prefix."utt_lectures_view";
    $teachersTable = $wpdb->prefix."utt_teachers";
    $lecturesTable = $wpdb->prefix."utt_lectures";
    $subjectsTable = $wpdb->prefix."utt_subjects";
    $groupsTable = $wpdb->prefix."utt_groups";
    
    
    $classroomsTable = $wpdb->prefix."utt_classrooms";
    
    
    
    foreach ($csvlines as $line)
    {
        echo "<br><font color='magenta'>$line</font><br><br>";
        if (empty($line))
        {
            continue;
        }
        $linedatas = explode("|",$line);
        //PARSE
        $title = $linedatas[0];
        $type = $linedatas[1];
        $departmentIDs = explode(",",$linedatas[2]);
        $class_level = $linedatas[3];
        
        $teachername = $linedatas[4];
        $datepart1 = $linedatas[5];
        $datepart2 = $linedatas[6];
        
        $classroomID = $linedatas[8];//278;//Online Sınıf
        
        
        
        
        $teachername2 = tr_strtoupper(str_replace(' ', '',$teachername));
        $findteacher = $wpdb->get_row($wpdb->prepare("SELECT * FROM $teachersTable WHERE UPPER(REPLACE(CONCAT(name,'',surname),' ','')) = %s;",$teachername2));
        $teacherID = $findteacher->teacherID;
        
        
        
        $findclassroom = $wpdb->get_row($wpdb->prepare("SELECT * FROM $classroomsTable WHERE classroomID = %d;",$classroomID));
        $classroomID2 = $findclassroom->classroomID;
        $classroomName = $findclassroom->name;
        
        
        
        
        $findsubject = $wpdb->get_row($wpdb->prepare("SELECT * FROM $subjectsTable WHERE title = %s;",$title));
        $subjecttitle = $findsubject->title;
        
        
        
        $datepart1_fix = formdate_to_dbdate($datepart1);
        
        
        
        
        echo "<br>";
        echo "--> ($teachername) ($teachername2) ID: ($teacherID)<br>";
        echo "--> Classroom ($classroomID) ($classroomID2) Name: ($classroomName)<br>";
        echo "--> Subject ($title) Title: ($subjecttitle)<br>";
        echo "--> Date1: (".$datepart1_fix[0].") - (".$datepart1_fix[1].")<br>";
        
        
        if(!$teacherID)
        {
            echo "<br>----> <font color='red'>ERROR</font> : Hoca ID Bulunamadı ($teachername) ($teachername2) ID: ($teacherID)<br>";
        }
        if(!$classroomName)
        {
            echo "<br>----> <font color='red'>ERROR</font> : Derslik Bulunamadı ($classroomID) ($classroomID2) ID: ($classroomName)<br>";
        }
        if(!$subjecttitle)
        {
            echo "<br>----> <font color='red'>ERROR</font> : Ders Bulunamadı ($title) Title: ($subjecttitle)<br>";
        }
        
        
        
        echo "----------<br>";
        
    }
}





//DERS TANIMLAMA
if($runautoscr == 1)
{
    $SuccessNum = 0;
    error_reporting(E_ALL);
    
    echo "Running autoscr...";
    

    $csvlines = explode("\n",$csvcontent);
    
    
    $subjectsTable = $wpdb->prefix."utt_subjects";
    $wpdb->query('START TRANSACTION');
    
    foreach ($csvlines as $line)
    {
        echo "<br>$line<br><br>";
        if (empty($line))
        {
            continue;
        }
        $linedatas = explode("|",$line);
        
        
        //PARSE
        $title = $linedatas[0];
        $type = $linedatas[1];
        $departmentIDs = explode(",",$linedatas[2]);
        $class_level = $linedatas[3];
        
        foreach ($departmentIDs as $departmentID)
        {
            echo "<br>Adding $title $type to ".$departments[$departmentID-1]." Class $class_level<br>";
            
            $safeSql = $wpdb->prepare("INSERT INTO $subjectsTable (title,type,semester,is_enabled,color,quota,class_level,is_common) VALUES (%s,%s,%d,%d,%s,%d,%d,%d)",$title,$type,$departmentID,1,'FFFFFF',0,$class_level,0);
            $success = $wpdb->query($safeSql);
            var_dump($success);
            
            if($success)
            {
                $SuccessNum++;
            }
        }
    }
    
    $wpdb->query('COMMIT');
    
    echo "<br>Total Success: $SuccessNum<br>";
}
















//ŞUBE TANIMLAMA
if($runautoscr == 2)
{
    $SuccessNum = 0;
    error_reporting(E_ALL);
    
    echo "Running autoscr2...";
    


    $csvlines = explode("\n",$csvcontent);
    
    
    $subjectsTable = $wpdb->prefix."utt_subjects";
    $groupsTable = $wpdb->prefix."utt_groups";
    $wpdb->query('START TRANSACTION');
    
    foreach ($csvlines as $line)
    {
        echo "<br>$line<br><br>";
        if (empty($line))
        {
            continue;
        }
        $linedatas = explode("|",$line);
        
        
        //PARSE
        $title = $linedatas[0];
        $type = $linedatas[1];
        $departmentIDs = explode(",",$linedatas[2]);
        $class_level = $linedatas[3];
        
        $group_name = $linedatas[7];//"Şube 1";
        if($group_name=="")
        {
            $group_name = "Şube 1";
        }
        
        
        
        
        foreach ($departmentIDs as $departmentID)
        {
            echo "<br>Adding GROUP for $title $type to ".$departments[$departmentID-1]." Class $class_level<br>";
            
            $recentsubject = $wpdb->get_row($wpdb->prepare("SELECT * FROM $subjectsTable WHERE title=%s AND type=%s AND semester=%d AND class_level=%d;",$title,$type,$departmentID,$class_level));
            //var_dump($recentsubject);
            $subjectID = $recentsubject->subjectID;
            //echo "<br>?$subjectID?<br>";
            
            $safeSql = $wpdb->prepare("INSERT INTO $groupsTable (periodID,subjectID,groupName) VALUES (%d,%d,%s)",$periodID,$subjectID,$group_name);
            $success = $wpdb->query($safeSql);
            var_dump($success);
            
            if($success)
            {
                $SuccessNum++;
            }
        }
    }
    
    $wpdb->query('COMMIT');
    
    echo "<br>Total Success: $SuccessNum<br>";
}
















//TAKVİM TANIMLAMA
if($runautoscr == 3)
{
    $SuccessNum = 0;
    error_reporting(E_ALL);
    
    echo "Running autoscr3...";
    


    $csvlines = explode("\n",$csvcontent);
    
    
    $jointTable=$wpdb->prefix."utt_joint";
    $lecturesView=$wpdb->prefix."utt_lectures_view";
    $teachersTable = $wpdb->prefix."utt_teachers";
    $lecturesTable = $wpdb->prefix."utt_lectures";
    $subjectsTable = $wpdb->prefix."utt_subjects";
    $groupsTable = $wpdb->prefix."utt_groups";
    $wpdb->query('START TRANSACTION');
    
    foreach ($csvlines as $line)
    {
        echo "<br><font color='magenta'>$line</font><br><br>";
        if (empty($line))
        {
            continue;
        }
        $linedatas = explode("|",$line);
        
        
        //PARSE
        $title = $linedatas[0];
        $type = $linedatas[1];
        $departmentIDs = explode(",",$linedatas[2]);
        $class_level = $linedatas[3];
        
        $teachername = $linedatas[4];
        $datepart1 = $linedatas[5];
        $datepart2 = $linedatas[6];
        
        $classroomID = $linedatas[8];//278;//Online Sınıf
        
        
        
        
        
        
        foreach ($departmentIDs as $departmentID)
        {
            echo "<br><font color='blue'>Adding GROUP for $title $type to ".$departments[$departmentID-1]." Class $class_level</font><br>";
            
            $recentsubject = $wpdb->get_row($wpdb->prepare("SELECT * FROM $subjectsTable WHERE title=%s AND type=%s AND semester=%d AND class_level=%d;",$title,$type,$departmentID,$class_level));
            //var_dump($recentsubject);
            $subjectID = $recentsubject->subjectID;
            //echo "<br>?$subjectID?<br>";
            
            
            $recentgroup = $wpdb->get_row($wpdb->prepare("SELECT * FROM $groupsTable WHERE periodID=%d AND subjectID=%d;",$periodID,$subjectID));
            $groupID = $recentgroup->groupID;
            
            $teachername = tr_strtoupper(str_replace(' ', '',$teachername));
            $findteacher = $wpdb->get_row($wpdb->prepare("SELECT * FROM $teachersTable WHERE UPPER(REPLACE(CONCAT(name,'',surname),' ','')) = %s;",$teachername));
            $teacherID = $findteacher->teacherID;
            
            
            echo "<br>";
            echo "--> $teachername ID: $teacherID<br>";
            echo "--> GROUP ID: $groupID<br>";
            
            if(!$teacherID)
            {
                echo "<br>----> ERROR : Hoca ID Bulunamadı $teachername ID: $teacherID<br>";
            }
            if(!$groupID)
            {
                echo "<br>----> ERROR : Group ID Bulunamadı $teachername ID: $teacherID<br>";
            }
            
            
            
            if(!empty($datepart1))
            {
                $startAndEndTimes = formdate_to_dbdate($datepart1);
                $startTime1 = $startAndEndTimes[0];
                $endTime1 = $startAndEndTimes[1];
                
                echo "Start Time: $startTime1<br>";
                echo "End Time: $endTime1<br>";
                
                
                
                
                
                
                //KONTROL VE EKLEME BAŞ
                
                //CHECK CONF
                $getSubjectId_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $groupsTable WHERE periodID=%d AND groupID=%d;",$periodID,$groupID));
                $getSubjectId = $getSubjectId_q->subjectID;
                $getSubjectTitle_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $subjectsTable WHERE subjectID=%d;",$getSubjectId));
                $getSubjectTitle = $getSubjectTitle_q->title;
                
                $busyTeacher = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d     AND subjectTitle!=%s    AND     teacherID=%d    AND     %s<end      AND     %s>start;",     $periodID,$getSubjectTitle,$teacher,$startTime1,$endTime1));
                $busyClassroom1 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d  AND subjectTitle!=%s    AND     classroomID=%d  AND     %s<end      AND     %s>start;",     $periodID,$getSubjectTitle,$classroomID,$startTime1,$endTime1));
                $busyClassroom2 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d  AND subjectTitle!=%s    AND     classroomID=%d  AND     %s<eventEnd AND     %s>eventStart;",$periodID,$getSubjectTitle,$classroomID,$startTime1,$endTime1));
                $busyGroup = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d       AND                             groupID=%d      AND     %s<end      AND     %s>start;",     $periodID,$groupID,$startTime1,$endTime1));
                
                //Online Education - Uzaktan Eğitim sınıfı
                if($classroomID==278)
                {
                    $busyClassroom1 = "";
                    $busyClassroom2 = "";
                }
                
    	        //joint check
                $findjoint_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d AND (teacherID=%d OR classroomID=%d) AND %s<end AND %s>start;",$periodID,$teacher,$classroomID,$startTime1,$endTime1));
                if($findjoint_q!="")
                {
                    $thissubjectid_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $groupsTable WHERE groupID=%d;",$groupID));
                    $otherid = $findjoint_q->subjectID;
                    $thisid = $thissubjectid_q->subjectID;
                    $getthejoint_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $jointTable WHERE s1ID=%d OR s2ID=%d OR s3ID=%d OR s4ID=%d OR s5ID=%d;",$otherid,$otherid,$otherid,$otherid,$otherid));
                    if($getthejoint_q!="" && ($getthejoint_q->s1ID == $thisid || ($getthejoint_q->s2ID == $thisid && $getthejoint_q->s2ok==1) || ($getthejoint_q->s3ID == $thisid && $getthejoint_q->s3ok==1) || ($getthejoint_q->s4ID == $thisid && $getthejoint_q->s4ok==1) || ($getthejoint_q->s5ID == $thisid && $getthejoint_q->s5ok==1) ))
                    {
                        $isCommon = true;
                    }
                }
                
                if(!$isCommon && ($busyTeacher!="" || $busyGroup!="" || $busyClassroom1!="" || $busyClassroom2!=""))
    			{
                    $exists = 1;
                }
    			else
    			{
                    $safeSql = $wpdb->prepare("INSERT INTO $lecturesTable (groupID, classroomID, teacherID, start, end, description,userid) VALUES( %d, %d, %d, %s, %s, %s, %d)",$groupID,$classroomID,$teacherID,$startTime1,$endTime1,"",1);
                    $success = $wpdb->query($safeSql);var_dump($success);
                    if($success)
                    {
                        echo "<font color='green'>Ders eklendi!</font><br>";
                        $SuccessNum++;
                    }
                    else
                    {
                        echo "<font color='red'>ERROR : Ders eklenemedi!</font><br>";
                    }
                }
                
                //KONTROL VE EKLEME SON
                
            }
            if(!empty($datepart2))
            {
                $startAndEndTimes = formdate_to_dbdate($datepart2);
                $startTime2 = $startAndEndTimes[0];
                $endTime2 = $startAndEndTimes[1];
                
                echo "Start Time: $startTime2<br>";
                echo "End Time: $endTime2<br>";
                
                
                
                
                
                
                
                
                
                
                //KONTROL VE EKLEME BAŞ
                
                //CHECK CONF
                $getSubjectId_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $groupsTable WHERE periodID=%d AND groupID=%d;",$periodID,$groupID));
                $getSubjectId = $getSubjectId_q->subjectID;
                $getSubjectTitle_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $subjectsTable WHERE subjectID=%d;",$getSubjectId));
                $getSubjectTitle = $getSubjectTitle_q->title;
                
                $busyTeacher = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d     AND subjectTitle!=%s    AND     teacherID=%d    AND     %s<end      AND     %s>start;",     $periodID,$getSubjectTitle,$teacher,$startTime2,$endTime2));
                $busyClassroom1 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d  AND subjectTitle!=%s    AND     classroomID=%d  AND     %s<end      AND     %s>start;",     $periodID,$getSubjectTitle,$classroomID,$startTime2,$endTime2));
                $busyClassroom2 = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d  AND subjectTitle!=%s    AND     classroomID=%d  AND     %s<eventEnd AND     %s>eventStart;",$periodID,$getSubjectTitle,$classroomID,$startTime2,$endTime2));
                $busyGroup = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d       AND                             groupID=%d      AND     %s<end      AND     %s>start;",     $periodID,$groupID,$startTime2,$endTime2));
                
                //Online Education - Uzaktan Eğitim sınıfı
                if($classroomID==278)
                {
                    $busyClassroom1 = "";
                    $busyClassroom2 = "";
                }
                
    	        //joint check
                $findjoint_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $lecturesView WHERE periodID=%d AND (teacherID=%d OR classroomID=%d) AND %s<end AND %s>start;",$periodID,$teacher,$classroomID,$startTime2,$endTime2));
                if($findjoint_q!="")
                {
                    $thissubjectid_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $groupsTable WHERE groupID=%d;",$groupID));
                    $otherid = $findjoint_q->subjectID;
                    $thisid = $thissubjectid_q->subjectID;
                    $getthejoint_q = $wpdb->get_row($wpdb->prepare("SELECT * FROM $jointTable WHERE s1ID=%d OR s2ID=%d OR s3ID=%d OR s4ID=%d OR s5ID=%d;",$otherid,$otherid,$otherid,$otherid,$otherid));
                    if($getthejoint_q!="" && ($getthejoint_q->s1ID == $thisid || ($getthejoint_q->s2ID == $thisid && $getthejoint_q->s2ok==1) || ($getthejoint_q->s3ID == $thisid && $getthejoint_q->s3ok==1) || ($getthejoint_q->s4ID == $thisid && $getthejoint_q->s4ok==1) || ($getthejoint_q->s5ID == $thisid && $getthejoint_q->s5ok==1) ))
                    {
                        $isCommon = true;
                    }
                }
                
                if(!$isCommon && ($busyTeacher!="" || $busyGroup!="" || $busyClassroom1!="" || $busyClassroom2!=""))
    			{
                    $exists = 1;
                    echo "<font color='orange'>ERROR : Ders eklenemedi, çakışma meydana geliyor.</font><br>";
                    echo "T LectureID: ".$busyTeacher->lectureID."<br>";
                    echo "G LectureID: ".$busyGroup->lectureID."<br>";
                    echo "C1 LectureID: ".$busyClassroom1->lectureID."<br>";
                    echo "C2 LectureID: ".$busyClassroom2->lectureID."<br>";
                }
    			else
    			{
                    $safeSql = $wpdb->prepare("INSERT INTO $lecturesTable (groupID, classroomID, teacherID, start, end, description,userid) VALUES( %d, %d, %d, %s, %s, %s, %d)",$groupID,$classroomID,$teacherID,$startTime2,$endTime2,"",1);
                    $success = $wpdb->query($safeSql);var_dump($success);
                    if($success)
                    {
                        echo "<font color='green'>Ders eklendi!</font><br>";
                        $SuccessNum++;
                    }
                    else
                    {
                        echo "<font color='red'>ERROR : Ders eklenemedi!</font><br>";
                    }
                }
                
                //KONTROL VE EKLEME SON
            }
            
            //$safeSql = $wpdb->prepare("INSERT INTO $lecturesTable (groupID,classroomID,teacherID,start,end,description,userid) VALUES (%d,%d,%d,%s,%s,%s,%d)",$groupID,$classroomID,$teacherID,$startTime,$endTime,"",1);
            // $success = $wpdb->query($safeSql);
            // var_dump($success);
            
            // if($success)
            // {
            //     $SuccessNum++;
            // }
        }
    }
    
    $wpdb->query('COMMIT');
    
    echo "<br>Total Success: $SuccessNum<br>";
}


?>