<?php

require_once('../../connect.php');
session_start();

$address = explode('?', $_POST['page_request']);
$login = $_POST['login'];

$query = "SELECT `id`, `pass`, `number`, `fio` FROM `parrents` WHERE `email` = '$login'";
$sql = mysqli_query($db, $query);
// echo "<br>Вывод дата<br>";
if (mysqli_num_rows($sql) == '0') {
  $link = $address[0]."?register=on";
}
else {
  $res = mysqli_fetch_assoc($sql);
  if (password_verify($_POST['password'], $res['pass']) == TRUE) {
    $_SESSION['auth'] = 'on';
    $_SESSION['parrent_id'] = $res['id'];
    $_SESSION['login'] = $_POST['login'];
    $_SESSION['number'] = $res['number'];
    $_SESSION['fio'] = $res['fio'];
    $link = $address[0];
  }
  else {
    $link = $address[0]."?pass=error";
  }
}
mysqli_close($db);
// var_dump($address);
// print_arr($_SESSION);
header("Location: $link");
?>
