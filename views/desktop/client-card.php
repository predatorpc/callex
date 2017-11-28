<?php
use yii\bootstrap\Html;

$this->title = 'Карточка клиента';
$this->params['breadcrumbs'][] = ['label' => 'Рабочий стол', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="clients-update">

    <?= $this->render('_form_client_new', [
            'model' => $client,'sms'=>$sms
        ]);
    ?>

</div>