<?php
namespace app\components\desktop;


use app\models\Comments;
use app\models\CommentsActions;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class WComments  extends Widget
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
        parent::run(); // TODO: Change the autogenerated stub
        if(!empty($this->client)){
            // Формируем массив выбор время;
            $hours = array();
            $minutes = array();
            for ($i=1; $i<24; $i++)   $hours[(($i < 10) ? '0' : '').$i] = (($i < 10) ? '0' : '').$i;
            for ($i=1; $i<60; $i++)   $minutes[(($i < 10) ? '0' : '').$i] = (($i < 10) ? '0' : '').$i;
            for ($i=1; $i<30; $i++)   $days[(($i < 10) ? '0' : '').$i] = (($i < 10) ? '0' : '').$i;

            $template = '';
            $template .= '<form id="comments">';


            $template .= Html::hiddenInput('Comments[client_id]',$this->client->id);

            $template .='<div class="form-group">';
            $template .= Html::label('Действие','',['class'=>'control-label']);
            $template .= Html::dropDownList('Comments[action_id]', 'null', ArrayHelper::map(CommentsActions::find()->All(),'id','name'),['prompt' => 'Выберитe..','class'=>'form-control js-select-action','id'=> 'action']);
            $template .= '</div>';
            // Выбор время;

            $template .= '<div class="times-content"><br>';
            $template .= DateTimePicker::widget([
                'name'=>'Comments[date_recall]',
                'options' => ['placeholder' => 'Выберите ...'],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'todayHighlight' => true,
                    'format' => 'yyyy-mm-dd H:i',
                ]
            ]);
//            $template .= '<div class="times-select">';
//            $template .= '<label>День</label>';
//            $template .= Html::dropDownList('Comments[days]', 'null',$days,['prompt' => '-','class'=>'form-control times-select','id'=> 'hours']);
//            $template .= '</div>';
//            $template .= '<div class="times-select">';
//            $template .= '<label>Час</label>';
//            $template .= Html::dropDownList('Comments[hours]', 'null',$hours,['prompt' => '-','class'=>'form-control times-select','id'=> 'hours']);
//            $template .= '</div>';
//            $template .= '<div class="times-select">';
//            $template .= '<label>Минута</label>';
//            $template .= Html::dropDownList('Comments[minute]', 'null', $minutes,['prompt' => '-','class'=>'form-control times-select','id'=> 'minutes']);
//            $template .= '</div>';
            $template .= '<div class="clear"></div>';
            $template .= '</div>';

        $template .='<div class="tag-content">';
        if(!empty(\Yii::$app->params['tag'])) {
            foreach (\Yii::$app->params['tag'] as $tag) {
                $template.= '<div style="margin: 0 10px 5px 0;display: inline-block;  font-size: 12px;"><a href="#!" class="js-tag dotted" >'.$tag.'</a></div>';
            }
        }else{
            $template .= '<b class="text-danger">Добавьте текст в парамс \'tag\'=>[\'Hello Word\']</b>';
        }
        $template .='</div>';
        $template .=  '<div class="form-group">';


            $template .= Html::label('Комментарий','',['class'=>'control-label']);
            $template .= Html::textarea('Comments[text]','',['class'=>'form-control js-text-add']);
            $template .= '</div>';
            $template .= '<div class="form-group">';
            $template .=Html::submitButton('Сохранить и закрыть', ['class' => 'btn btn-success  col-md-12', "id"=>"sendcomment"]);
            $template .= '</div>';
            $template .= '</form>';
        }
        else{
            $template = '<div class="text">Клиент не найден</div>';
        }


        echo $template;
    }
}