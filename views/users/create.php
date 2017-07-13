<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Добавить пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Пользватели', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->last_name." ".$model->first_name." ".$model->second_name;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'model_file' => $model_file,
    //    'clubs' => $clubs,
    //    'companies' => $companies,

    ]) ?>

    <?php /*
    echo Html::submitButton(
        $model->isNewRecord ? 'Добавить' : 'Обновить',
        [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
            'data-confirm' => Yii::t('yii', 'Уверены?'),
            'data-method' => 'post',
        ]
    ); */
    ?>

</div>
