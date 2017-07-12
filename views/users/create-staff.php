<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Добавить сотрудника';
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['staff']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_staff', [
        'model' => $model,
        'model_file' => $model_file,
    //    'clubs' => $clubs,
    //    'companies' => $companies,
	'modelRole' => $modelRole,
		
    ]) ?>

</div>
