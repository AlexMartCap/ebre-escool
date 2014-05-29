<?php


/*
|--------------------------------------------------------------------------
| GROUP WITH READONLY ROLE
|--------------------------------------------------------------------------
|
| Groups with readonly acces to app
| 
| Example: Enrollment Wizard
*/
$config['academic_periods'] = array(
	1 => "2013-14",
	2 => "2014-15",
	3 => "2015-16"
	);

$config['current_period'] = "2014-15";

/* Enrollment Configurations */
// URL LINKS
$config['rulesURL'] = "http://moodle.iesmontsia.org/normesTIC";
$config['servicesURL'] = "http://moodle.iesmontsia.org/serveisTIC";

$config['highSchoolName'] = "Institut de l'Ebre";
$config['highSchoolSuffixEmail'] = "iesebre.com";

// DOCUMENT NAME= externalID_internalID_documentNameSufix
$config['documentNameSufix'] = "_matriculaTIC.pdf";

$config['windowheadertitle'] = "Matrícula TIC de l'alumne";

// IMAGES PATHS
$config['logo_image'] = base_url('assets/img').'/logo1.jpeg';
$config['signature_image'] = base_url('assets/img').'/signature.jpeg';

// STRINGS
$config['STR_Title'] = "MATRÍCULA TIC";
$config['STR_User'] = "Usuari";
$config['STR_Password'] = "Paraula de pas";
$config['STR_InternalID'] = "Identificador del centre";
$config['STR_PersonalEmail'] = "Correu electrònic personal";
$config['STR_Email'] = "Correu electrònic del centre";
$config['STR_UserSignature'] = "Signatura de l'interessat/interessada";
$config['STR_SchoolSignature'] = "Signatura i segell del centre";
$config['STR_UserPageType'] = "Exemplar per a la persona interessada";
$config['STR_SchoolPageType'] = "Exemplar per a l'escola";
$config['STR_TutorPageType'] = "Exemplar per al tutor";

$config['IMPORTANT_NOTE'] = "IMPORTANT: La paraula de pas ha de ser PERSONAL i INTRANSFERIBLE, s'ha d'utilitzar en cura i no es pot deixar-la o prestar-la a altres usuaris. És la vostra responsabilitat no facilitar el vostre usuari o paraula de pas a NINGÚ. Queda expressament prohibit assumir la identitat d'altres usuaris.";

/* Comprovar els valors de les variables */
// TEXTS
//$config['text1'] = "En/Na $givenName $sn1 $sn2, amb número identificatiu $externalID, ha estat matriculat/da el $date per tal de tenir accés als recursos TIC de l'$HIGHSCHOOLSNAME. Les dades que heu d'utilitzar per accedir als recursos TIC del centre són:";
$config['text2'] = "En firmar aquesta matrícula esteu acceptant les normes d'ús dels recursos TIC del centre. Les normes les podeu consultar a: ";
$config['text3'] = "Amb el vostre compte d'usuari de centre podeu accedir a una sèrie de serveis que us ofereix el centre i que podeu consultar a:";
$config['text4'] = "<pre>En aquesta pàgina web també podeu trobar les instruccions per tal de modificar la vostra paraula de pas. És important que escolliu una paraula de pas prou segura i que us sigui fàcil de recordar. 

IMPORTANT: Si oblideu la vosta paraula de pas, la forma de recuperar-la serà enviar-vos una de nova a la vostra adreça de correu electrònic personal, per tant és molt important que ens proporcioneu una adreça de correu electrònic vàlida.</pre>";
//$config['text5'] = "Amposta, $day_of_month de $month de $year";

?>