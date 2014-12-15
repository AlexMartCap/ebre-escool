<?php

/*
PREREQUISITES:
Set basedn to base correct base dn period enrollment. P.e.  
 Uncomment temporarily in first line of file /usr/share/ebre-escool/application/config/auth_ldap.php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 Uncomment the same at file /usr/share/ebre-escool/application/config/database.php
*/

#IMPORTANT: no poseu les paraules de pas a aquest fitxer:
include "/usr/share/ebre-escool/application/config/auth_ldap.php";
include "/usr/share/ebre-escool/application/config/database.php";

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

//MYSQL CONNECTION
$con=mysqli_connect($db['default']['hostname'],$db['default']['username'],$db['default']['password'],$db['default']['database']);
	// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//GET PERSONS INFO FROM MYSQL
$result = mysqli_query($con,"SELECT * FROM person");
$persons = array();
$persons_dni = array();
while($row = mysqli_fetch_array($result)) {
	$person = new stdClass;
	$person->id = $row['person_id'];

	$persons[$row['username']] = $person;
	$persons_dni[$row['person_official_id']] = $person;
}
//print_r($persons);
//print_r($persons_dni);

//LDAP
$ldapconfig['host'] = $config['hosts'][0];
#Només cal indicar el port si es diferent del port per defecte
$ldapconfig['port'] = NULL;
//$ldapconfig['basedn'] = $config['basedn'];

$ds=ldap_connect($ldapconfig['host'], $ldapconfig['port']);

ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

$password=$config['proxy_pass'];
$dn=$config['proxy_user'];

if ($bind=ldap_bind($ds, $dn, $password)) {
  echo("LDAP Login correct\n");
} else {
  # Error
  echo("LDAP Login ERROR!\n");
}

//PERIODS
foreach ($academic_periods_info as $academic_period_info_key => $academic_period_info) {
	//Obtain all users for period
	//Search al Accounts with jpegPhotos
	echo "\n";
	echo "****************************************************\n";
	echo "   STARTING PERIOD " . $academic_period_info->name . "\n" ;
	echo "****************************************************\n";
	echo "";
	$filter="(objectClass=inetOrgPerson)";
	$sr=ldap_search($ds,$academic_period_info->basedn, $filter);   
	$totalUsers=ldap_count_entries($ds,$sr); 
	echo "Alumnes potencials a matrícular:".$totalUsers."\n";

	$info = ldap_get_entries($ds, $sr); 
	echo "Data for ".$info["count"]." items returned:\n"; 

	$enrolled_persons=0;

	for ($i=0; $i<$info["count"]; $i++  ) { 
		
		$uid= $info[$i]["uid"][0];	
		$irispersonaluniqueid="";
		if (array_key_exists ( "irispersonaluniqueid" , $info[$i] )) {
			$irispersonaluniqueid=$info[$i]["irispersonaluniqueid"][0];	
		} 

		if (array_key_exists ( $uid , $persons )) {
			echo "i FOUND!: " . $i . "| dni:" . $irispersonaluniqueid . "| uid: " . $uid . "| database id: " . $persons[$uid]->id  . "\n"; 

			if (!mysqli_query($con,"INSERT INTO enrollment (enrollment_periodid,enrollment_personid) VALUES ('" . $academic_period_info->name . "','" . $persons[$uid]->id .  "')")) {
				//die('Error: ' . mysqli_error($con));
				echo " ERROR! " . mysqli_error($con) . "\n";
			} else {
				$enrolled_persons++;	
				echo "Found by uid. ENROLLED to table enrollment!\n";
			}

			
		}	else {
			if ($irispersonaluniqueid != ""){ 
				if (array_key_exists ( $irispersonaluniqueid , $persons_dni )) {
					echo "i FOUND!: " . $i . "| dni:" . $irispersonaluniqueid . "| uid: " . $uid . "| database id: " . $persons_dni[$irispersonaluniqueid]->id; 
					if (!mysqli_query($con,"INSERT INTO  enrollment (enrollment_periodid,enrollment_personid) VALUES ('". $academic_period_info->name ."','" . $persons_dni[$irispersonaluniqueid]->id .  "')")) {
						//die('Error: ' . mysqli_error($con));
						echo " ERROR! " . mysqli_error($con) . "\n";

					} else {
						$enrolled_persons++;	
						echo "Found by id (DNI. NIE, passport...). ENROLLED to table enrollment!\n";
					}
					
				}
				else {
					echo "i NOT FOUND!: " . $i . "| dni:" . $irispersonaluniqueid . "| uid: " . $uid . "\n"; 
					//Insert Ldap person to secondary table!
				}
			}
		}
		//search person_id at database
		//$givenname= $info[$i]["givenname"][0];
		//print_r($info[$i]);
	} 

	echo "Alumnes matriculats al " . $academic_period_info->name . ": ".$enrolled_persons."\n";

	echo "\n";
	echo "****************************************************\n";
	echo "   END PERIOD " . $academic_period_info->name ;
	echo "****************************************************\n";
	echo "\n";

}

//MYSQL CLOSE
mysqli_close($con);

?>