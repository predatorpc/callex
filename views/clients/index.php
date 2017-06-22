<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\editable\Editable;
use app\models\Comments;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Клиенты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить клиента', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    $layoutGrid= '
        <div>{toolbar}</div>
        {summary} 
        {items}
        {pager}
        <div class="clearfix"></div>
        ';

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => $layoutGrid,
        'emptyText' => 'Нет записей',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'first_name',
            [
                'attribute' => 'first_name',
                'header' => 'Ф.И.О.',
                'value' => function($model){
                    return $model->first_name.' '.$model->second_name.' '.$model->last_name;
                }
            ],

            [
                    'attribute' => 'birthday',
                    'value' => function($model){
                        return !empty($model->birthday) ? date('d.m.Y', strtotime($model->birthday)) : '';
                    }
            ],
            [
                'class'=>'kartik\grid\EditableColumn',
                //'attribute' => 'lifetime',
                'header' => 'Комментрарии',
                'value' => function($model) use ($modelComment){
                    if(Yii::$app->user->can('Manager')) {
                        $text = 'Комментарии';
                    }
                    else{
                        $text = 'Комментарии не доступны';
                    }
                    return $text;
                },
                'editableOptions'=> function ($model, $key, $index) use ($modelComment) {
                    return [
                        'name' => 'calls',
                        'asPopover' => true,
                        'value' => 'Комментарии',
                        'header' => 'Name',
                        'size' => 'md',
                        'options' => ['class' => 'form-control', 'placeholder' => 'Enter person name...'],

                        'header' => 'Баланс',
                        'inputType' => Editable::INPUT_HIDDEN,
                        'beforeInput' => function ($form, $widget) use ($modelComment, $model) {
                            $actions = ArrayHelper::map(\app\models\CommentsActions::find()->where(['status'=>1])->all(),'id','name');
                            $types = ArrayHelper::map(\app\models\CommentsTypes::find()->where(['status'=>1])->all(),'id','name');
                            echo '<label>Добавление комментария</label><br>';
                            echo '<ul>';
                            foreach (Comments::find()->where(['client_id' => $model->id])->orderBy('date DESC')->limit(5)->All() as $comment) {
                                echo '<li><b>' . $comment->client->second_name .
                                    '(' . date('d.m.Y H:i:s', strtotime($comment->date)) .
                                    ')</b>: '.(isset($actions[$comment->action_id])?$actions[$comment->action_id]:'')
                                    .' - '. $comment->text . '</li>';
                            }
                            echo '</ul>';

                            echo $form->field($modelComment, 'client_id')->hiddenInput(['value' => $model->id])->label(false);
                            echo $form->field($modelComment, 'action_id')->dropDownList($actions,[
                                'prompt' => 'Выберите действие...'
                            ]);
                            echo $form->field($modelComment, 'type_id')->dropDownList($types,[
                                'prompt' => 'Выберите тип...'
                            ]);
                            echo $form->field($modelComment, 'text');
                        },

                    ];
                },
                'format'=>'raw',
            ],
            [
                    'attribute' => 'gender',
                    'value' => function($model){
                        $text = '';
                        if($model->gender == 1){
                            $text = 'Ж';
                        }elseif($model->gender == 2){
                            $text = 'М';
                        }
                        return $text;
                    },
                    'filter' => ['1'=>'Ж','2'=>'М'],
            ],
             'phone',
             'district',
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'car',
                'vAlign'=>'middle'
            ],
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'children',
                'vAlign'=>'middle'
            ],

            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'status',
                'vAlign'=>'middle'
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
