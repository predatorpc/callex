<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CommentsActions */

$this->title = 'Create Comments Actions';
$this->params['breadcrumbs'][] = ['label' => 'Comments Actions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-actions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
