<?php

$page = $_GET;

if (empty($page['uri'])) {
  $page['page_title'] = 'ПМПК ГЦППМСП';
  $page['page_uri'] = 'pages/index.php';
}
elseif ($page['uri'] == 'zapis_consult') {
  $page['page_title'] = 'Запись на консультацию';
  $page['page_uri'] = 'pages/zapis_consult.php';
}
elseif ($page['uri'] == 'zapis_pmpk') {
  $page['page_title'] = 'Запись на ПМПК';
  $page['page_uri'] = 'pages/zapis_pmpk.php';
}
elseif ($page['uri'] == 'zapis_pmpk_gia') {
  $page['page_title'] = 'Запись на ПМПК ГИА';
  $page['page_uri'] = 'pages/zapis_pmpk_gia.php';
}
elseif ($page['uri'] == 'parrents_acc') {
  $page['page_title'] = 'Профиль родителя';
  $page['page_uri'] = 'pages/parrents_acc.php';
}
// elseif ($page['uri'] == 'do_zapis_pmpk') {
//   $page['page_title'] = 'Запись на ПМПК ГИА';
//   $page['page_uri'] = 'pages/do/do_zapis_pmpk.php';
// }
else {
  echo('Такой страницы не существует. <br><a href="/"><b>Перейти на главную страницу сайта<b></a>');
}

?>
