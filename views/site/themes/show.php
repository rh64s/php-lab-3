<h2>Тема: <?= $theme->name ?></h2>
<form>
    <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
    <label for="question">Напишите сюда свой вопрос (убедительная просьба, найти ответ на свой вопрос, а только потом задавать, если его нету)</label>
    <textarea id="question" name="text"></textarea>
    <input type="submit" value="Отправить">
</form>
<h3>Список вопросов:</h3>
<div class="list">
    <?php foreach ($questions as $question): ?>
    <div class="item question">

        <p class="user"><?= Models\User::find($question->user_id)->login?></p>
        <p><?= $question->text ?></p>
        <a class="btn btn-dark">Перейти</a>
    </div>
    <?php endforeach; ?>
</div>
