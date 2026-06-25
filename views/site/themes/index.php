<h2>Доступные темы:</h2>
<div class="list">
    <?php if (isset($themes)):?>
    <?php foreach ($themes as $theme): ?>
        <div class="item">
            <a class="link-box" href="<?= app()->route->getUrl('/themes/' . $theme->id) ?>">
                <p><?= $theme->name ?></p>
            </a>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>