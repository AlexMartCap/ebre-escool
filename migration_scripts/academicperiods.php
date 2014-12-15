<?php

# 0 -> ONLY ONE PERIOD 1 -> ALL PERIODS
$BOOLEAN_PERIODS=1;

// OBSOLET?
$CURRENT_PERIOD= "2013-14";

$academic_periods_info = array();

//IF ONLY ONE PERIOD IS SELECTED HERE INDICATE WICH ONE
$academic_period_onlyone = new stdClass;
$academic_period_onlyone->name = "2010-11";
$academic_period_onlyone->alt_name = "201011";
$academic_period_onlyone->basedn = "ou=Alumnes,ou=All,ou=201011,dc=iesebre,dc=com";

$academic_period1 = new stdClass;
$academic_period1->name = "2010-11";
$academic_period1->alt_name = "201011";
$academic_period1->basedn = "ou=Alumnes,ou=All,ou=201011,dc=iesebre,dc=com";

$academic_period2 = new stdClass;
$academic_period2->name = "2011-12";
$academic_period2->alt_name = "201112";
$academic_period2->basedn = "ou=Alumnes,ou=All,ou=201112,dc=iesebre,dc=com";

$academic_period3 = new stdClass;
$academic_period3->name = "2012-13";
$academic_period3->alt_name = "201213";
$academic_period3->basedn = "ou=Alumnes,ou=All,ou=201213,dc=iesebre,dc=com";

$academic_period4 = new stdClass;
$academic_period4->name = "2013-14";
$academic_period4->alt_name = "201314";
$academic_period4->basedn = "ou=Alumnes,ou=All,dc=iesebre,dc=com";

if ($BOOLEAN_PERIODS) {
	$academic_periods_info = array ( $academic_period1, $academic_period2, $academic_period3, $academic_period4 );
}	else {
	$academic_periods_info = array ( $academic_period_onlyone );
}

?>