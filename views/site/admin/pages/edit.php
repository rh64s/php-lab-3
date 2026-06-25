<h1>Редиактировть страницу: <?= $slug ?></h1>
<form method="POST" action="<?= app()->route->getUrl('/admin/pages/'). $slug . '/edit' ?>">
    <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
    
    <div>
        <label for="editable">Контент (Сырой HTML):</label>
        <textarea id="editable" name="editable" rows="20" cols="80" required ><?= $editable ?></textarea>
    </div>
    
    <div class="navs">
        <button type="submit" class="btn btn-dark">Сохранить изменения</button>
        <a href="<?= app()->route->getUrl('/admin/pages')?>" class="btn btn-light">Назад</a>
    </div>
</form>