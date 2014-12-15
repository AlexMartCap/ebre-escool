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

$person_creationUserId = 2;
$person_lastupdateUserId = 2;

//TRUNCATE TABLES:

$query="TRUNCATE TABLE person";  
echo $query."\n";
if (!mysqli_query($con, $query ))  {
	//ERROR
	echo "ERROR TRUNCATING TABLE person!\n";
	die();
} else {
	echo "TABLE person truncated Ok!\n";
}

$query="TRUNCATE TABLE person_duplicatedfromldap";  
echo $query ."\n";
if (!mysqli_query($con, $query ))  {
	//ERROR
	echo "ERROR TRUNCATING TABLE person_duplicatedfromldap!\n";
	die();
} else {
	echo "TABLE person_duplicatedfromldap truncated Ok!\n";
}

$query="TRUNCATE TABLE users";  
echo $query ."\n";
if (!mysqli_query($con, $query ))  {
	//ERROR
	echo "ERROR TRUNCATING TABLE users!\n";
	die();
} else {
	echo "TABLE users truncated Ok!\n";
}


$query="INSERT INTO `person` (`person_id`, `person_givenName`, `person_sn1`, `person_sn2`, `person_email`, `person_secondary_email`, `person_terciary_email`, `person_official_id`, `person_official_id_type`, `person_date_of_birth`, `person_gender`, `person_secondary_official_id`, `person_secondary_official_id_type`, `person_homePostalAddress`, `person_photo`, `person_locality_id`, `person_locality_name`, `person_telephoneNumber`, `person_mobile`, `person_bank_account_id`, `person_notes`, `person_entryDate`, `person_last_update`, `person_creationUserId`, `person_lastupdateUserId`, `person_markedForDeletion`, `person_markedForDeletionDate`, `dn`, `date_of_birth`, `user_type`, `username_original_ldap`, `calculated_username`, `duplicated_username`, `original_username`, `uidnumber`, `homedirectory`, `employeenumber`, `employeetype`, `createbydn`, `modifiedbydn`, `userpassword`, `postalcode`, `state`) VALUES
(1, 'Sergi', 'Tur', 'Badenas', 'sergitur@iesebre.com', 'sergi.tur@upc.edu', '', '14268002K', 0, '1978-03-02', 'M', '', 999, 'Alcanyiz 26 Atic 2', 'sergitur.jpg', 99999, '', '', '', 9999, 'Imported using script personsFromLdapToDatabase.php', '2011-09-14 15:32:37', '2011-10-14 07:45:38', " . $person_creationUserId . ", " . $person_lastupdateUserId . ", 'n', '0000-00-00 00:00:00', 'cn=admin Tur AsAdmin,ou=people,ou=maninfo,ou=Personal,ou=All,dc=iesebre,dc=com', '1978-03-02', 3, 'sergitur', 'adminadmin', 0, 'adminadmin', 2262, '/home/sergitur', 999999, '', 'cn=Sergi Tur AsAdmin,ou=people,ou=maninfo,ou=Personal,ou=All,dc=iesebre,dc=com', 'cn=admin Tur AsAdmin,ou=people,ou=maninfo,ou=Personal,ou=All,dc=iesebre,dc=com', '{MD5}mjkX+mxCHX+1FbE7OPo8DQ==', '', '')";
echo $query ."\n";
if (!mysqli_query($con, $query ))  {
	//ERROR
	echo "ERROR INSERTING FIXED PERSONS!\n";
	die();
} else {
	echo "OK INSERTING FIXED PERSONS!\n";
}


$query="INSERT INTO users (id,person_id,username,password,mainOrganizationaUnitId,email,secondary_email,created_on,active) VALUES ('1','1','admin','9a3917fa6c421d7fb515b13b38fa3c0d','99','sergitur@iesebre.com','sergi.tur@upc.edu','2011-09-14 15:32:37','1')";
echo $query ."\n";
if (!mysqli_query($con, $query ))  {
	//ERROR
	echo "ERROR INSERTING FIXED USERS 1!\n";
	die();
} else {
	echo "OK INSERTING FIXED USERS 1 !\n";
}

