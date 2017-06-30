<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Scripts */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Скрипты', 'url' => ['/desktop/scripts']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scripts-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?=$model->text;?>
    </p>

</div>