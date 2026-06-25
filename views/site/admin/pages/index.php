<h1>Созданные страницы</h1>
<div class="list">
    <a href="<?= app()->route->getUrl('/admin/pages/create');?>" class="btn btn-dark">Создать новую страницу</a>

    <table>
        <thead>
            <tr>
                <th>Название (страницы как файла)</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pages)): ?>
            <tr>
                <td colspan="2">Страницы еще не созданы</td>
            </tr>
            <?php else: ?>
                <?php foreach ($pages as $page): ?>
                <tr>
                    <td><?= $page['slug'] ?></td>
                    <td>
                        <a href="<?= app()->route->getUrl('/admin/pages/' . $page['slug']  . '/edit');?>" class="btn btn-light">Редактировать</a>
                        <form method="POST" action="<?= app()->route->getUrl('/admin/pages/' . $page['slug']  . '/delete');?>" style="display:inline;">
                            <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
                            <button type="submit" class="btn btn-light" onclick="return confirm('Точно удалить??')">Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="<?= app()->route->getUrl('/admin/dashboard');?>" class="btn btn-light">Назад на общее обозрение</a>
</div>