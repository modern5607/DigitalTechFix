<?php 
$tns = " (DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 221.144.12.6)(PORT = 1521)) ) (CONNECT_DATA = (SERVICE_NAME = ORACLE) ) ) "; 
try { 
	$conn = new PDO("oci:dbname=".$tns.";charset=utf8", "JSDB", "12345678"); 
	} 
catch (PDOException $e) { 
	echo "Failed to obtain database handle " . $e->getMessage(); 
	} 
ini_set("memory_limit" , -1);
error_reporting(0);	
?>