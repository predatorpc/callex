<?php
use yii\bootstrap\Html;
use kartik\grid\GridView;

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
            'client_id',
            [
                'attribute'=>'client_id',
                'label' => 'Клиент',
                'value' => function($model){
                    return $model->client->last_name.' '.$model->client->last_name.' '.$model->client->second_name;
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

                }
            ],
            [
                'attribute'=>'action_id',
                'value' => function($model) {
                    if (isset($model->action)) {
                        return $model->action->name;
                    } else {
                        return '';
                    }
                }
            ],

            'text:ntext',
            // 'created_by_user',
            // 'date',
            // 'call_status_id',
            // 'status',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
