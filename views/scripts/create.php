<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Scripts */

$this->title = 'Новый скрипт';
$this->params['breadcrumbs'][] = ['label' => 'Скрипты', 'url' => ['/desktop/scripts']];
$this->params['breadcrumbs'][] = ['label' => 'Управление скриптами', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scripts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
