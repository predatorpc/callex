<?php
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;

$this->title = 'Импорт клиентов';
$this->params['breadcrumbs'][] = $this->title;
?>


<?= Yii::$app->session->getFlash('error'); ?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <div class="form-group">
<?= Html::fileInput('csv','',['class' => 'btn btn-success']); ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Импорт', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end() ?>