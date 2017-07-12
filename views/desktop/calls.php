<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\CallStatuses;
$this->title = 'Мои звонки';
?>

<div class="calls-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="calendar-fast">
        <a class="dashed" href="<?=Url::to(['desktop/calls', 'dateStart' => Date("Y-m-d"), 'dateEnd' => Date("Y-m-d")]);?>">Сегодня</a>|
        <a class="dashed" href="<?=Url::to(['desktop/calls', 'dateStart' => Date('Y-m-d', strtotime('-1 day')), 'dateEnd' => Date('Y-m-d', strtotime('-1 day'))]);?>">Вчера</a>|
        <a class="dashed" href="<?=Url::to(['desktop/calls', 'dateStart' => Date('Y-m-d', strtotime('-2 day')), 'dateEnd' => Date('Y-m-d', strtotime('-2 day'))]);?>">Позавчера</a>|
        <a class="dashed" href="<?=Url::to(['desktop/calls', 'dateStart' => Date('Y-m-d', strtotime('-1 week')), 'dateEnd' => Date('Y-m-d')]);?>">Прош. неделя</a>|
        <a class="dashed" href="<?=Url::to(['desktop/calls', 'dateStart' => Date('Y-m-d', strtotime('-1 month')), 'dateEnd' => Date("Y-m-d")]);?>">Прош. месяц</a>
    </div>

    <?php
    $layoutGrid= '
        <div> {toolbar}</div>
        {summary}
        {items}
        <div class="clearfix"></div>
        {pager}
        ';
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => $layoutGrid,
        'responsive'=>false,
        'responsiveWrap'=>false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'client_name',
                'header' => 'Клиент',
                'value' => function($model){
                    if(isset($model->client) && isset($model->client->last_name)){
                        return $model->client->last_name.' '.$model->client->first_name.' '.$model->client->second_name;;
                    }
                }
            ],
            [
                'attribute' => 'call_status_id',
                'header' => 'Статус',
                'value' => function($model){
                    if(isset($model->client) && isset($model->client->call_status_id)){
                        $callStatuses = ArrayHelper::map(CallStatuses::find()->All(),'id','name');
                        return $callStatuses[$model->client->call_status_id];
                    }
                },
                'filter' => ArrayHelper::map(CallStatuses::find()->All(),'id','name'),
            ],
            [
                    'attribute' => 'date',
                    'header' => 'Дата',
                    'value' => function($model){
                        if(isset($model->date)){
                            return date('d.m.Y H:i:s',strtotime($model->date));
                        }
                    },
                    'filter' => false,
            ],

        ],
    ]); ?>
</div>
