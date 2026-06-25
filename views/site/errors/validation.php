<?php if (isset($validation)): ?>
<div class="alert alert-danger">
    <ul>
        <?php foreach ($validation as $field => $errors): ?>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>