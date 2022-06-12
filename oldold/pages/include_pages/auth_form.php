<form style="width: 400px;" action="pages/do/login.php" method="post">
  <fieldset>
    <legend>Авторизуйтесь</legend>
    <input type="hidden" name="page_request" id="page_request" class="form-control" value="<?=$_SERVER['REQUEST_URI']?>" readonly>
    <div class="form-group">
      <label for="exampleInputEmail1">Введите логин (ваш Email):</label>
      <input type="email" name="login" class="form-control" id="login" aria-describedby="Login" placeholder="Введите email" required>
      <small id="login" class="form-text text-muted">Ваш email не передается никому.</small>
    </div>
    <div class="form-group">
      <label for="password">Введите пароль:</label>
      <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
    </div>
    <button type="submit" class="btn btn-success">Отправить</button>
  </fieldset>
</form>
