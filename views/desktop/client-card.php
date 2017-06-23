<?php
use yii\bootstrap\Html;

$this->title = 'Карточка клиента';
$this->params['breadcrumbs'][] = ['label' => 'Рабочий стол', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="clients-update">

    <h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form_client', [
    'model' => $client,'sms'=>$sms
]) ?>

</div>