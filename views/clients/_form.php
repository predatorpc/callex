<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Clients */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clients-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'second_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?php //= $form->field($model, 'birthday')->textInput() ?>
    <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'Выберите ...'],
    'pluginOptions' => [
        'autoclose'=>true,
        'todayHighlight' => true,
        'format' => 'yyyy-mm-dd',
    ]
    ]);?>

    <?= $form->field($model, 'gender')->dropDownList(['1' => 'Ж', '2' => 'М'], ['prompt' => 'Выберите...']) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'district')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'car')->checkbox() ?>

    <?= $form->field($model, 'children')->checkbox() ?>

    <?= $form->field($model, 'call_status_id')->textInput() ?>

    <?= $form->field($model, 'client_shop_id')->textInput() ?>

    <?= $form->field($model, 'client_helper_id')->textInput() ?>

    <?= $form->field($model, 'client_fit_id')->textInput() ?>

    <?php //= $form->field($model, 'date_create')->textInput() ?>

    <?php //= $form->field($model, 'date_update')->textInput() ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
