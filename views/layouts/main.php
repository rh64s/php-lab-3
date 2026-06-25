<?php
$auth = \Src\Auth\Auth::check();
$user = $auth ? \Src\Auth\Auth::user() : null;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css.css">
    <title>Main site</title>
</head>
<body>
<div>
    <nav>
        <div class="logo">FAQ</div>
        <?= $auth ? '<p>Добрый день, ' . $user->login . '</p>' : '' ?>
        <div class="navs navs-links">
            <?= $auth && (int) $user->role_id === 1 ? ('<a href="' . app()->route->getUrl('/admin/dashboard') . '">Админка</a>') : ''  ?>
            <a href="<?= app()->route->getUrl('/') ?>">Главная</a>
        </div>
        <div class="navs">
            <?= \Src\Auth\Auth::check() ?
                    '<a class="btn btn-light" href="' . app()->route->getUrl('/logout') . '">Выход</a>'
                    :
                    '<a class="btn btn-light" href="' . app()->route->getUrl('/login') . '">Войти</a>' .
                    '<a class="btn btn-light" href="' . app()->route->getUrl('/register') . '">Регистрация</a>'
            ?>
        </div>
    </nav>
    <main>
        <div class="message">
            <?= $message ?? '' ?>
        </div>
        <div class="content">
            <?= $content ?? ''; ?>
        </div>

    </main>
</div>

<footer>
    <p>Сделано с душой (через боль)</p>
</footer>

</body>
</html>