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

include "/usr/share/ebre-escool/academicperiods.php";

include "/usr/share/ebre-escool/mysqlconnection.php";

//LDAP
include "/usr/share/ebre-escool/ldapconnection.php";

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
				echo " ENROLLED!\n";
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
						echo " ENROLLED!\n";
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