$query="INSERT INTO users (id,person_id,username,password,mainOrganizationaUnitId,email,secondary_email,created_on,active) VALUES ('2','1','admin_migration','9a3917fa6c421d7fb515b13b38fa3c0d','99','sergitur@iesebre.com','sergi.tur@upc.edu','2011-09-14 15:32:37','1')";
echo $query ."\n";
if (!mysqli_query($con, $query ))  {
	//ERROR
	echo "ERROR INSERTING FIXED USERS 2 !\n";
	die();
} else {
	echo "OK INSERTING FIXED USERS 2!\n";
}

//LDAP
$ldapconfig['host'] = $config['hosts'][0];
#Només cal indicar el port si es diferent del port per defecte
$ldapconfig['port'] = NULL;
//$ldapconfig['basedn'] = $config['basedn'];

$basedn="dc=iesebre,dc=com";

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


$filter="(objectClass=inetOrgPerson)";
$sr=ldap_search($ds,$basedn, $filter);   
$totalPersons=ldap_count_entries($ds,$sr); 

echo "Persones potencials a matrícular:".$totalPersons."\n";

$localities= getLocalities($con);

echo "Poblacions trobades: ". count($localities) ."\n";

$info = ldap_get_entries($ds, $sr); 
echo "Data for ".$info["count"]." items returned:\n"; 

$users_to_skip = array ( "root", "nobody", "alumnenou", "profenou", "admin","sergitur", "201213alumnenou","prova","201213cooperativa","iewu5fvextrty");

$skipped = 0; 
$skipped_withoutuid = 0;
$skipped_duplicate_id = 0;
$skipped_duplicated_twotimes= 0;
$added_persons = 0;
$duplicated_usernames = 0;

$fist_invented_dni=99999001;

