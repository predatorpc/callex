<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\CommentsTypes;
use app\models\CommentsActions;
use app\models\Comments;
use yii\helpers\ArrayHelper;
use kartik\datetime\DateTimePicker;
use app\models\UserFitness;
use app\models\UserShop;

/* @var $this yii\web\View */
/* @var $model app\models\Clients */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clients-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
    <div class="col-md-3">

        <input type="hidden" name="client_id" value="<?=$model->id;?>">

        <input type="hidden" name="comment_send" value="0">

        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'second_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

        <?php //= $form->field($model, 'birthday')->textInput() ?>
        <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Выберите ...'],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'format' => 'yyyy-mm-dd',
        ]
        ]);?>

        <?= $form->field($model, 'gender')->dropDownList(['1' => 'Ж', '2' => 'М'], ['prompt' => 'Выберите...']) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        <?= Html::a('Позвонить клиенту', '#', ['class'=>'btn btn-success', 'rel'=>'100', 'onclick'=>'call('.$model->phone.','.(!empty(\app\models\Users::find()->where(['id'=>Yii::$app->user->id])->one()->phone_id)?\app\models\Users::find()->where(['id'=>Yii::$app->user->id])->one()->phone_id:'0').');'])?>
    </div>
        <div class="col-md-3">

        <?= $form->field($model, 'district')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'car')->checkbox() ?>

    <?= $form->field($model, 'children')->checkbox() ?>

    <?= $form->field($model, 'call_status_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\CallStatuses::find()->where(['status'=>1])->All(),'id','name')) ?>

    <div id="next_call"style="display: none;">
            <?= DateTimePicker::widget([
                'name' => 'Clients[next_call]',
                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                'value' => '',
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'dd.mm.yyyy HH:ii',
                ],
            ]);?>
    </div>

    <?php //= $form->field($model, 'client_shop_id')->textInput() ?>
            <?php /*
            if(isset($model->phone) && !empty($model->phone)){
                $phone = $model->phone;
                $user = UserShop::find()->where(['LIKE','phone',$phone])->One();
                if($user){
                    echo '<a target="_blank" style="font-size: 25px;font-weight: bold;" href="https://www.extremeshop.ru/user/view?id='.$user->id.'">Профиль в extremeshop</a>';
                }
            }
            */?>

    <?php //= $form->field($model, 'client_helper_id')->textInput() ?>
    <br>
    <?php //= $form->field($model, 'client_fit_id')->textInput() ?>
            <?php
                if(isset($model->phone) && !empty($model->phone)){
                    $phone = $model->phone;
                    $user = UserFitness::find()->where(['LIKE','phone',$phone])->One();
                    if($user){
                        echo '<a target="_blank" style="font-size: 25px;font-weight: bold;" href="http://web.extremefitness.ru/users/view?id='.$user->id.'">Профиль в фитнессе</a>';
                    }
                }
            ?>

    <?= $form->field($model, 'last_call')->hiddenInput(['value'=> date('Y-m-d H:i:s')])->label(false) ?>

    <?php //= $form->field($model, 'date_create')->textInput() ?>

    <?php //= $form->field($model, 'date_update')->textInput() ?>

    <?php //= $form->field($model, 'status')->checkbox() ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success client', 'disabled'=>'disabled']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-md-6" >
            <form id="comments">
                <?=Html::hiddenInput('comment[client_id]',$model->id);?>
            <div class="form-group">
                <?= Html::label('Комментарии','',['class'=>'control-label']);?>
                <?php
                $comments = [];
                foreach (Comments::find()->where(['client_id' => $model->id])->orderBy('date DESC')->limit(5)->All() as $comment) {
                    $comments[] = $comment->user->second_name .
                        '(' . date('d.m.Y H:i:s', strtotime($comment->date)) .
                        '): '.(isset($actions[$comment->action_id])?$actions[$comment->action_id]:'')
                        .' - '. $comment->text;
                }
                ?>
                <?= Html::ul($comments,['id'=>'comments_list']);?>
            </div>
            <div class="form-group">
                <?= Html::label('Действие','',['class'=>'control-label']);?>
                <?= Html::dropDownList('comment[action_id]', 'null', ArrayHelper::map(CommentsActions::find()->All(),'id','name'),['prompt' => 'Выберитe..','class'=>'form-control','id'=> 'action']);?>
            </div>
            <?php /*
                <div class="form-group">
                <?= Html::label('Тип','',['class'=>'control-label']);?>
                <?= Html::dropDownList('comment[type_id]', 'null', ArrayHelper::map(CommentsTypes::find()->All(),'id','name'),['prompt' => 'Выбирете..','class'=>'form-control']);?>
            </div> */?>
            <div class="form-group">
                <?= Html::label('Комментарий','',['class'=>'control-label']);?>
                <?= Html::textarea('comment[text]','',['class'=>'form-control']) ?>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Оставить комментарий', ['class' => 'btn btn-success', "id"=>"sendcomment"]) ?>
            </div>
            </form>

            <form id="sms">
                <?=Html::hiddenInput('sms[client_id]]',$model->id);?>
                <div class="form-group">
                    <?= Html::label('Текст','',['class'=>'control-label']);?>
                    <?= Html::input('text','sms[sms]',(isset($sms)) ? $sms : '',['class'=>'form-control']) ?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Сохранить смс', ['class' => 'btn btn-warning', "id"=>"savesms"]) ?>
                    <?= Html::submitButton('Сохранить и отправить смс', ['class' => 'btn btn-warning', "id"=>"sendsms"]) ?>
                </div>
            </form>
        </div>
    </div>

</div>
