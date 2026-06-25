<?php if (isset($data['warnings'])):?>
    <div class="warnings">

    <?php foreach ($data['warnings'] as $warning): ?>
        <div class="warning">
            <p><?=$warning?></p>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>
