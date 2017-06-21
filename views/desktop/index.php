<?php
$this->title = 'Рабочий стол';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <a href="/desktop/client-card" class="btn btn-success center-block">Получить карточку клиента</a>
</div>
<br>
<?php
if(Yii::$app->user->can('GodMode')){?>
    <div class="row">
        <a href="/desktop/import" class="btn btn-success center-block">Импорт клиентов</a>
    </div>
<?php } ?>
