<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сотрудники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить сотрудника', ['create-staff'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute'=>'status',
                'label' => 'Фото',
                'content' => function($model){
                    $path = \app\models\UsersPhotos::getFirstImage($model->id);
                    if(!empty($path))
                        return "<img width=50 src=\"".$path['path']."\"><br>";
                    else
                        return "<img width=50 src=\"/images/nophoto.png\"><br>";
                }
            ],
            'name',
            [
                'attribute'=>'second_name',
                'contentOptions' =>['data-label' => 'Фамилия'],
                'content' => function($model){
                    return Html::a($model->second_name,'/users/view-staff?id='.$model->id);
                },
            ],
            'first_name',
            'last_name',
            'phone',
            // 'email:email',
            [
                'attribute' => 'birthday',
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
                'attribute' => 'club_id',
                'content' => function($model)
                {
                    if(!empty($model->club_id)) {
                        $club = \app\models\Clubs::find()->where(
                            'id = ' . $model->club_id
                        )->one();
                        return $club['name'];


                    }
                    return 'Нет привязки';
                }
            ],            [
                'attribute' => 'company_id',
                'content' => function($model)
                {
                    if(!empty($model->company_id)) {
                        $corporative = \app\models\Corporative::find()->where(
                            'id = ' . $model->company_id
                        )->one();
                        return $corporative['name'];


                    }
                    return 'Нет привязки';
                }
            ],
            [
                'attribute'=>'status',
                'label' => 'Статус',
                'content' => function($model){
                    return $model->status ? "<span class='text-success'>Активный</span>" : "<span class='text-danger'>Не активный</span>";
                },
                'filter'=>array("1"=>"Активый","0"=>"Не активный"),
            ],

        //    ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{view} {update}',
                'buttons' => [
                    'view' => function ($url,$model) {
                        return Html::a('<span class="glyphicon glyphicon-share"></span>',
                            'view-staff?id='.$model->id);
                    },
                    'update' => function ($url,$model) {
                        return Html::a('<span class="glyphicon glyphicon-edit"></span>',
                            'update-staff?id='.$model->id);
                    },
                ],

            ],

        ],
    ]); ?>
</div>
