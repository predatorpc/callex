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
$this->title = 'Отчет по звонкам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="calendar-fast">
        <a class="dashed" href="<?=Url::to(['reports/calls', 'dateStart' => Date("Y-m-d"), 'dateEnd' => Date("Y-m-d")]);?>">Сегодня</a>|
        <a class="dashed" href="<?=Url::to(['reports/calls', 'dateStart' => Date('Y-m-d', strtotime('-1 day')), 'dateEnd' => Date('Y-m-d', strtotime('-1 day'))]);?>">Вчера</a>|
        <a class="dashed" href="<?=Url::to(['reports/calls', 'dateStart' => Date('Y-m-d', strtotime('-2 day')), 'dateEnd' => Date('Y-m-d', strtotime('-2 day'))]);?>">Позавчера</a>|
        <a class="dashed" href="<?=Url::to(['reports/calls', 'dateStart' => Date('Y-m-d', strtotime('-1 week')), 'dateEnd' => Date('Y-m-d')]);?>">Прош. неделя</a>|
        <a class="dashed" href="<?=Url::to(['reports/calls', 'dateStart' => Date('Y-m-d', strtotime('-1 month')), 'dateEnd' => Date("Y-m-d")]);?>">Прош. месяц</a>
    </div>
    <?php $form = ActiveForm::begin(['action' => '/reports/calls', 'id' => 'forum_post', 'method' => 'get',]); ?>
    <?= DatePicker::widget([
        'name' => 'dateStart',
            'value' => isset($_GET['dateStart']) ? $_GET['dateStart'] : '',
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'dd.mm.yyyy'
            ]
        ]);
       ?>
    <?= DatePicker::widget([
        'name' => 'dateEnd',
        'value' => isset($_GET['dateStart']) ? $_GET['dateEnd'] : '',
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'dd.mm.yyyy'
        ]
    ]);
    ?>
        <div class="form-group">
            <?= Html::submitButton('-->', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
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
            ['class' => 'kartik\grid\SerialColumn'],

//            'id',
            [
                'attribute'=>'client_id',
                'label' => 'Клиент',
                'value' => function($model){
                    return $model->client->last_name.' '.$model->client->first_name.' '.$model->client->second_name;
                }
            ],
            [
                    'attribute' => 'phone',
                    'header' => 'Телефон',
                    'value' => function($model){
                        if(isset($model->client) && isset($model->client->phone)){
                            return $model->client->phone;
                        }else{
                            return '';
                        }
                    }
            ],
            [
                'attribute'=>'type_id',
                'value' => function($model){
                    if(isset($model->type)){
                        return $model->type->name;
                    }else{
                        return '';
                    }

                },
                'filter' => ArrayHelper::map(CommentsTypes::find()->all(),'id','name')
            ],
            [
                'attribute'=>'action_id',
                'value' => function($model) {
                    if (isset($model->action)) {
                        return $model->action->name;
                    } else {
                        return '';
                    }
                },
                'filter' => ArrayHelper::map(CommentsActions::find()->all(),'id','name')
            ],

            'text:ntext',
            [
                'attribute'=>'created_by_user',
                'label' => 'Оператор',
                'value' => function($model){
                    return $model->user->first_name.' '.$model->user->last_name.' '.$model->user->second_name;
                }
            ],
            [
                'attribute'=>'call_status_id',
                'header'=> 'Статус звонка',
                'value' => function($model){

                    return $model->client->callStatus->name;
                },
                'filter' => ArrayHelper::map(CallStatuses::find()->all(),'id','name'),
            ],
            [
                'attribute'=>'date',
                'value' => function($model){
                    return date('d.m.Y H:i:s',strtotime($model->date));
                },
                'filter' => false,
            ],

            // 'status',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
