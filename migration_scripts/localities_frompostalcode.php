<?php

/*
PREREQUISITES:
 Comment temporarily in first line of file /usr/share/ebre-escool/application/config/database.php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
*/

#IMPORTANT: no poseu les paraules de pas a aquest fitxer:
include "/usr/share/ebre-escool/application/config/database.php";

//MYSQL CONNECTION
$con=mysqli_connect($db['default']['hostname'],$db['default']['username'],$db['default']['password'],$db['default']['database']);
	// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

/* change character set to utf8 */
if (!mysqli_set_charset($con, "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($con));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($con));
}

$query="TRUNCATE TABLE locality";  
echo $query ."\n";
if (!mysqli_query($con, $query ))  {
	//ERROR
	echo "ERROR TRUNCATING TABLE locality!\n";
	die();
} else {
	echo "TABLE locality truncated Ok!\n";
}

//GET ALL INFO FORM DATABASE postalcode
$query = "SELECT postalcode_id,postalcode_code,postalcode_name,postalcode_localityid FROM postalcode";
echo $query . "\n";
$result = mysqli_query($con,$query);

$autoincrement=1;
while($row = mysqli_fetch_array($result)) {

	$locality_name = $row['postalcode_name'];
	$entryDate = "2014-06-30 00:00:00";
	$last_update = "2014-06-30 00:00:00";
	//Id of user 5273 is sergiturmigration. Put your required value here
	$creationUserId = 5273;
	$lastupdateUserId = 5273;
	$locality_markedForDeletion = "n";
	$locality_markedForDeletionDate = "0000-00-00 00:00:00";


	$locality_name = mysqli_real_escape_string($con, $locality_name);
	$entryDate = mysqli_real_escape_string($con, $entryDate);
	$last_update = mysqli_real_escape_string($con, $last_update);
	$creationUserId = mysqli_real_escape_string($con, $creationUserId);
	$lastupdateUserId = mysqli_real_escape_string($con, $lastupdateUserId);
	$locality_markedForDeletion = mysqli_real_escape_string($con, $locality_markedForDeletion);
	$locality_markedForDeletionDate = mysqli_real_escape_string($con, $locality_markedForDeletionDate);

	$query = "INSERT INTO locality (locality_id,locality_name,locality_postalcodename, locality_entryDate, locality_last_update, locality_creationUserId, 
		                            locality_lastupdateUserId, locality_markedForDeletion, locality_markedForDeletionDate)
						  VALUES (" . $autoincrement . ",'" . $locality_name . "','" . $locality_name . "','" . $entryDate . "','" . $last_update . "','" . $creationUserId . "','" . $lastupdateUserId . "','" . $locality_markedForDeletion . "','" . $locality_markedForDeletionDate ."')";

	//INSERT INTO `locality`(`locality_id`, `locality_name`, `locality_parent_locality_id`, `locality_state_id`, `locality_ine_id`, `locality_aeat_id`, `locality_entryDate`, `locality_last_update`, `locality_creationUserId`, `locality_lastupdateUserId`, `locality_markedForDeletion`, `locality_markedForDeletionDate`) 
	//					  VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12])

	echo $query."\n";
	if (!mysqli_query($con,$query)) {
		$error_number= mysqli_errno($con);
		if ($error_number == 1062) {
			echo "SKIP DUPLICATED LOCALITY: " . $locality_name . "! \n";
		} else {
			die('Error: ' . mysqli_error($con) . " Error number: " . $error_number);
			//echo " ERROR! " . mysqli_error($con) . "\n";	
		}
		
	} else {
		$autoincrement++;
		$insert_id = mysqli_insert_id($con);
		echo "INSERTED OK. ID: " . $insert_id . "!\n";
		$query = "UPDATE postalcode SET postalcode_localityid=" . $insert_id . " WHERE postalcode_id=" . $row['postalcode_id'];
		echo $query."\n";
		if (!mysqli_query($con,$query)) {
			die('Error: ' . mysqli_error($con));
			echo " ERROR! " . mysqli_error($con) . "\n";
		} else {
			echo "UPDATED OK!\n";			
		}
	}
}


/* TODO

//GET ALL locality info for current people at table person
$query="SELECT person_locality_id, person_locality_name, state, postalcode, count( `person_id` ) AS total
FROM person
GROUP BY person_locality_id, person_locality_name, state, postalcode
ORDER BY total DESC";
echo $query . "\n";
$result = mysqli_query($con,$query);

while($row = mysqli_fetch_array($result)) {
	//person_locality_id 	person_locality_name 	state 	postalcode 	total
}
*/

?>