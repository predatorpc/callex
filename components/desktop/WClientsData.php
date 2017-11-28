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
            $template = '';

            $form = ActiveForm::begin();
            $template .= $form->field($this->client, 'id')->hiddenInput();
            $template .= Html::hiddenInput('comment_send', '0',[]);
            //$template .= $form->field($this->client, 'comment_send')->hiddenInput();
            $template .= $form->field($this->client, 'first_name')->textInput(['maxlength' => true]);
            $template .= $form->field($this->client, 'second_name')->textInput(['maxlength' => true]);
            $template .= $form->field($this->client, 'last_name')->textInput(['maxlength' => true]);

            $template .= $form->field($this->client, 'birthday')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Выберите ...'],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'todayHighlight' => true,
                    'format' => 'yyyy-mm-dd',
                ]
            ]);

            $template .= $form->field($this->client, 'gender')->dropDownList(['1' => 'Ж', '2' => 'М'], ['prompt' => 'Выберите...']);
            $template .= $form->field($this->client, 'phone')->textInput(['maxlength' => true]);
            $template .= $form->field($this->client, 'email')->textInput();
            //$form->field($this->client, 'district')->textInput(['maxlength' => true]);
            $template .= $form->field($this->client, 'car')->checkbox();
            $template .= $form->field($this->client, 'children')->checkbox();
            $template .= $form->field($this->client, 'call_status_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\CallStatuses::find()->where(['status'=>1])->All(),'id','name'));

            $template .= '<div id="next_call"style="display: none;">';
            $template .= DateTimePicker::widget([
                'name' => 'Clients[next_call]',
                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                'value' => '',
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'dd.mm.yyyy HH:ii',
                ],
            ]);
            $template .= '</div>';



            if(isset($this->client->phone) && !empty($this->client->phone)){
                $phone = $this->client->phone;
                $user = UserFitness::find()->where(['LIKE','phone',$phone])->One();
                if($user){
                    $template .= '<a target="_blank" style="font-size: 25px;font-weight: bold;" href="http://web.extremefitness.ru/users/view?id='.$user->id.'">Профиль в фитнессе</a>';
                }
            }

            $template .= $form->field($this->client, 'last_call')->hiddenInput(['value'=> date('Y-m-d H:i:s')])->label(false);

            $template .= '<div class="form-group">';
            $template .= Html::submitButton('Сохранить', ['class' => 'btn btn-success client', 'disabled'=>'disabled']);
            $template .= '</div>';

            ActiveForm::end();
        }
        else{
            $template = Html::tag('span', 'Клиент не найден', ['class'=>'']);
        }
        return $template;
    }

}