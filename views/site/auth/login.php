<h2>Логин</h2>
<?php include_once __DIR__ . '/../../layouts/warnings.php'; ?>

<form method="post">
    <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
    <div>
        <label for="login">Логин</label>
        <input id="login" type="text" name="login">
    </div>

    <div>
        <label for="password">Пароль</label>
        <input id="password" type="password" name="password">
    </div>

    <input class="btn btn-dark" type="submit" value="Войти">
</form>