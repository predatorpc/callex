<?php
use app\components\desktop\WClientsData;
use app\components\desktop\WClientsInfo;
use app\components\desktop\WComments;
use app\components\desktop\WSmsForm;

?>
<div class="clients-form">
    <div class="row">
        <div class="col-md-3"><?=WClientsData::widget(['client'=>$model]);?></div>
        <div class="col-md-3"><?=WClientsInfo::widget(['client'=>$model]);?></div>
        <div class="col-md-6">
            <?=WComments::widget(['client'=>$model]);?>
            <?=WSmsForm::widget(['client'=>$model]);?>
            <?php
                $f = new \app\models\fitness\FitnessInfo(['phone'=>'79237042936']);
                $f->getUserInfo();
            ?>
        </div>
    </div>
</div>
<div class="clearfix"></div>
