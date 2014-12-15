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

//DATABASE:
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

	//Obtain all studies for current perior from Ldap

	$query_by_ap = "SELECT * 
	FROM studies
	INNER JOIN studies_academic_periods ON studies_academic_periods.studies_academic_periods_study_id = studies.studies_id
	INNER JOIN academic_periods ON academic_periods.academic_periods_id = studies_academic_periods.studies_academic_periods_academic_period_id
	WHERE academic_periods.academic_periods_shortname = '". $academic_period_info->name ."'";
	$result = mysqli_query($con,$query_by_ap);

	$studies = array();

	$enrolled_persons=0;

	while($row = mysqli_fetch_array($result)) {

		$study = new stdClass;
		$study->id = $row['studies_id'];
		$study->shortname = $row['studies_shortname'];
		$studies[$study->shortname] = $study;
		
		//Search al Accounts with jpegPhotos
		$filter="(destinationIndicator=" . $study->shortname . ")";
		
		echo "Searching Study: " . $study->shortname . " " . $filter . "...";

		$sr=ldap_search($ds,$academic_period_info->basedn, $filter);   

		$totalStudiesFound=ldap_count_entries($ds,$sr); 

		//TODO: 2 estudies FOUND. It could be: rare cases
		if ($totalStudiesFound == 0) {
			echo "Academic period: " . $academic_period_info->name . " Error: $totalStudiesFound found entries!\n";
			continue;
		}

		if ($totalStudiesFound > 1) {
			echo "OCO!: $totalCoursesFound found entries!\n";
			continue;
		}

		$info = ldap_get_entries($ds, $sr); 

		$dn = $info[0]["dn"];
		echo "FOUND! dn: " . $dn . "\n";

		echo " Searching students.... ";

		//Search al Accounts with jpegPhotos
		$filter="(objectClass=inetOrgperson)";
		$sr=ldap_search($ds,$dn,$filter); 

		$totalStudentsFound=ldap_count_entries($ds,$sr); 

		echo " found $totalStudentsFound\n";

		if ($totalStudentsFound > 0) {
			$info = ldap_get_entries($ds, $sr);
			for ($i=0; $i<$info["count"]; $i++  ) { 

				//print_r($info[$i]);
				
				$uid= $info[$i]["uid"][0];	

				$irispersonaluniqueid="";
				if (array_key_exists ( "irispersonaluniqueid" , $info[$i] )) {
					$irispersonaluniqueid=$info[$i]["irispersonaluniqueid"][0];	
				} 


				if (array_key_exists ( $uid , $persons )) {
					echo " i FOUND!: " . $i . "| dni:" . $irispersonaluniqueid . "| uid: " . $uid . "| database id: " . $persons[$uid]->id  . "\n"; 

					if (!mysqli_query($con,"INSERT INTO enrollment_studies (enrollment_studies_periodid,enrollment_studies_personid,enrollment_studies_study_id) VALUES ('" . $academic_period_info->name . "','" . $persons[$uid]->id .  "','" . $study->id ."')")) {
						//die('Error: ' . mysqli_error($con));
						echo " ERROR! " . mysqli_error($con) . "\n";
					} else {
						$enrolled_persons++;	
						echo " ENROLLED to table enrollment_studies!\n";
					}

				
				}	else {
					if ($irispersonaluniqueid != ""){ 
						if (array_key_exists ( $irispersonaluniqueid , $persons_dni )) {
							echo " i FOUND!: " . $i . "| dni:" . $irispersonaluniqueid . "| uid: " . $uid . "| database id: " . $persons_dni[$irispersonaluniqueid]->id; 
							if (!mysqli_query($con,"INSERT INTO  enrollment_studies (enrollment_studies_periodid,enrollment_studies_personid,enrollment_studies_study_id) VALUES ('" . $academic_period_info->name . "','" . $persons_dni[$irispersonaluniqueid]->id . "','". $study->id ."')")) {
								//die('Error: ' . mysqli_error($con));
								echo " ERROR! " . mysqli_error($con) . "\n";

							} else {
								$enrolled_persons++;	
								echo " ENROLLED!\n";
							}
							
						}
						else {
							echo " i NOT FOUND!: " . $i . "| dni:" . $irispersonaluniqueid . "| uid: " . $uid . "\n"; 
						}
					}
				}	

			}

		} else {
			echo "Error: $totalStudentsFound found entries!\n";
			continue;
		}
	
	}

	echo "\n";
	echo "****************************************************\n";
	echo "   END PERIOD " . $academic_period_info->name ;
	echo "****************************************************\n";
	echo "\n";
}


mysqli_close($con);
?>