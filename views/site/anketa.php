<?php
use yii\helpers\Html;
use yii\base\Widget;
use yii\widgets\ActiveForm;
$this->title = 'Анкета';
?>

<div class="clients-create">
    <div class="anketa-form">

        <?php
        $form = ActiveForm::begin(
            [
                'options' => ['class' => 'form-normal border anketa-form'],
                'enableAjaxValidation'   => false,
                'enableClientValidation' => true,
                'validateOnBlur'         => false,
                'validateOnType'         => false,
                'validateOnChange'       => false,
                'validateOnSubmit'       => false,
            ]);  ?>
          <div class="title-form">Заполните анкету и мы обязательно свяжемся с вами!</div>
          <div class="text-min">* Обязательные поля</div>

        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'second_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>


        <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions'=>['clearIncomplete'=>false],'mask' => ['+7 999 999 9999']])?>

        <?= $form->field($model, 'fitness')->checkbox() ?>

        <?= $form->field($model, 'shop')->checkbox() ?>

        <?= $form->field($model, 'gender')->dropDownList(['1' => 'Жен.', '2' => 'Муж.'],['prompt' => 'Выберите...']) ?>

        <?= $form->field($model, 'commnet')->textarea(['rows' => 2]) ?>

        <?php //= $form->field($model, 'date')->textInput() ?>

        <?php //= $form->field($model, 'status')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
