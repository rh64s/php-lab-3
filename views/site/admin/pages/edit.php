<h1>Редиактировть страницу: <?= $slug ?></h1>
<form method="POST" action="/admin/pages/<?= $slug ?>/edit">
    <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
    
    <div>
        <label for="editable">Content (raw HTML):</label>
        <textarea id="editable" name="editable" rows="20" cols="80" required ><?= $editable ?></textarea>
    </div>
    
    <div class="navs">
        <button type="submit" class="btn btn-dark">Save Changes</button>
        <a href="/admin/pages" class="btn btn-light">Cancel</a>
    </div>
</form>