<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CallStatuses */

$this->title = 'Create Call Statuses';
$this->params['breadcrumbs'][] = ['label' => 'Рабочий стол', 'url' => ['/desktop/index']];
$this->params['breadcrumbs'][] = ['label' => 'Статусы звонков', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="call-statuses-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