for ($i=0; $i<$info["count"]; $i++  ) { 

	echo "************************************************\n";
	echo "* Iteration begin: " . $i . "\n";
	echo "************************************************\n";
	//Init variables
	$person_givenName = ""; 
	$person_sn1 = "";
	$person_sn2 = "";
	$person_email = "";
	$person_secondary_email = "";
	$person_terciary_email = "";
	$person_official_id = "";
	$person_official_id_type = 999;
	$person_date_of_birth = "0000-00-00";
	$person_gender = "";
	$person_secondary_official_id = "";
	$person_secondary_official_id_type = 999;
	$person_homePostalAddress = "";
	$person_photo = "";
	$person_locality_id = 99999;
	$person_locality_name = "";
	$person_telephoneNumber = "";
	$person_mobile ="";
	$person_bank_account_id = 999999;
	$person_notes = "";
	$person_entryDate = "2014-06-30 00:00:00";
	$person_last_update = "2014-06-30 00:00:00";
	//Id of user 5273 is sergiturmigration. Put your required value here
	
	$person_markedForDeletion = "n";
	$person_markedForDeletionDate = "0000-00-00 00:00:00";
	$dn = "";
	$date_of_birth = "0000-00-00";
	$user_type = 999;
	$username = "";	

	$person_uidnumber = 999999;
	$person_homedirectory = "";
	$person_employeenumber = 999999;
	$person_employeetype = "";
	$person_userpassword = "";
	$person_postalcode = "";
	$person_state = "";

	//print_r($info[$i]);

	$dn = $info[$i]["dn"];
		
	$uid="";	
	if (array_key_exists ( "uid" , $info[$i] )) {
		$uid= $info[$i]["uid"][0];	
	} else {
		$uid= "";	
		echo "DN: " . $dn . " . No té uid. SKIPPED!!!\n";
		$skipped++; 
		$skipped_withoutuid++;
		continue;
	}

	//USERS TO SKIP:
	if ( in_array ( $uid , $users_to_skip ) ) {
		echo "uid: " . $uid . " . SKIPPED!!!\n";
		$skipped++; 
		continue;
	}

	$irispersonaluniqueid="";

	if (array_key_exists ( "irispersonaluniqueid" , $info[$i] )) {
		$irispersonaluniqueid=$info[$i]["irispersonaluniqueid"][0];	
		
		echo "uid: " . $uid . " . ID: " . $irispersonaluniqueid .  "\n";
	} else {
		echo "uid: " . $uid . " . ID doest not exists. DN: " . $dn . ". NIF: " . $fist_invented_dni . LetraNIF('$fist_invented_dni') . "." . "ERROR!!!\n";
		$fist_invented_dni++;
	}

	if (array_key_exists ( "givenname" , $info[$i] )) {
		$person_givenName= $info[$i]["givenname"][0];	
	}
	if (array_key_exists ( "sn1" , $info[$i] )) {
		$person_sn1= $info[$i]["sn1"][0];	
	}
	if (array_key_exists ( "sn2" , $info[$i] )) {
		$person_sn2= $info[$i]["sn2"][0];	
	}
	if (array_key_exists ( "email" , $info[$i] )) {
		$person_email= $info[$i]["email"][0];	
	}
	if (array_key_exists ( "highschoolpersonalemail" , $info[$i] )) {
		$person_secondary_email= $info[$i]["highschoolpersonalemail"][0];	 
	}
	
	//THIS DATA DOES NOT EXISTS ON LDAP
	$person_terciary_email="";

	$person_official_id= strtoupper($irispersonaluniqueid);

	if (array_key_exists ( "irispersonaluniqueidtype" , $info[$i] )) {
		$person_official_id_type= $info[$i]["irispersonaluniqueidtype"][0];	
	}
	if (array_key_exists ( "dateofbirth" , $info[$i] )) {
		$person_date_of_birth= $info[$i]["dateofbirth"][0];	
	}
	if (array_key_exists ( "gender" , $info[$i] )) {
		$person_gender= $info[$i]["gender"][0];	
	}
	if (array_key_exists ( "highschooltsi" , $info[$i] )) {
		$person_secondary_official_id= $info[$i]["highschooltsi"][0];	
		$person_secondary_official_id_type= 4;	
	} 
	if (array_key_exists ( "homepostaladdress" , $info[$i] )) {
		$person_homePostalAddress = $info[$i]["homepostaladdress"][0];
	}

	$person_photo = $uid . ".jpg";

	if (array_key_exists ( "l" , $info[$i] )) {
		$person_locality_name= $info[$i]["l"][0];	
	}

	if ($person_locality_name != "") {
		if (array_key_exists ( strtolower($person_locality_name) , $localities) ) {
			$person_locality_id = $localities[strtolower($person_locality_name)]->id;
		}
	}

	if (array_key_exists ( "homephone" , $info[$i] )) {
		$person_telephoneNumber= $info[$i]["homephone"][0];	
	}
	
	if (array_key_exists ( "mobile" , $info[$i] )) {
		$person_mobile= $info[$i]["mobile"][0];	
	}

	//Fields: person_entryDate person_last_update person_creationUserId person_lastupdateUserId
	if (array_key_exists ( "highschoolusercreatedby" , $info[$i] )) {
		$person_createbydn = $info[$i]["highschoolusercreatedby"][0];	
	}

	if (array_key_exists ( "highschooluserlastmodifiedby" , $info[$i] )) {
		$person_modifiedbydn = $info[$i]["highschooluserlastmodifiedby"][0];	
	}

	if (array_key_exists ( "highschoolusercreatedat" , $info[$i] )) {
		//Format date example :  2014-06-30 00:00:00
		$person_entryDate = date("Y-m-d G:i:s",$info[$i]["highschoolusercreatedat"][0]);	
	}

	if (array_key_exists ( "highschooluserlastmodifiedat" , $info[$i] )) {
		$person_last_update = date("Y-m-d G:i:s",$info[$i]["highschooluserlastmodifiedat"][0]);	
	}

	if (array_key_exists ( "uidnumber" , $info[$i] )) {
		$person_uidnumber = $info[$i]["uidnumber"][0];	
	}
	if (array_key_exists ( "homedirectory" , $info[$i] )) {
		$person_homedirectory = $info[$i]["homedirectory"][0];	
	}
	if (array_key_exists ( "employeenumber" , $info[$i] )) {
		$person_employeenumber = $info[$i]["employeenumber"][0];	
	}
	if (array_key_exists ( "employeetype" , $info[$i] )) {
		$person_employeetype = $info[$i]["employeetype"][0];	
	}
	if (array_key_exists ( "userpassword" , $info[$i] )) {
		$person_userpassword = $info[$i]["userpassword"][0];	
	}
	if (array_key_exists ( "postalcode" , $info[$i] )) {
		$person_postalcode = $info[$i]["postalcode"][0];	
	}
	if (array_key_exists ( "st" , $info[$i] )) {
		$person_state = $info[$i]["st"][0];	
	}

	$person_bank_account_id=9999;
	
	$person_notes ="Imported using script personsFromLdapToDatabase.php";

	//ESCAPE CHARACTER like ' to avoid SQL ERRORS!
	$person_givenName = mysqli_real_escape_string($con, $person_givenName);
	$person_sn1 = mysqli_real_escape_string($con, $person_sn1);
	$person_sn2 = mysqli_real_escape_string($con, $person_sn2);
	$person_email = mysqli_real_escape_string($con, $person_email);
	$person_secondary_email = mysqli_real_escape_string($con, $person_secondary_email);
	$person_terciary_email = mysqli_real_escape_string($con, $person_terciary_email);
	$person_official_id = mysqli_real_escape_string($con, $person_official_id);
	//number then not requires
	//$person_official_id_type = mysqli_real_escape_string($con, $person_official_id_type);
	$person_date_of_birth = mysqli_real_escape_string($con, $person_date_of_birth);
	$person_gender = mysqli_real_escape_string($con, $person_gender);
	$person_secondary_official_id = mysqli_real_escape_string($con, $person_secondary_official_id);
	//number then not requires
	//$person_secondary_official_id_type = mysqli_real_escape_string($con, $person_secondary_official_id_type);
	$person_secondary_official_id_type = mysqli_real_escape_string($con, $person_secondary_official_id_type);
	$person_homePostalAddress = mysqli_real_escape_string($con, $person_homePostalAddress);
	$person_gender = mysqli_real_escape_string($con, $person_gender);
	$person_photo = mysqli_real_escape_string($con, $person_photo);
	$person_locality_id = mysqli_real_escape_string($con, $person_locality_id);
	$person_locality_name = mysqli_real_escape_string($con, $person_locality_name);
	$person_telephoneNumber = mysqli_real_escape_string($con, $person_telephoneNumber);
	$person_mobile = mysqli_real_escape_string($con, $person_mobile);
	$person_notes = mysqli_real_escape_string($con, $person_notes);
	$dn = mysqli_real_escape_string($con, $dn);

	$person_userpassword = mysqli_real_escape_string($con, $person_userpassword);
	$person_postalcode = mysqli_real_escape_string($con, $person_postalcode);
	$person_state = mysqli_real_escape_string($con, $person_state);
	


	$person = new stdClass;

	$person->person_givenName = $person_givenName;
	$person->person_sn1 = $person_sn1;
	$person->person_sn2 = $person_sn2;
	$person->person_email = $person_email;
	$person->person_secondary_email = $person_secondary_email;
	$person->person_terciary_email = $person_terciary_email;
	$person->person_official_id = $person_official_id;
	$person->person_official_id_type = $person_official_id_type;
	$person->person_date_of_birth = $person_date_of_birth;
	$person->person_gender = $person_gender;
	$person->person_secondary_official_id = $person_secondary_official_id;
	$person->person_secondary_official_id_type = $person_secondary_official_id_type;
	$person->person_homePostalAddress = $person_homePostalAddress;
	$person->person_photo = $person_photo;
	$person->person_locality_id = $person_locality_id;
	$person->person_locality_name = $person_locality_name;
	$person->person_telephoneNumber = $person_telephoneNumber;
	$person->person_mobile = $person_mobile;
	$person->person_bank_account_id = $person_bank_account_id;
	$person->person_notes = $person_notes;
	$person->person_entryDate = $person_entryDate;
	$person->person_last_update = $person_last_update;
	$person->person_creationUserId = $person_creationUserId;
	$person->person_lastupdateUserId = $person_lastupdateUserId;
	$person->person_markedForDeletion = $person_markedForDeletion;
	$person->person_markedForDeletionDate = $person_markedForDeletionDate;
	$person->createbydn = $person_createbydn;
	$person->modifiedbydn = $person_modifiedbydn;

	$person->uidnumber = $person_uidnumber;
	$person->homedirectory = $person_homedirectory;
	$person->employeenumber = $person_employeenumber;
	$person->employeetype = $person_employeetype;
	$person->userpassword = $person_userpassword;
	$person->postalcode = $person_postalcode;
	$person->state = $person_state;

	$person->dn = $dn;
	$person->date_of_birth = $person_date_of_birth;
	$person->user_type = getUserType($dn);

	//TODO: Calculate username : givenName + sn1 + (OPTIONAL IF REPEATED) x ON x és un número incremental (1,2,3,4,...,10,11,..., 20,21,..., 100,101...)
	$person->username = calculateusername($person_givenName, $person_sn1,$con);	

	$normal_username = replaceAccents(strtolower(trim($person_givenName)) . strtolower(trim($person_sn1)));
	$normal_username=preg_replace('/\s+/', '', $normal_username);
	$person->original_username = $normal_username;

	$person->duplicated_username = false;
	if ( $person->username != $normal_username) {
		echo "USERNAME CALCULATED TO: " . $person->username . " FOR " . $person_sn1 . " " . $person_sn2 . ", " .  $person_givenName . " ID: " . $person_official_id  . "\n";
		$person->duplicated_username = true;
		
	}

	$person->username_ldap = $uid;

	$result = addPersonToDatabase($con,$person);

	switch ($result) {
	    case 0:
	        echo "i equals 0";
	        break;
	    case 1:
	        $added_persons++;
	        break;
	    case 2:
	        $added_persons++;
	        $duplicated_usernames++;
	        break;
	    case 3:
	        $skipped++;
			$skipped_duplicate_id++;    
	        break;
	    case 4:
	    	$skipped++;
	    	$skipped_duplicated_twotimes++;    
	        echo "ERROR! TWO TIMES DUPLICATED\n";
	        break;        
	    case 99:
	        die("ERROR! executing addPersonToDatabase function!");
	        break;    
	}

	echo "************************************************\n";
	echo "* Iteration END: " . $i . "\n";
	echo "************************************************\n";

}

