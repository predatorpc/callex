<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Scripts */

$this->title = 'Редактирование скрипта: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Скрипты', 'url' => ['/desktop/scripts']];
$this->params['breadcrumbs'][] = ['label' => 'Управление скриптами', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scripts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
