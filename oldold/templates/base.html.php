<!DOCTYPE html>
<html lang="ru">
<?php include('templates/head.php') ?>
<body>
<div class="all">
<?php include_once("templates/menu1.html.twig") ?>
	<div class="big_content">
        <?php include_once("templates/left_border.php") ?>
		<!-- Блок содержимого центрального блока -->
		<div class="content">

        
            <?php include_once($page['page_uri']) ?>
		
        
        </div>
		<!-- Конец блока содержимого центрального блока -->
        <?php include_once("templates/right_border.php") ?>
	</div>
</div>
</body>
</html>