echo "************************************************\n";
echo "* END. STATISTICS: \n";
echo "* Added persons: " . $added_persons . " \n";
echo "* Skipped: " . $skipped . " \n";
echo "* Skipped by hardcode (array users_to_skip ): " . count($users_to_skip) . " \n";
echo "* Skipped without uid: " . $skipped_withoutuid . " \n";
echo "* Skipped & duplicated (inserted at table person_duplicatedfromldap): " . $skipped_duplicate_id . " \n";
echo "* Skipped & duplicated two times (NOT inserted at any table): " . $skipped_duplicated_twotimes . " \n";
echo "* Duplicated usernames: " . $duplicated_usernames . " \n";
echo "* Iterations: " . $i . " \n";
echo "* TotalPersons: " . $totalPersons . " \n";
echo "************************************************\n";


function getUserType($dn) {

	if (strstr($dn, "Alumnes")) {
   		return 1;
	} elseif (strstr($dn, "Profes")) {
   		return 2;
	} elseif (strstr($dn, "Personal")) {
		return 3;
	} else {
		return 9999;
	}

	return 9999;
} 

function getLocalities($con) {

	//SELECT
	$result = mysqli_query($con,"SELECT * FROM  locality INNER JOIN postalcode ON postalcode.postalcode_localityid = locality.locality_id");
	$localities = array();
	$counter=0;
	while($row = mysqli_fetch_array($result)) {
		$locality = new stdClass;
		$locality->id = $row['locality_id'];
		$locality->name = $row['locality_name'];
		$locality->name = $row['locality_name'];
		$locality->locality_parent_locality_id = $row['locality_parent_locality_id'];
		$locality->locality_state_id = $row['locality_state_id'];
		$locality->locality_postal_code = $row['postalcode_code'];

		$localities[strtolower($row['locality_name'])] = $locality;
		$counter++;
	}
	echo "Counter: " . $counter . "\n";
	return $localities;
}

