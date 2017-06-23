<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить пользователя', ['create-staff'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered mobile'
        ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'id',
                'contentOptions' =>['data-label' => 'ID Пользователя'],
                'content' => function($model){
                    if($model->staff==1){
                        return Html::a($model->id,'/users/update-staff?id='.$model->id);
                    }
                    return Html::a($model->id,'/users/update-staff?id='.$model->id);
                },
            ],
            [
                'attribute'=>'status',
                'label' => 'Фото',
                'content' => function($model){
                    $path = \app\models\UsersPhotos::getFirstImage($model->id);
                    if(!empty($path))
                        return "<img width=50 src=\"".$path['path']."\"><br>";
                    else
                        return "<img width=50 src=\"/images/nophoto.png\"><br>";
                },
                'contentOptions' =>['data-label' => 'Фото'],
            ],
//В этом разделе не треба
//            [
//                'attribute'=>'name',
//                'contentOptions' =>['data-label' => 'Имя пользователя'],
//            ],
            [
                'attribute'=>'second_name',
                'contentOptions' =>['data-label' => 'Фамилия'],
                'content' => function($model){
                    if($model->staff==1){
                        return Html::a($model->second_name,'/users/update-staff?id='.$model->id);
                    }
                    return Html::a($model->second_name,'/users/update-staff?id='.$model->id);
                },
            ],
            [
                'attribute'=>'first_name',
                'contentOptions' =>['data-label' => 'Имя'],
            ],
            [
                'attribute'=>'last_name',
                'contentOptions' =>['data-label' => 'Отчество'],
            ],
            [
                'attribute'=>'phone',
                'contentOptions' =>['data-label' => 'Телефон'],
            ],

            // 'email:email',
             [
               'attribute' => 'birthday',
               'contentOptions' =>['data-label' => 'ДР'],
               'content' => function($model)
               {
                   return date("d/m/Y", strtotime($model->birthday));
               }
             ],
            // 'bonus',
            // 'money',
            // 'created_at',
            // 'updated_at',
            // 'password_reset_token',
            // 'password_hash',
            // 'password',
            // 'auth_key',

           [
               'attribute'=>'status',
               'contentOptions' =>['data-label' => 'Статус'],
               'label' => 'Статус',
               'content' => function($model){
                   return $model->status ? "<span class='text-success'>Активный</span>" : "<span class='text-danger'>Не активный</span>";
               },
               'filter'=>array("1"=>"Активый","0"=>"Не активный"),
           ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
