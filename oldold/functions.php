<?php

function print_arr($arr){
	echo '<pre>' . print_r($arr, true) . '</pre>';
}

function get_services(){
	global $db;
	$sql = "SELECT * FROM services ORDER BY id";

	if(!$result = mysqli_query($db, $sql))
		return false;

	var_dump($result);
	# Тут будет массив для передачи foreach
	$items = array();
	# Пока есть строки в ресурсе
	while( $data = mysqli_fetch_assoc($result) )
		# Дописываем данные в массив
		$items[] = $data;
	mysqli_free_result($result);
	return $items;
}