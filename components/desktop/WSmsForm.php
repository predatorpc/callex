<?php
namespace app\components\desktop;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class WSmsForm  extends Widget
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


        $templateSms = '';
        $templateSms .= '<form id="sms">';
            $templateSms .= Html::hiddenInput('sms[client_id]]',$this->client->id);
            $templateSms .= '<div class="form-group">';
                $templateSms .= Html::label('Текст SMS','',['class'=>'control-label']);
                $templateSms .= Html::input('text','sms[sms]',(isset($sms)) ? $sms : '',['class'=>'form-control']);
            $templateSms .= '</div>';
            $templateSms .= '<div class="form-group">';
                $templateSms .= '<div  class="pull-left" style="margin-right: 10px">'.Html::submitButton('Сохранить смс', ['class' => 'btn btn-warning', "id"=>"savesms"]).'</div>';
                $templateSms .= '<div  class="">'.Html::submitButton('Сохранить и отправить смс', ['class' => 'btn btn-warning', "id"=>"sendsms"]).'</div>';
                $templateSms .= '<div class="clear"></div>';
        $templateSms .= '</div>';
        $templateSms .= '</form>';

        return $templateSms;
    }
}