<?php

//////////////////////////////////////////////////////////////////////////////////////
//
// Форма добавления новой карточки
// 22/12/2016
// mmerzlyakov AKA predator_pc
// special for ExtremeFitness.ru
//
/////////////////////////////////////////////////////////////////////////////////////


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use \kartik\file\FileInput;
use \kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */


//Справочник пола
$gender = [
    ['id' => 0, 'name'=> ' -- Выбрать пол'],
    ['id' => 1, 'name'=> 'Мужчина'],
    ['id' => 2, 'name'=> 'Женщина'],
];



////////////////////////////////////////////////////////////////////////////////////////////////////
// Начало формы
////////////////////////////////////////////////////////////////////////////////////////////////////

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php // = $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'second_name')->textInput(['minlength' => 1,'maxlength' => true]) ?>
    <?= $form->field($model, 'first_name')->textInput(['minlength' => 1, 'maxlength' => true]) ?>
    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '+79999999999',
    ]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?php //=$form->field($model, 'company_id')->DropDownList(ArrayHelper::map(array_merge([['id' =>0,'name' => ' -- Выбрать компанию']],$companies),'id','name'));  ?>
    <?php //=$form->field($model, 'club_id')->DropDownList(ArrayHelper::map(array_merge([['id' => 0,'name' => ' -- Выбрать клуб']],$clubs),'id','name'));  ?>
    <?=$form->field($model, 'gender')->DropDownList(ArrayHelper::map($gender,'id','name'));  ?>

    <?php //= $form->field($model, 'birthday')->textInput() ?>
    <?php /*//= $form->field($model, 'birthday')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATE,
        'ajaxConversion'=>false,
        'widgetOptions' => [
            'pluginOptions' => [
                'autoclose' => true
            ]
        ]
    ]); */ ?>
    <?php
    if(Yii::$app->user->can('Manager')){

        echo $form->field($model, 'staff')->checkbox();
        //echo $form->field($model, 'agree')->checkbox();
    }

    ?>
    <?php //= $form->field($model, 'bonus')->textInput() ?>
    <?php //= $form->field($model, 'money')->textInput() ?>

    <?php //= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    <?php //= $form->field($model, 'staff')->checkbox() ?>
    <?php //= $form->field($model, 'agree')->checkbox(['disabled' => true]) ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить',
                        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end();

    if(!$model->isNewRecord){
        $images = \app\models\UsersPhotos::find()->where('user_id = '.$model->id)->andWhere('status = 1')->all();
        if(!empty($images)) {

            foreach ($images as $item){

                echo "<div style='margin: 5px; position: relative; display: inline-block;'>";

                if($item->main==1)
                    echo "<div class='close' style='opacity: 1; font-size: 14pt; color:"
                        ." greenyellow;position: absolute; right: 30px; top: 10px;'>V</div>";
                ?>

                <div class='close' style='opacity: 1; font-size: 26pt; color: red; position: absolute; right: 3px; top: 3px; '
                     onClick='return delImage("/users/delete-image",<?=$item->id?>)'>&times;
                </div>
                <img src='<?=$item->path?>' width=250 id='<?=$item->id ?>'
                     onClick='return setMainImage("/users/set-main-image", <?=$model->id?>, <?=$item->id?>)'>
                </div>

                <?php
            }
        }
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
// Конец формы, загрузка картинки
////////////////////////////////////////////////////////////////////////////////////////////////////


                if(!$model->isNewRecord){
    ?>

        <h3>Добавить изображение</h3>
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
        echo $form->field($model_file, 'imageFile[]')->widget(
            FileInput::classname(), [
                'options' => ['multiple' => true],
                'pluginOptions' => ['previewFileType' => 'any',
                                    'uploadUrl' => Url::to(
                                        ['/users/upload?model_id=' . $model->id]
                                    )],
            ]
        );
        ActiveForm::end();

    }
////////////////////////////////////////////////////////////////////////////////////////////////////
// Конец формы, загрузка картинки
////////////////////////////////////////////////////////////////////////////////////////////////////


?>


</div>
