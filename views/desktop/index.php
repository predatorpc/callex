<?php
use app\components\WClietsList;
$this->title = 'Рабочий стол';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="index-desktop">
    <div class="row">
        <div class="col-xs-3">
            <?= WClietsList::widget() ?>
        </div>
        <div class="col-xs-9">
                <a href="/desktop/client-card" class="btn btn-success center-block">Получить карточку клиента</a>
               <br>
            <?php

            if(Yii::$app->user->can('Manager')){?>

                    <a href="/desktop/import" class="btn btn-danger ">Импорт клиентов</a>

                    <a href="/call-statuses" class="btn btn-warning">Статусы звонков</a>

                    <a href="/comments-actions" class="btn btn-warning">Действия к комментариям</a>

                    <a href="/comments-types" class="btn btn-warning">Типы к комментариям</a>
            <?php } ?>

    <spans style="font-weight: bold;font-size: 50px; float: right;"><?=$todayCountCalls;?></spans>
    </div>
        <div class="clear"></div>
    </div>
</div>