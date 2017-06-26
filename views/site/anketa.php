<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Анкета';
?>

<div class="clients-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="anketa-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'second_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'fitness')->checkbox() ?>

        <?= $form->field($model, 'shop')->checkbox() ?>

        <?= $form->field($model, 'gender')->dropDownList(['1' => 'Жен.', '2' => 'Муж.']) ?>

        <?= $form->field($model, 'commnet')->textarea(['rows' => 6]) ?>

        <?php //= $form->field($model, 'date')->textInput() ?>

        <?php //= $form->field($model, 'status')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
