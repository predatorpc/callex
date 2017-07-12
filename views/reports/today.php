<?php
use yii\bootstrap\Html;

use yii\helpers\ArrayHelper;

use app\models\CallStatuses;
$weekDays = array(
    'Воскресенье', 'Понедельник', 'Вторник', 'Среда',
    'Четверг', 'Пятница', 'Суббота'
);
$callStatuses = ArrayHelper::map(CallStatuses::find()->All(),'id','name');
$cartTypes = ArrayHelper::map(\app\models\CardsTypesWebFit::find()->All(),'id','name');
$commentActions = ArrayHelper::map(\app\models\CommentsActions::find()->All(),'id','name');
$this->title = 'Отчет за сегодня '.date('d.m.Y').' '.$weekDays[(date('w'))];
$this->params['breadcrumbs'][] = $this->title;
//print_r($orders);die;

?>
<style>
    table{
        font-size: 20px;
    }
</style>
<div class="comments-index">
    <h1><?= Html::encode($this->title) ?></h1
    <div class="row">
        <div class="col-md-4">

            <h2>Call-центр</h2>
            <table class="table table-striped">
                <thead>
                <tr><th>Действие</th><th>Количество</th></tr>
                </thead>
                <tbody>
                <?php
                $commemtActionTotal = 0;
                foreach ($commetns as $commetn){?>
                    <tr>
                        <td><?=$commentActions[$commetn['action_id']];?></td>
                        <td><?=$commetn['count'];$commemtActionTotal+=$commetn['count'] ?></td>
                    </tr>
                <?php }?>
                <tr class="success">
                    <td><b>Итого:</b></td>
                    <td><b><?=$commemtActionTotal;?></b></td>
                </tr>
                </tbody>
            </table>
            <hr>
            <table class="table table-striped">
                <thead>
                    <tr><th>Статус</th><th>Количество</th></tr>
                </thead>
                <tbody>
                <?php
                $callexTotal = 0;
                foreach ($callex as $item){?>
                <tr>
                    <td><?=$callStatuses[$item->call_status_id];?></td>
                    <td><?=$item->count; $callexTotal += $item->count;?></td>
                </tr>
                <?php }?>
                <tr class="success">
                    <td><b>Итого:</b></td>
                    <td><b><?=$callexTotal;?></b></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h2>ExtremeFitness</h2>
            <table class="table table-striped">
                <thead>
                <tr><th>Тип карты</th><th>Количество</th></tr>
                </thead>
                <tbody>
                <?php
                $cardTotal = 0;
                foreach ($cards as $key =>$card){?>
                    <tr>
                        <td><?=$cartTypes[$card['card_type_id']];?></td>
                        <td><b><?=$card['countNew']; $cardTotal += $card['countNew']?></b></td>
                    </tr>
                <?php }?>
                <tr class="success">
                    <td><b>Итого:</b></td>
                    <td><b><?=$cardTotal;?></b></td>
                </tr>
                </tbody>
            </table>
            <table class="table table-striped">
                <tbody>
                    <tr class="success">
                        <td>Сумма </td>
                        <td><?=number_format($totalMoneyWebFit, '0', '.', ' ');?> руб.</td>
                    </tr>
                    <tr class="success">
                        <td>Количество транзакций</td>
                        <td><?=$countTransWebFit;?></td>
                    </tr>

                </tbody>
            </table>


        </div>
        <div class="col-md-4">
            <h2>ExtremeShop</h2>
            <table class="table table-striped">
                <thead>
                <tr><th></th><th>Сумма</th></tr>
                </thead>
                <tbody>
                <?php
                foreach ($orders as $key =>$item){
                    if($key == 'Количество заказов'){?>
                        <tr class="success">
                            <td><?=$key;?></td>
                            <td><?=$item;?></td>
                        </tr>
                    <?php }else if($key == 'Полная стоимость'){?>
                        <tr class="success">
                            <td><?=$key;?></td>
                            <td><?=number_format($item, '0', '.', ' ');?> руб.</td>
                        </tr>
                    <?php }else{?>
                    <tr>
                        <td><?=$key;?></td>
                        <td><?=$item;?> руб.</td>
                    </tr>
                <?php }}?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php

//начало многосточной строки, можно использовать любые кавычки
$script = <<< JS
setTimeout(function() {
  location.reload();;
},60000);
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);
?>
