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
    <div class="row">
    <div class="col-md-3">

        <input type="hidden" name="client_id" value="<?=$model->id;?>">

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
    </div>
        <div class="col-md-3">

        <?= $form->field($model, 'district')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'car')->checkbox() ?>

    <?= $form->field($model, 'children')->checkbox() ?>

    <?= $form->field($model, 'call_status_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\CallStatuses::find()->where(['status'=>1])->All(),'id','name'),['prompt' => 'Выберите...']) ?>

    <?= $form->field($model, 'client_shop_id')->textInput() ?>

    <?= $form->field($model, 'client_helper_id')->textInput() ?>

    <?= $form->field($model, 'client_fit_id')->textInput() ?>

    <?= $form->field($model, 'last_call')->hiddenInput(['value'=> date('Y-m-d H:i:s')])->label(false) ?>

    <?php //= $form->field($model, 'date_create')->textInput() ?>

    <?php //= $form->field($model, 'date_update')->textInput() ?>

    <?= $form->field($model, 'status')->checkbox() ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'disabled'=>'disabled']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $script = <<< JS
    $('#clients-call_status_id').change(function(){
	    $('.btn.btn-success').prop("disabled", false)
    });
JS;

    //маркер конца строки, обязательно сразу, без пробелов и табуляции
    $this->registerJs($script, yii\web\View::POS_READY);
    ?>


</div>
