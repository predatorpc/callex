<?php

namespace app\components\desktop;
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
            <ul class="nav nav-tabs ">
                <li class="active"><a href="#comments-tab" data-toggle="tab">Список комментариев</a></li>
                <li><a href="#sms-tab" data-toggle="tab">Список Смс-ки</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="comments-tab">
                   <div class="items__com">
                       <?php
                       $comments = Comments::find()->where(['client_id' => $this->client->id])->orderBy('date DESC')->limit(5)->All();
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
                        <div class="item">
                            <b class="title">Media heading</b>
                            <div class="date">20.01.17 г</div>
                            <div class="text"> Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.</div>
                        </div>
                        <div class="item">
                            <b class="title">Media heading</b>
                            <div class="date">20.01.17 г</div>
                            <div class="text"> Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque </div>
                        </div>

                    </div>
                </div>
            </div>
       </div>
    <?php
    }


}