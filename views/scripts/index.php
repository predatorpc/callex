<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScriptsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Управление скриптами';
$this->params['breadcrumbs'][] = ['label' => 'Скрипты', 'url' => ['/desktop/scripts']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scripts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить скрипт', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            //'text:ntext',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
