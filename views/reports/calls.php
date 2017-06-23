<?php
use yii\bootstrap\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\CommentsActions;
use app\models\CommentsTypes;

$this->title = 'Отчет по звонкам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute'=>'client_id',
                'label' => 'Клиент',
                'value' => function($model){
                    return $model->client->last_name.' '.$model->client->first_name.' '.$model->client->second_name;
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
                'attribute'=>'date',
                'value' => function($model){
                    return date('d.m.Y H:i:s',strtotime($model->date));
                }
            ],
            // 'call_status_id',
            // 'status',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>