function getLocalities_by_postalcode($con) {

	//SELECT
	$result = mysqli_query($con,"SELECT * FROM  locality");
	$localities = array();
	$counter=0;
	while($row = mysqli_fetch_array($result)) {
		$locality = new stdClass;
		$locality->id = $row['locality_id'];
		$locality->name = $row['locality_name'];
		$locality->name = $row['locality_name'];
		$locality->locality_parent_locality_id = $row['locality_parent_locality_id'];
		$locality->locality_state_id = $row['locality_state_id'];
		$locality->locality_state_id = $row['locality_state_id'];
		$locality->locality_postal_code = $row['locality_postal_code'];

		$localities[strtolower($row['locality_postal_code'])] = $locality;
		$counter++;
	}
	echo "Counter: " . $counter . "\n";
	return $localities;
}

/*OBSOLET
function getIdForLocality($con,$person_locality_name) {

	//SELECT
	$result = mysqli_query($con,"SELECT * FROM  locality");
	$localities = array();
$persons_dni = array();
while($row = mysqli_fetch_array($result)) {
	$person = new stdClass;
	$person->id = $row['person_id'];

	$persons[$row['username']] = $person;
	$persons_dni[$row['person_official_id']] = $person;
}


	//FOUND?
		//RETURN ID
	//NOT FOUND?
		//Add locality
	return 9999;
}*/

