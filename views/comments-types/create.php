<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CommentsTypes */

$this->title = 'Create Comments Types';
$this->params['breadcrumbs'][] = ['label' => 'Comments Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-types-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
