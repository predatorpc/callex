<?php
$this->title = 'Рабочий стол';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <a href="/desktop/client-card" class="btn btn-success center-block">Получить карточку клиента</a>
</div>
<br>
<?php
if(Yii::$app->user->can('Manager')){?>
    <div class="row">
        <a href="/desktop/import" class="btn btn-danger ">Импорт клиентов</a>

        <a href="/call-statuses" class="btn btn-warning">Статусы звонков</a>

        <a href="/comments-actions" class="btn btn-warning">Действия к комментариям</a>

        <a href="/comments-types" class="btn btn-warning">Типы к комментариям</a>
    </div>
<?php } ?>
