<?php
use app\components\WClietsList;
use yii\helpers\ArrayHelper;
use app\models\CallStatuses;
use yii\bootstrap\BootstrapAsset;
$callStatuses = ArrayHelper::map(\app\models\CommentsActions::find()->All(),'id','name');
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
                <a href="/desktop/find-client" class="btn btn-default ">Найти по номеру</a>

            <?php
            if(Yii::$app->user->can('Manager')){?>

                    <a href="/desktop/import" class="btn btn-danger ">Импорт клиентов</a>

                    <a href="/call-statuses" class="btn btn-warning">Статусы звонков</a>

                    <a href="/comments-actions" class="btn btn-warning">Действия к комментариям</a>

<!--                    <a href="/comments-types" class="btn btn-warning">Типы к комментариям</a>-->
            <?php } ?>

            <button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="left"  style="float: right;" id="statistic" data-content="<?php

                    foreach ($statistic as $item){
                        //print_r($item);
                        echo $callStatuses[$item->action_id].' : ';
                        echo $item->count.' | ';
                    }?>">
                <?=$todayCountCalls;?>
            </button>
    </div>
        <div class="clear"></div>
    </div>
</div>
<?php
$script = <<< JS
    $('#statistic').popover();
JS;
$this->registerJs($script, yii\web\View::POS_READY);

?>