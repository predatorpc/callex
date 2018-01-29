<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Поиск карточки клиента';

?>

<div class="clients-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <label>Введите номер</label>
        <input class="form-control number" name="phone" maxlength="11" >
    </div>
    <div class="form-group">
        <?= Html::submitButton('Найти', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

