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
	echo "\n";
	echo "****************************************************\n";
	echo "   STARTING PERIOD " . $academic_period_info->name . "\n" ;
	echo "****************************************************\n";
	echo "";
	//Obtain all classroomgroups from database
	$query_all_classroomgroups_from_current_period = "SELECT * 
	FROM classroom_group 
	INNER JOIN classroom_group_academic_periods ON classroom_group_academic_periods.classroom_group_academic_periods_classroom_group_id = classroom_group.classroom_group_id
	INNER JOIN academic_periods ON academic_periods.academic_periods_id = classroom_group_academic_periods.classroom_group_academic_periods_academic_period_id
	INNER JOIN course ON course.course_id = classroom_group.classroom_group_course_id
	WHERE academic_periods.academic_periods_shortname='". $academic_period_info->name ."'";
	$result = mysqli_query($con,$query_all_classroomgroups_from_current_period);

	$classroomgroups = array();
	$enrolled_persons=0;

	while($row = mysqli_fetch_array($result)) {
		$classroomgroup = new stdClass;
		$classroomgroup->id = $row['classroom_group_id'];
		$classroomgroup->group_code = $row['classroom_group_code'];
		$classroomgroup->shortName = $row['classroom_group_shortName'];
		$classroomgroup->course_id = $row['classroom_group_course_id'];
		$classroomgroup->study_id = $row['course_study_id'];

		$enrollment_class_group_entryDate = "2014-06-20 00:00:00";
		$enrollment_class_group_last_update = "2014-06-20 00:00:00";
		$enrollment_class_group_creationUserId = 1421;
		$enrollment_class_group_lastupdateUserId = 1421;
		$enrollment_class_group_markedForDeletion = "n";
		$enrollment_class_group_markedForDeletionDate = "0000-00-00 00:00:00" ;

		$classroomgroups[$classroomgroup->group_code] = $classroomgroup;
		
		//Search al Accounts with jpegPhotos
		$filter="(physicalDeliveryOfficeName=" . $classroomgroup->group_code . ")";
		
		echo "Searching Classroom_group: " . $classroomgroup->group_code . " " . $filter . " ";

		$sr=ldap_search($ds,$academic_period_info->basedn, $filter);   

		$totalClassroomGroupsFound=ldap_count_entries($ds,$sr); 

		if ($totalClassroomGroupsFound == 0) {
			echo "Academic_period: " . $academic_period_info->name . " Error: $totalClassroomGroupsFound found entries!\n";
			continue;
		}

		if ($totalClassroomGroupsFound > 1) {
			echo "Academic_period: " . $academic_period_info->name . " OCO!: $totalClassroomGroupsFound found entries!\n";
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

					if (!mysqli_query($con,"INSERT INTO enrollment_class_group (enrollment_class_group_periodid,enrollment_class_group_personid,enrollment_class_group_study_id,enrollment_class_group_course_id,enrollment_class_group_group_id,enrollment_class_group_entryDate,enrollment_class_group_last_update,enrollment_class_group_creationUserId,enrollment_class_group_lastupdateUserId,enrollment_class_group_markedForDeletion,enrollment_class_group_markedForDeletionDate) VALUES ('" . $academic_period_info->name . "','" . $persons[$uid]->id . "','" . $classroomgroup->study_id . "','" . $classroomgroup->course_id . "','" . $classroomgroup->id ."','" . $enrollment_class_group_entryDate . "','" . $enrollment_class_group_last_update . "','" . $enrollment_class_group_creationUserId . "','" . $enrollment_class_group_lastupdateUserId . "','" . $enrollment_class_group_markedForDeletion . "','" . $enrollment_class_group_markedForDeletionDate ."')")) {
						//die('Error: ' . mysqli_error($con));
						echo " ERROR! " . mysqli_error($con) . "\n";
					} else {
						$enrolled_persons++;	
						echo " ENROLLED!\n";
					}

				
				}	else {
					if ($irispersonaluniqueid != ""){ 
						if (array_key_exists ( $irispersonaluniqueid , $persons_dni )) {
							echo " i FOUND!: " . $i . "| dni:" . $irispersonaluniqueid . "| uid: " . $uid . "| database id: " . $persons_dni[$irispersonaluniqueid]->id; 
							if (!mysqli_query($con,"INSERT INTO  enrollment_class_group (enrollment_class_group_periodid,enrollment_class_group_personid,enrollment_class_group_study_id,enrollment_class_group_course_id,enrollment_class_group_group_id,enrollment_class_group_entryDate,enrollment_class_group_last_update,enrollment_class_group_creationUserId,enrollment_class_group_lastupdateUserId,enrollment_class_group_markedForDeletion,enrollment_class_group_markedForDeletionDate) VALUES ('" . $academic_period_info->name . "','" . $persons_dni[$irispersonaluniqueid]->id . "','" . $classroomgroup->study_id . "','" . $classroomgroup->course_id . "','" . $classroomgroup->id ."','" . $enrollment_class_group_entryDate . "','" . $enrollment_class_group_last_update . "','" . $enrollment_class_group_creationUserId . "','" . $enrollment_class_group_lastupdateUserId . "','" . $enrollment_class_group_markedForDeletion . "','" . $enrollment_class_group_markedForDeletionDate ."')")) {
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