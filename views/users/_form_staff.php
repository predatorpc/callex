<?php


//////////////////////////////////////////////////////////////////////////////////////
//
// Форма добавления новой карточки сотрудника
// 22/12/2016
// mmerzlyakov AKA predator_pc
// special for ExtremeFitness.ru
//
/////////////////////////////////////////////////////////////////////////////////////


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
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


unset($modelRole['0'], $modelRole['1'], $modelRole['4']);
$rolesar = ArrayHelper::map($modelRole,'name','name');

//var_dump($modelRole);
//var_dump($roles);die();

////////////////////////////////////////////////////////////////////////////////////////////////////
// Начало формы
////////////////////////////////////////////////////////////////////////////////////////////////////

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'id')->textInput(['readonly' => true]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'second_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '+79999999999',
    ]) ?>

    <?= $form->field($model, 'email')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '*{*}@*{*}.*{*}',
    ])  ?>
    <?= $form->field($model, 'typeof')->textInput(['maxlength' => true]) ?>

    <?php //= $form->field($model, 'company_id')->DropDownList(ArrayHelper::map(
    //      array_merge([['id' =>0,'name' => ' -- Выбрать компанию']],$companies),'id','name'));  ?>

    <?php //=$form->field($model, 'club_id')->DropDownList(ArrayHelper::map( array_merge([['id' => 0,'name' => ' -- Выбрать клуб']],$clubs),'id','name')   ?>
    <?=$form->field($model, 'gender')->DropDownList(ArrayHelper::map($gender,'id','name'));  ?>

    <?php //= $form->field($model, 'birthday')->textInput() ?>
    <?php /* = $form->field($model, 'birthday')->widget(DateControl::classname(), [
        'options' => ['placeholder' => 'День рождения'],
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]);
    
    */?>

    <?php //= $form->field($model, 'bonus')->textInput() ?>
    <?php //= $form->field($model, 'money')->textInput() ?>

    <?php

    if(Yii::$app->user->can('Manager')){
        if($model->isNewRecord || (empty($model->password_hash))){
            echo $form->field($model, 'passwordNew')->passwordInput(['maxlength' => true]);
            echo $form->field($model, 'confirmPassword')->passwordInput(['maxlength' => true]);
        }

        echo $form->field($model, 'staff')->checkbox();
        echo $form->field($model, 'agree')->checkbox();
        echo $form->field($model, 'status')->checkbox();

//        echo $form->field($model->role, 'item_name')->dropDownList($rolesar)->label('Права доступа');

	    if(!empty($model->role)){
            echo $form->field($model->role, 'item_name')->DropDownList($rolesar);
        }
        else{
            $roles= \app\models\AuthItem::find()->all();
            unset($roles['0'], $roles['1'], $roles['4']);
            $roleNew = ArrayHelper::map($roles,'name','name');

            echo $form->field(new \app\models\AuthItem(), 'name')->DropDownList($roleNew)->label('Role');
        }
    }


    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить',
                                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


    <?php

    /*if(!$model->isNewRecord){
        $images = \app\models\UsersPhotos::find()->where('user_id = '.$model->id)->all();
        if(!empty($images)) {
            foreach ($images as $item){

                ?>
                <img src="<?= $item->path ?>"><br>

                <?php
            }
        }

    }*/
    if(!$model->isNewRecord){
        $images = \app\models\UsersPhotos::find()->where('user_id = '.$model->id)->andWhere('status = 1')->all();
    if(!empty($images)) {

        foreach ($images as $item){
            echo "<div style='margin: 5px; position: relative; display: inline-block;'>";
            if($item->main==1)
                echo "<div class='close' style='opacity: 1; font-size: 14pt; color:"
                        ." greenyellow;position: absolute; right: 30px; top: 10px;'>V</div>";
        ?>

                    <div class='close' style='opacity: 1; font-size: 26pt; color: red; position: absolute; right: 3px; top: 3px; 'onClick='return delImage("/users/delete-image",<?=$item->id?>)'>&times;</div>
                    <img src='<?=$item->path?>' width=250 id='<?=$item->id ?>'onClick='return setMainImage("/users/set-main-image", <?=$model->id?>, <?=$item->id?>)'>
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
            \kartik\file\FileInput::classname(), [
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
