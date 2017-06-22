<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CallStatuses */

$this->title = 'Update Call Statuses: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Рабочий стол', 'url' => ['/desktop/index']];
$this->params['breadcrumbs'][] = ['label' => 'Call Statuses', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="call-statuses-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
