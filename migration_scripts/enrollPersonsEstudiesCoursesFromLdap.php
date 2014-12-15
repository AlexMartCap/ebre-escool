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
	//Obtain all courses form current period from SQL
	$query_courses_from_current_period="SELECT * 
	FROM course
	INNER JOIN courses_academic_periods ON courses_academic_periods.courses_academic_periods_course_id = course.course_id
	INNER JOIN academic_periods ON academic_periods.academic_periods_id = courses_academic_periods.courses_academic_periods_academic_period_id
	WHERE  academic_periods.academic_periods_shortname='" . $academic_period_info->name . "'";
	$result = mysqli_query($con,$query_courses_from_current_period);

	$courses = array();

	$enrolled_persons=0;

	while($row = mysqli_fetch_array($result)) {

		$course = new stdClass;
		$course->id = $row['course_id'];
		$course->shortname = $row['course_shortname'];
		$course->study_id = $row['course_study_id'];
		$courses[$course->shortname] = $course;
		
		//Search al Accounts with jpegPhotos
		$filter="(postOfficeBox=" . $course->shortname . ")";
		
		echo "Searching Course: " . $course->shortname . " " . $filter . " ";

		$sr=ldap_search($ds,$academic_period_info->basedn, $filter);   

		$totalCoursesFound=ldap_count_entries($ds,$sr); 

		if ($totalCoursesFound == 0) {
			echo "Academic_period: " . $academic_period_info->name . " Error: $totalCoursesFound found entries!\n";
			continue;
		}

		if ($totalCoursesFound > 1) {
			echo "Academic_period: " . $academic_period_info->name . " OCO!: $totalCoursesFound found entries!\n";
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

					if (!mysqli_query($con,"INSERT INTO enrollment_courses (enrollment_courses_periodid,enrollment_courses_personid,enrollment_courses_study_id,enrollment_courses_course_id) VALUES ('" . $academic_period_info->name . "','" . $persons[$uid]->id . "','" . $course->study_id .  "','" . $course->id ."')")) {
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
							if (!mysqli_query($con,"INSERT INTO  enrollment_courses (enrollment_courses_periodid,enrollment_courses_personid,enrollment_courses_study_id,enrollment_courses_course_id) VALUES ('" . $academic_period_info->name . "','" . $persons_dni[$irispersonaluniqueid]->id .  "','" . $course->study_id .  "','". $course->id ."')")) {
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