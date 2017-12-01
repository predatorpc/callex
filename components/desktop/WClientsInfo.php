<?php
namespace app\components\desktop;

use app\models\ClientsInfoGroups;
use yii\base\Widget;
use yii\helpers\Html;

class WClientsInfo  extends Widget
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
        parent::run();
        // выводим информацию по клиенту
        if(!empty($this->client)){
            $template = '';
            //выводим группы информации по клиенту
            $groups = ClientsInfoGroups::find()->where(['status'=>1])->all();
            if(!empty($groups)){
                foreach ($groups as $group){
                    $templateGrop = '<div class="group-info-client">'.Html::tag('h3', $group->name, ['class'=>'']);
                    $infoItems = $group->clientsInfos;
                    if(!empty($infoItems)){
                        foreach ($infoItems as $infoItem){

                            $statusShow = (!empty($this->client->checkRelevanceInfo($infoItem->id))?1:0);

                            $templateGrop .=
                                '<div class="group-info-client-item">'.
                                '<span class="infoItem" client="'.$this->client->id.'" clientInfoLink="'.$infoItem->id.'">'.
                                Html::label($infoItem->name,'',['class'=>($statusShow==1 ? 'text-success' : '')]).
                                //Html::checkbox('check', $statusShow, ['class'=>'itemClient'] ).
                                Html::tag('span', '',['class'=>'glyphicon glyphicon-ok '.(($statusShow==1)?'greenText':'greyText').'', 'id' =>'checkBox'.$this->client->id.'-'.$infoItem->id,'client'=>$this->client->id, 'infoItem'=>$infoItem->id]).
                                '</span> '.
                                Html::tag('span', '',['class'=>'glyphicon glyphicon-info-sign','onclick'=>'window_pay("desktop/client-old-info","'.$infoItem->name.'",{clientOldInfo:true, client:'.$this->client->id.',infoItem:'.$infoItem->id.'})']).

                                '</div>';
                        }
                    }
                    else{
                        $templateGrop='';
                    }

                    if(!empty($templateGrop)){
                        $template .= $templateGrop.'</div>';
                    }
                }
            }

        }
        else{
            $template = '<div class="text">Клиент не найден</div>';
        }


        return $template;
    }


}