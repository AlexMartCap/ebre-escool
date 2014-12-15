<?php

/*
PREREQUISITES:
 $ sudo apt-get install php5-imagick 
 Uncomment temporarily in first line of file /usr/share/ebre-escool/application/config/auth_ldap.php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
*/

#IMPORTANT: no poseu les paraules de pas a aquest fitxer:
include "/usr/share/ebre-escool/application/config/auth_ldap.php";

$destinationDIR=getcwd()."/uploads/person_photos";

echo "CURRENT DIRECTORY: " . getcwd() . "\n";

echo "Generating destination directory...\n";

mkdir($destinationDIR);



$ldapconfig['host'] = $config['hosts'][0];
#Només cal indicar el port si es diferent del port per defecte
$ldapconfig['port'] = NULL;
$ldapconfig['basedn'] = $config['basedn'];

$ds=ldap_connect($ldapconfig['host'], $ldapconfig['port']);

ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

$password=$config['proxy_pass'];
$dn=$config['proxy_user'];

$basedn=$ldapconfig['basedn'];

if ($bind=ldap_bind($ds, $dn, $password)) {
  echo("Login correct\n");
} else {
  # Error
}

//Obtain all users

//Search al Accounts with jpegPhotos
$filter="(jpegphoto=*)";
$sr=ldap_search($ds,$basedn, $filter);   

$totalUsers=ldap_count_entries($ds,$sr); 

echo "Usuaris totals:".$totalUsers."\n";

$info = ldap_get_entries($ds, $sr); 

echo "Data for ".$info["count"]." items returned:<p>"; 

for ($i=0; $i<$info["count"]; $i++  ) { 
	$uid= $info[$i]["uid"][0];
	$jpegphoto= $info[$i]["jpegphoto"][0];
	echo "i: "+$i . "|". $info[$i]["dn"] . " uid: " . $uid . "\n"; 


	if(class_exists('Imagick')){

	$im = new Imagick();
	$im->readImageBlob($jpegphoto);
	$im->setImageOpacity(1.0);
	//$im->resizeImage(147,200,Imagick::FILTER_UNDEFINED,0.5,TRUE);
	//$im->setCompressionQuality(90);
	$im->setImageFormat('jpeg'); 
	
	$destinationfilename=$destinationDIR."/".$uid .".jpg";
	echo "Creating file $destinationfilename...\n";
	$im->writeImage ($destinationfilename);

} else {
	echo "ERROR!";
}

} 


/*
$USERDN="cn=Tur AsAdmin Sergi,ou=people,ou=maninfo,ou=Personal,ou=All,dc=iesebre,dc=com";

$attrs=array();

if(class_exists('Imagick')){

	$im = new Imagick('/home/sergi/Escriptori/SergiTurGosa.jpeg');
	$im->setImageOpacity(1.0);
	//$im->resizeImage(147,200,Imagick::FILTER_UNDEFINED,0.5,TRUE);
	//$im->setCompressionQuality(90);
	$im->setImageFormat('jpeg'); 
	$attrs['jpegphoto']=$im->getImageBlob();

} else {
	echo "ERROR!";
}
$ret1=ldap_mod_add($ds,$USERDN,$attrs);	
	
if (!$ret1) {
	echo "Error at ldap_mod_add: $ret1\n";
}*/

?>