<?php

require_once('../../connect.php');
$login = $_POST['login'];
//echo $_POST['page_request'].'<br>';
$address = explode('?', $_POST['page_request']);
//echo $address[0].'<br>';
// $query = "SELECT `id` FROM `parrents` WHERE ( `email` = $login )";
$query = "SELECT * FROM parrents WHERE `email`= '$login'";
$sql = mysqli_query($db, $query);
// echo "<br> нум роу <br><pre>";
// var_dump($sql->num_rows);
// echo "</pre><br>";

if ($sql->num_rows === 0) {    // если запрос по ЕМАЙЛУ вернул 0 строк регистрируем нового пользователя 
	$fio = $_POST['fio'];
	$number = $_POST['number'];
	$pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$query_reg = "INSERT INTO parrents (`fio`, `email`, `number`, `pass`) VALUES ('$fio', '$login', '$number', '$pass')";
	$res = mysqli_query($db, $query_reg);
	
	// echo "<br> результат <br>";
	// var_dump($query_reg);
	// die;
	
	// создаем линк для блока ELSE
	if ($res == !false) $link = $address[0]."?doauth=aftersave"; // если сохранение прошло успешно - направляем на ZAPIS_PMPK для дальнейшей авторизации
	else { // если сохранение вернуло ошибку - значит отправляем авторизовываться с ообщением что такой логин уже есть
		$err = mysqli_error($db);
		//echo "$err";
		$link = $address[0].'?doauth=on';
	}
}
else { // ИНАЧЕ если запрос по ЕМАЙЛУ вернул НЕ 0 строк - включае doauth и и делаем линк на авторизацию
	$link = $address[0]."?doauth=on";
}

// mysqli_close($link);
$_SESSION['login'] = $_POST['login']; // устанавливаем в сессию указанный логин

mysqli_close($db);
header("Location: $link"); // отправляем на итоговый ЛИНК


?>
