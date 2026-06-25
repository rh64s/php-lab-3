<h1>Создать новую страницу</h1>
<form method="POST" action="<?= app()->route->getUrl('/admin/pages/create');?>">
    <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
    
    <div>
        <label for="slug">Название (т.е. файл, а также адреса к ней):</label>
        <input type="text" id="slug" name="slug" required>
    </div>
    
    <div>
        <label for="content">Контент (сырой HTML, что попадет как содержимое тега "main>div"):</label>
        <textarea id="content" name="content" rows="20" cols="80" required></textarea>
    </div>
    
    <div class="navs">
        <button type="submit" class="btn btn-dark">Создать</button>
        <a href="<?= app()->route->getUrl('/admin/pages')?>" class="btn btn-light">Отмена</a>
    </div>
</form>