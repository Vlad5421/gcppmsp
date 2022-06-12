	<!-- Блок содержимого центрального блока -->
	<div class="telo">
		<span class="hed1">Подача документов для записи на ПМПК<br>
			<span style="font-size: 27px; color:blue;">On-Line<br></span>
		</span>
		<span class="hed2">
			<!-- Место для подзаголовка -->
			Все данные и документы передаются в диспетчерскую службу по защищенному каналу.
		</span>
		<span class="hed3">
			<!-- Место для под-под-заголовка -->
		</span>
		<hr>

		<!--   Блок отображения карточек услуг специалистов   -->

<?php if (isset($_SESSION['auth']) and  $_SESSION['auth'] == 'on'): ?> <!-- если прошел авторизацию и она сработала -->
<?php
echo "Вы авторизованы как: ".$_SESSION['fio'];
echo "<br>";
require_once 'include_pages/form_pmpk.php'
?>

<?php elseif (isset($_GET['doauth']) and $_GET['doauth'] == 'on'): ?>
<div class="alert alert-dismissible alert-success">
  <h4 class="alert-heading">Внимание!</h4>
  <p class="mb-0">Профиль с таким Email уже зарегистрирован,<br><a href="zapis_pmpk" class="alert-link">АВТОРИЗУЙТЕСЬ</a>.</p>
</div>
<?php require_once 'include_pages/auth_form.php' ?>


<?php elseif (isset($_GET['reg']) and $_GET['reg'] == 'error'): ?>
<div class="alert alert-dismissible alert-warning">
  <h4 class="alert-heading">Внимание!</h4>
  <p class="mb-0">Произошла ошибка при регистрации<br><a href="zapis_pmpk" class="alert-link">попробуйте снова</a>.</p>
</div>
<?php require_once('include_pages/reg_form.php');?>

<?php elseif (isset($_GET['register']) and $_GET['register'] == 'on'):?>
<div class="alert alert-dismissible alert-warning">
  <h4 class="alert-heading">Внимание!</h4>
  <p class="mb-0">Профиля с таким Email не зарегистрированно, зарегистрируйте профиль или <br><a href="zapis_pmpk" class="alert-link">попробуйте снова</a>.</p>
</div>
<?php require_once('include_pages/reg_form.php');?>

<?php elseif (isset($_GET['doauth']) and $_GET['doauth'] == 'aftersave'):?>
<div class="alert alert-dismissible alert-warning">
  <h4 class="alert-heading">Внимание!</h4>
  <p class="mb-0">Профиль родителя зрегистрирован. <br><a href="zapis_pmpk" class="alert-link">Авторизуйтесь</a>.</p>
</div>
<?php require_once('include_pages/auth_form.php');?>

<?php else: ?>

<?php if (isset($_GET['pass']) and $_GET['pass'] == 'error'): ?>
	<div class="alert alert-dismissible alert-warning">
	  <h4 class="alert-heading">Внимание!</h4>
	  <p class="mb-0">Не верный пароль<br><a href="zapis_pmpk" class="alert-link">попробуйте снова</a>.</p>
	</div>
<?php require_once('include_pages/auth_form.php');?>
<?php endif; ?>

<?php require_once('include_pages/auth_form.php');?>
<br><hr><br>
	<span style="margin-bottom: 10px;"><b>Если вы подаете документы ОНЛАЙН впервые, то нужно зарегистрироваться</b></span><br>
	<?php require_once('include_pages/reg_form.php');?>
<?php endif; ?>
<?php
	// echo "<br>Сесссии<br>";
	// print_arr($_SESSION);
	// echo "<br>GET<br>";
	// print_arr($_GET);
	// echo "<br>сервер<br>";
	// print_arr($_SERVER);
?>

</div>

<!-- Конец блока содержимого центрального блока -->
