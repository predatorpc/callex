<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Редактировать сотрудника: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['staff']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view-staff', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_staff', [
        'model' => $model,
        'model_file' => $model_file,
//        'clubs' => $clubs,
//        'companies' => $companies,
        'modelRole' => $modelRole,

    ]) ?>

</div>
