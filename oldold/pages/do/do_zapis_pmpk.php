<?php
require_once('../../connect.php');
function print_arr($arr){
	echo '<pre>' . print_r($arr, true) . '</pre>';
}
print_arr($_POST);
print_arr($_SESSION);
	extract($_POST);
	$id_parrent = mysqli_real_escape_string($db, $parrent_id);
	$organnapr = mysqli_real_escape_string($db, $organnapr);
	$prich = mysqli_real_escape_string($db, $prich);
	$fioreb = mysqli_real_escape_string($db, $fioreb);
	$dateroj = mysqli_real_escape_string($db, $dateroj);
	$school = mysqli_real_escape_string($db, $school);
	$class = mysqli_real_escape_string($db, $class);
	$datapredpmpk = mysqli_real_escape_string($db, $datapredpmpk);
	$namepredpmpk = mysqli_real_escape_string($db, $namepredpmpk);
	$snopdrod = true;
	$snopdreb = true;

	$query = "INSERT INTO zapis_pmpk (id_parrent, organnapr, prich, fioreb, dateroj, school, class, datapredpmpk, namepredpmpk, snopdrod, snopdreb) VALUES ('$id_parrent', '$organnapr', '$prich', '$fioreb', '$dateroj', '$school', '$class', '$datapredpmpk', '$namepredpmpk', '$snopdrod', '$snopdreb')";
	mysqli_query($db, $query);
	$inid = mysqli_insert_id($db);
	mysqli_close($db);
//header("Location: zapis_pmpk");
print_arr($_FILES);
?>