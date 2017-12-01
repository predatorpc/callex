<?php

namespace app\components\desktop;
use app\models\Sentsms;
use yii\base\Widget;
use yii\helpers\Html;
use app\models\Comments;

class WCallMessage extends Widget
{
    public $client;

    public function run() {

   if(empty($this->client)) return false; ?>
       <div class="list-comments">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs " id="myTabList">
                <li class="active"><a href="#comments-tab" data-toggle="tab">Список комментариев</a></li>
                <li><a href="#sms-tab" data-toggle="tab">Список Смс-ки</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="comments-tab">
                   <div class="items__com">
                       <?php
                       $comments = Comments::find()->where(['client_id' => $this->client->id])->orderBy('date DESC')->All();
                       if(!empty($comments)) {
                           foreach ($comments as $comment){ ?>
                            <div class="item">
                                <b class="title"><?=$comment->user->second_name?></b>
                                <div class="date"><?=date('d.m.Y H:i:s', strtotime($comment->date))?></div>
                                <div class="text"><?=$comment->text?></div>
                            </div>
                            <?php } ?>
                       <?php }else {?>
                           <div class="text" style="padding: 10px 5px;">Нет коментарий</div>
                       <?php } ?>
                   </div>
                </div>
                <div class="tab-pane" id="sms-tab">
                    <div class="items__com">
                        <?php
                        $smss = Sentsms::find()->where(['client_id'=>$this->client->id, 'status'=>1])->all();
                        if(!empty($smss)){
                            foreach ($smss as $sms){?>
                                <div class="item">
                                    <div class="date"><?= Date('d.m.Y H:i', strtotime($sms->date))?></div>
                                    <div class="text"><?= (!empty($sms->text)?$sms->text:'')?></div>
                                </div>
                            <?php
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
       </div>
    <?php
    }


}