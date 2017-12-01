<?php


namespace app\components\desktop;


use app\models\fitness\FitnessInfo;
use yii\base\Widget;
use yii\helpers\Html;
use app\models\FeedbackTrainer;

class WFeedbackTrainer extends Widget
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
        if(!empty($this->client)) {
            $fitInfo = new FitnessInfo();
            $trainers = $fitInfo->getTrainers();
            if (empty($trainers)) {
                $trainers = [];
            }
            ?>

            <form id="FeedbackTrainer">
                <?= Html::hiddenInput('FeedbackTrainer[client_id]]', $this->client->id,['class'=>'client_id']);?>

                <div class="form-group">

                    <?=  Html::label('Тренер', '', ['class' => 'control-label']);?>
                    <?=  Html::dropDownList('FeedbackTrainer[trainer_fit_id]', 'null', $trainers['data']['trainers'], ['class' => 'form-control feedbackTrainerSelect', 'id' => 'trainerSel', 'prompt' => 'Выберите...']);?>
                    <?=  Html::label('Отзыв', '', ['class' => 'control-label']);?>
                    <?=  Html::input('text', 'FeedbackTrainer[feedback]', '',['class' => 'form-control feedbackTrainer']);?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-success  col-md-12 js-feedback-trainer', "id" => "FeedbackTrainerCommit"]);?>
                    <div class="clear"></div>
                </div>
            </form>

            <div class="content-feedback"></div>

            <?php
        }
        else{
            echo '<div class="text">Клиент не найден</div>';
        }
    }
}