function check_if_user_exists($username,$con) {

	$query="SELECT calculated_username FROM person WHERE calculated_username='". $username ."'";
	//echo $query;
	$result = mysqli_query($con, $query );
	if ( !$result ) {
		$error_number= mysqli_errno($con);
		echo " ERROR! Number: ". $error_number . " Message: " . mysqli_error($con) . "\n";
		die();
	} else {
		//QUERY OK. CHECK NUM OF ROWS

		if ($result->num_rows > 0) {
			 /* free result set */
    		$result->close();
			return true;
		} else {
			 /* free result set */
    		$result->close();
			return false;
		}

	}
	
}

function replaceAccents($str)
{
  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
  return str_replace($a, $b, $str);
}

function calculateusername($person_givenName, $person_sn1,$con) {
	$username = replaceAccents(strtolower(trim($person_givenName)) . strtolower(trim($person_sn1)));
	$username=preg_replace('/\s+/', '', $username);
    $index=1;

    $user_new = $username;
    do{
        $user_exists = check_if_user_exists($user_new,$con);
        if($user_exists){
            $user_new = $username . $index;
            $index++;
        }
    }while($user_exists==true);
    
	return $user_new;
}

function addPersonToDatabase($con, $person) {

	//INSERT TO DATABASE
	/*$query="INSERT INTO person (person_givenName,person_sn1,person_sn2,person_email,person_secondary_email,person_terciary_email,
								 person_official_id,person_official_id_type,person_date_of_birth,person_gender,person_secondary_official_id,person_secondary_official_id_type,person_homePostalAddress,person_photo,person_locality_id,person_locality_name,person_telephoneNumber,person_mobile,person_bank_account_id,person_notes,person_entryDate,person_last_update,person_creationUserId,person_lastupdateUserId,person_markedForDeletion,person_markedForDeletionDate,dn,date_of_birth,user_type,username) 
		        VALUES ('" . $person->person_givenName . "','" . $person->person_sn1 . "','" . $person->person_sn2 . "','" .
						$person->person_email . "','" . $person->person_secondary_email . "','" . $person->person_terciary_email . "','" . $person->person_official_id . "'," . $person->person_official_id_type . ",'" . $person->person_date_of_birth . "','" . $person->person_gender . "','" . $person->person_secondary_official_id . "'," . $person->person_secondary_official_id_type . ",'" . $person->person_homePostalAddress . "','" . $person->person_photo . "','" . $person->person_locality_id . "','" . $person->person_locality_name . "','" . $person->person_telephoneNumber . "','" . $person->person_mobile . "','". $person->person_bank_account_id . "','" . $person->person_notes . "','" . $person->person_entryDate . "','" . $person->person_last_update . "','" . $person->person_creationUserId . "','" . $person->person_lastupdateUserId . "','" . $person->person_markedForDeletion . "','" . $person->person_markedForDeletionDate . "','" . $person->dn . "','" . $person->date_of_birth . "','" . $person->user_type . "','"
   						    . $person->username 
		        	        .  "')";*/

	//DEBUG:
	print_r($person);

	$query="INSERT INTO person (person_givenName,person_sn1,person_sn2,person_email,person_secondary_email,person_terciary_email,
								 person_official_id,person_official_id_type,person_date_of_birth,person_gender,person_secondary_official_id,person_secondary_official_id_type,person_homePostalAddress,person_photo,person_locality_id,person_locality_name,person_telephoneNumber,person_mobile,person_bank_account_id,person_notes,person_entryDate,person_last_update,person_creationUserId,person_lastupdateUserId,createbydn,modifiedbydn,person_markedForDeletion,person_markedForDeletionDate,dn,date_of_birth,user_type,uidnumber,homedirectory,employeenumber,employeetype,userpassword,username_original_ldap,calculated_username,duplicated_username,original_username,postalcode,state) 
		        VALUES ('" . $person->person_givenName . "','" . $person->person_sn1 . "','" . $person->person_sn2 . "','" .
						$person->person_email . "','" . $person->person_secondary_email . "','" . $person->person_terciary_email . "','" . $person->person_official_id . "'," . $person->person_official_id_type . ",'" . $person->person_date_of_birth . "','" . $person->person_gender . "','" . $person->person_secondary_official_id . "'," . $person->person_secondary_official_id_type . ",'" . $person->person_homePostalAddress . "','" . $person->person_photo . "','" . $person->person_locality_id . "','" . $person->person_locality_name . "','" . $person->person_telephoneNumber . "','" . $person->person_mobile . "','". $person->person_bank_account_id . "','" . $person->person_notes . "','" . $person->person_entryDate . "','" . $person->person_last_update . "','" . $person->person_creationUserId . "','" . $person->person_lastupdateUserId . "','" . $person->createbydn . "','" . $person->modifiedbydn . "','" . $person->person_markedForDeletion . "','" . $person->person_markedForDeletionDate . "','" . $person->dn . "','" . $person->date_of_birth . "','" . $person->user_type . "','" . $person->uidnumber . "','" . $person->homedirectory . "','" . $person->employeenumber . "','" . $person->employeetype . "','" . $person->userpassword . "','" . $person->username_ldap . "','" . $person->username . "','" . $person->duplicated_username . "','" . $person->original_username . "','" . $person->postalcode . "','" . $person->state . "')";  

	echo $query;
	if (!mysqli_query($con, $query )) 
		{
			$error_number= mysqli_errno($con);
			echo " ERROR! Number: ". $error_number . " Message: " . mysqli_error($con) . "\n";
			if ($error_number == 1062) {
				echo "SKIP DUPLICATED ID! Adding to table person_duplicatedfromldap\n";
				$query="INSERT INTO person_duplicatedfromldap (person_givenName,person_sn1,person_sn2,person_email,person_secondary_email,person_terciary_email,
								 person_official_id,person_official_id_type,person_date_of_birth,person_gender,person_secondary_official_id,person_secondary_official_id_type,person_homePostalAddress,person_photo,person_locality_id,person_locality_name,person_telephoneNumber,person_mobile,person_bank_account_id,person_notes,person_entryDate,person_last_update,person_creationUserId,person_lastupdateUserId,createbydn,modifiedbydn,person_markedForDeletion,person_markedForDeletionDate,dn,date_of_birth,user_type,uidnumber,homedirectory,employeenumber,employeetype,userpassword,username_original_ldap,calculated_username,duplicated_username,original_username,postalcode,state)  
		        		VALUES ('" . $person->person_givenName . "','" . $person->person_sn1 . "','" . $person->person_sn2 . "','" .
						$person->person_email . "','" . $person->person_secondary_email . "','" . $person->person_terciary_email . "','" . $person->person_official_id . "'," . $person->person_official_id_type . ",'" . $person->person_date_of_birth . "','" . $person->person_gender . "','" . $person->person_secondary_official_id . "'," . $person->person_secondary_official_id_type . ",'" . $person->person_homePostalAddress . "','" . $person->person_photo . "','" . $person->person_locality_id . "','" . $person->person_locality_name . "','" . $person->person_telephoneNumber . "','" . $person->person_mobile . "','". $person->person_bank_account_id . "','" . $person->person_notes . "','" . $person->person_entryDate . "','" . $person->person_last_update . "','" . $person->person_creationUserId . "','" . $person->person_lastupdateUserId . "','" . $person->createbydn . "','" . $person->modifiedbydn . "','" . $person->person_markedForDeletion . "','" . $person->person_markedForDeletionDate . "','" . $person->dn . "','" . $person->date_of_birth . "','" . $person->user_type . "','" . $person->uidnumber . "','" . $person->homedirectory . "','" . $person->employeenumber . "','" . $person->employeetype . "','" . $person->userpassword . "','" . $person->username_ldap . "','" . $person->username . "','" . $person->duplicated_username . "','" . $person->original_username . "','" . $person->postalcode . "','" . $person->state . "')";  
				echo $query . "\n";
				if (mysqli_query($con, $query )) {
					echo "Correctly inserted to person_duplicatedfromldap!\n";
					return 3;
				} else {
					if ($error_number == 1062) {
						echo "Two times duplicated!\n";
						return 4;
					} else {
						die('Error: ' . mysqli_error($con));	
					}	
				}				
			} else {
				die('Error: ' . mysqli_error($con));	
			}			
		} else {
			echo " ADDED OK!\n";

				//Add username
				$insert_id = mysqli_insert_id($con);
				$password="";
				if ($person->userpassword!="") {
					$password_withoutmd5string = str_replace("{MD5}","",$person->userpassword);
					$password_base64uncoded = base64_decode($password_withoutmd5string);
					$password_array = unpack('H*',$password_base64uncoded);
					$password = $password_array[1];
				}

				echo "person->userpassword: " . $person->userpassword . "\n";
				echo "password_withoutmd5string: " . $password_withoutmd5string . "\n";
				echo "password_base64uncoded: " . $password_base64uncoded . "\n";
				echo "password_array: " . print_r($password_array) . "\n";
				echo "password: " . $password . "\n";

				$mainOrganizationaUnitId=99;
				$active = 1;
				$query="INSERT INTO users (person_id,username,password,mainOrganizationaUnitId,email,secondary_email,created_on,active)								 
		        		VALUES ('" . $insert_id . "','" . $person->username . "','" . $password . "','" .
						$mainOrganizationaUnitId . "','" . $person->person_email . "','" . $person->person_secondary_email . "','" . $person->person_entryDate . "','" . $active . "')";  
				echo $query . "\n";
				if (mysqli_query($con, $query )) {
					echo "Correctly inserted to users table!\n";
				} else {
					$error_number= mysqli_errno($con);
					die('Error: ' . mysqli_error($con) . ". Error number: " . $error_number);						
				}
			if ($person->duplicated_username) {
				echo " ADDED OK!\n";
				echo "INSERTED OK USERNAME CALCULATED TO: " . $person->username . " FOR " . $person->person_sn1 . " " . $person->person_sn2 . ", " .  $person->person_givenName . " ID: " . $person->person_official_id  . "\n";			
				return 2;
			}
			return 1;
		}
	return 99;	
}


# http://www.lawebdelprogramador.com # 
function LetraNIF ($dni) { 
	/* Obtiene letra del NIF a partir del DNI */ 
	$valor= (int) ($dni / 23); 
	$valor *= 23; 
	$valor= $dni - $valor; 
	$letras= "TRWAGMYFPDXBNJZSQVHLCKEO"; 
	$letraNif= substr ($letras, $valor, 1); 
	return $letraNif; 
}

?>