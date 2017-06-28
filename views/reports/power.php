<?php
use yii\bootstrap\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\CommentsActions;
use app\models\CommentsTypes;
use app\models\CallStatuses;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
$this->title = 'Отчет по производительности';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="calendar-fast">
        <a class="dashed" href="<?=Url::to(['reports/power', 'dateStart' => Date("Y-m-d"), 'dateEnd' => Date("Y-m-d")]);?>">Сегодня</a>|
        <a class="dashed" href="<?=Url::to(['reports/power', 'dateStart' => Date('Y-m-d', strtotime('-1 day')), 'dateEnd' => Date('Y-m-d', strtotime('-1 day'))]);?>">Вчера</a>|
        <a class="dashed" href="<?=Url::to(['reports/power', 'dateStart' => Date('Y-m-d', strtotime('-2 day')), 'dateEnd' => Date('Y-m-d', strtotime('-2 day'))]);?>">Позавчера</a>|
        <a class="dashed" href="<?=Url::to(['reports/power', 'dateStart' => Date('Y-m-d', strtotime('-1 week')), 'dateEnd' => Date('Y-m-d')]);?>">Прош. неделя</a>|
        <a class="dashed" href="<?=Url::to(['reports/power', 'dateStart' => Date('Y-m-d', strtotime('-1 month')), 'dateEnd' => Date("Y-m-d")]);?>">Прош. месяц</a>
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
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'user_id',
                'header' => 'Оператор',
                'value' => function($model){
                    $user = \app\models\User::find()->where(['id'=>$model->user_id])->One();
                    if($user){
                        return $user->second_name;
                    }else{
                        return '';
                    }
                },
                'filter' => false,
            ],
            [
                'attribute' => 'count',
                'header' => 'Количсетво звонков'
            ],
        ],
    ]); ?>
</div>
