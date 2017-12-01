<?php
namespace app\components\html;

use yii\base\Widget;
use yii\helpers\Html;
use app\models\fitness\FitnessInfo;

class WTrainersListComments extends Widget
{

    public $model;

    public function run()
    {
        if(empty($this->model)) return 'Нет запесей';
        $fitInfo = new FitnessInfo();
        $trainers = $fitInfo->getTrainers();
        if (empty($trainers)) {
            $trainers = [];
        }

        ?>
        <div class="items__com">
            <?php foreach($this->model as $item): ?>

                <div class="item" data-comment_id="<?=$item->id?>" >
                    <button type="button" class="close js-trainers-comments-delete" data-dismiss="alert" aria-hidden="true">×</button>
                    <b class="title"><?=!empty($trainers['data']['trainers'][$item->trainer_fit_id]) ? $trainers['data']['trainers'][$item->trainer_fit_id] : ''?></b>
                    <div class="date"><?=date('m.d.Y',strtotime($item->date_creation))?></div>
                    <div class="text js-comments-text-show"><?=$item->feedback?></div>
                    <div class="content-text hidden_r">
                        <input type="text " value="<?=$item->feedback?>" class="feedback" />
                       <button type="button" class="btn btn-success btn-sm js-trainers-comments-update">Сохранить</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php
    }
}