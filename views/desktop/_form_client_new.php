<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\desktop\WClientsData;
use app\components\desktop\WClientsInfo;
use app\components\desktop\WComments;
use app\components\desktop\WSmsForm;
use app\components\desktop\WCallMessage;
use app\components\desktop\WFitnessInfo;

use app\models\fitness\FitnessInfo;
?>
<div class="clients-form" id="client-card">

       <div class="js-grid">
            <!--Форма юзера-->
            <div class="col-md-4 item-grid">
                <div class="panel panel-primary">
                    <div class="panel-heading">Вмджет форма клиент</div>
                    <div class="panel-body">
                        <?php
                            echo WClientsData::widget(['client'=>$model]);
                        ?>
                        <div class="clear"></div>
                    </div>
                </div>
            </div> <!--./Форма юзера-->

            <!--Напрвление-->
            <div class="col-md-4 item-grid">
                <div class="panel panel-primary">
                    <div class="panel-heading">Panel heading without title</div>
                    <div class="panel-body">
                        <?=WClientsInfo::widget(['client'=>$model]);?>
                        <div class="clear"></div>
                    </div>
                </div>
            </div><!--./Напрвление-->

            <!--Коментарий-->
            <div class="col-md-4 item-grid">
                <div class="panel panel-primary">
                    <div class="panel-heading">Panel heading without title</div>
                    <div class="panel-body">
                        <?=WComments::widget(['client'=>$model]);?>
                        <div class="clear"></div>

                    </div>
                </div>
            </div><!--./Коментарий-->

            <!--Sms-->
            <div class="col-md-4 item-grid">
                <div class="panel panel-primary">
                    <div class="panel-heading">Panel heading without title</div>
                    <div class="panel-body">
                        <?=WSmsForm::widget(['client'=>$model]);?>
                        <div class="clear"></div>
                    </div>
                </div>
            </div><!--./sms-->

            <!--Статистика-->
            <div class="col-md-4 item-grid">
                <div class="panel panel-primary">
                    <div class="panel-heading">Panel heading without title</div>
                    <div class="panel-body">
                        <?=WCallMessage::widget(['client'=>$model]);?>
                        <div class="clear"></div>
                    </div>
                </div>
            </div><!--./Статистика-->
       </div>
    <div class="row">
        <!--Инфо о клиенте-->
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Panel heading without title</div>
                <div class="panel-body">
                    <?=WFitnessInfo::widget(['client'=>$model]);?>
                    <div class="clear"></div>
                </div>
            </div>
        </div><!--Инфо о клиенте-->

    </div>
</div>
<div class="clearfix"></div>
