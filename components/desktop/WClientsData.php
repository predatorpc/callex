<?php
namespace app\components\desktop;

use app\models\Clients;
use app\models\fitness\UserFitness;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use Yii;

class WClientsData  extends Widget
{
    public $client;


    public function init()
    {
        parent::init();
        if ($this->client === null) {
            $this->client = false;
        }
    }

    public function run()
    {
        if(!empty($this->client)){

            $form = ActiveForm::begin([
                'method'=>'post',
                'options' => ['id' => 'user-form','class'=>'js-form-yii2'],
                'enableAjaxValidation'   => true,
                'enableClientValidation' => true,
                'validateOnBlur'         => false,
                'validateOnType'         => false,
                'validateOnChange'       => false,
                'validateOnSubmit'       => true,
            ]); ?>

                <?=$form->field($this->client, 'id')->hiddenInput()->label(false);?>
                <?= Html::hiddenInput('comment_send', '0',[]);?>

                <?=$form->field($this->client, 'second_name',['options'=>['class'=>'form-group col-md-4 input']])->textInput(['maxlength' => true]);?>

                <?=$form->field($this->client, 'first_name',['options'=>['class'=>'form-group col-md-3 input']])->textInput(['maxlength' => true]);?>

                <?=$form->field($this->client, 'last_name',['options'=>['class'=>'form-group col-md-5 input']])->textInput(['maxlength' => true]);?>


                <?php
                    echo $form->field($this->client, 'birthday',['options'=>['class'=>'form-group col-md-8 input']])->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'Выберите ...'],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'todayHighlight' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]);
                ?>
                <?= $form->field($this->client, 'gender',['options'=>['class'=>'form-group col-md-4 input']])->dropDownList(['1' => 'Ж', '2' => 'М'], ['prompt' => 'Выберите...']);?>
                <?= $form->field($this->client, 'phone',['options'=>['class'=>'form-group col-md-6 input']])->textInput(['maxlength' => true,'disabled'=>'disabled']);?>
                <?= $form->field($this->client, 'email',['options'=>['class'=>'form-group col-md-6 input']])->textInput();?>

                <?php if(isset($this->client->phone) && !empty($this->client->phone)):
                      $phone = $this->client->phone;
                      $user = UserFitness::find()->where(['LIKE','phone',$phone])->One();
                      if($user){
                          echo '<a target="_blank" style="font-size: 25px;font-weight: bold;" href="http://web.extremefitness.ru/users/view?id='.$user->id.'">Профиль в фитнессе</a>';
                      }
                ?>
                <?php endif; ?>

                <?=$form->field($this->client, 'last_call')->hiddenInput(['value'=> date('Y-m-d H:i:s')])->label(false);?>

               <div class="form-group">
                   <?=Html::submitButton('Сохранить', ['class' => 'btn btn-success  col-md-12']); ?>
               </div>
        <?php
            ActiveForm::end();
        }else{
           return '<div class="text">Клиент не найден</div>';
        }

    }

}