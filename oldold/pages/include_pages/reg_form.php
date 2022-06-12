<form style="width: 400px;" action="pages/do/registration.php" method="post">
  <fieldset>
    <legend>Регистрация профиля</legend>
    <input type="hidden" name="page_request" id="page_request" class="form-control" value="<?=$_SERVER['REQUEST_URI']?>" readonly>
    <div class="form-group">
      <label for="exampleInputEmail1">Введите ваши Фамилию Имя Отчество (ФИО родителя):</label>
      <input type="text" name="fio" class="form-control" id="fio" aria-describedby="Fio" placeholder="Введите ФИО" required>
      <small id="login" class="form-text text-muted">Ваши данные не будут переданы третьим лицам</small>
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Введите логин (ваш Email):</label>
      <input type="email" name="login" class="form-control" id="login" aria-describedby="Login" placeholder="Введите email" required>
      <small id="login" class="form-text text-muted">Ваши данные не будут переданы третьим лицам</small>
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Введите номер телефона (11 цифр - 89ххххххххх):</label>
      <input type="text" name="number" class="form-control" id="number" aria-describedby="Number" pattern="[0-9]{11}" placeholder="89ххххххххх" required>
      <small id="login" class="form-text text-muted">Ваши данные не будут переданы третьим лицам</small>
    </div>
    <div class="form-group">
      <label for="password">Введите пароль:</label>
      <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
    </div>
    <button type="submit" class="btn btn-success">Зарегистрироваться</button>
  </fieldset>
</form>

