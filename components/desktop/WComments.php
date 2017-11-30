<?php
namespace app\components\desktop;


use app\models\Comments;
use app\models\CommentsActions;
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
        $template = '';
        $template .= '<form id="comments" method="post">';


        $template .= Html::hiddenInput('Comment[client_id]',$this->client->id);

        $template .='<div class="form-group">';
        $template .= Html::label('Действие','',['class'=>'control-label']);
        $commentsActions = ArrayHelper::map(CommentsActions::find()->All(),'id','name');
        $template .= Html::dropDownList('Comment[action_id]', 'null', $commentsActions ,['class'=>'form-control','id'=> 'action', ]);
        $template .= '</div>
<div class="form-group">';
        $template .= Html::label('Комментарий','',['class'=>'control-label']);
        $template .= Html::textarea('Comment[text]','',['class'=>'form-control']);
        $template .= '</div>';
        $template .= '<div class="form-group">';
        $template .=Html::submitButton('Сохранить и закрыть', ['class' => 'btn btn-success  col-md-12', "id"=>"sendcomment"]);
        $template .= '</div>';
        $template .= '</form>';

        return $template;
    }
}