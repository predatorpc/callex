<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Колл-центр v1.01a';
?>

    <div class="site-index">
      <div  class="nav-main" >
        <div class="body-content">
        
        <?php if(Yii::$app->user->can('GodMode')) { ?>
        
	    <h3>Администрирование</h3>
            <div class="form-group">
                <?= Yii::$app->user->can('GodMode') ? Html::a('Пользователи', '/users', ['class' => 'btn btn-primary']) : '' ?>
                <?= Yii::$app->user->can('GodMode') ? Html::a('Клиенты', '/clients', ['class' => 'btn btn-primary']) : '' ?>
                <?= Yii::$app->user->can('GodMode') ? Html::a('Роли', '/roles', ['class' => 'btn btn-danger']) : '' ?>
                <?= Yii::$app->user->can('GodMode') ? Html::a('Журналы кто и что делал', '/', ['class' => 'btn btn-success']) : '' ?>
            </div>
            
	<?php } ?>


        <?php if(Yii::$app->user->can('GodMode') || Yii::$app->user->can('Operator') || Yii::$app->user->can('Manager') ) { ?>
                
            <h3>Операции по работе с клиентами</h3>
            <div class="form-group">
                <?= Yii::$app->user->can('Operator') ? Html::a('Рабочий стол', '/desktop', ['class' => 'btn btn-primary']) : ''  ?>
                <?= Yii::$app->user->can('Operator') ? Html::a('Мои звонки', '/desktop/calls', ['class' => 'btn btn-primary']) : ''  ?>
                <?= Yii::$app->user->can('Operator') ? Html::a('Скрипты разговоров', '/desktop/scripts', ['class' => 'btn btn-primary'])  : '' ?>
                <?= Yii::$app->user->can('Operator') ? Html::a('Справочная информация', '/desktop/docs', ['class' => 'btn btn-success']) : '' ?>
            </div>
    	<?php } ?>
        

        <?php if(Yii::$app->user->can('GodMode') || Yii::$app->user->can('Manager') ) {  ?>
            
            <h3>Отчеты и настроки работы</h3>
            <div class="form-group">
                <?= Yii::$app->user->can('Manager') ? Html::a('Пользователи', '/users', ['class' => 'btn btn-primary']) : '' ?>
                <?= Yii::$app->user->can('Manager') ? Html::a('Отчет по звонкам', '/reports/calls', ['class' => 'btn btn-warning']) : '' ?>            
                <?= Yii::$app->user->can('Manager') ? Html::a('Отчет по производительности', '/reports/power', ['class' => 'btn btn-warning']) : '' ?>
            </div>
	    <div class="form-group">
                <?= Yii::$app->user->can('Manager') ? Html::a('Типы коментариев', '/settings/comment-types', ['class' => 'btn btn-primary']) : '' ?>
                <?= Yii::$app->user->can('Manager') ? Html::a('Загрузка скриптов', '/scripts', ['class' => 'btn btn-primary']) : '' ?>
                <?= Yii::$app->user->can('Manager') ? Html::a('Управление списками', '/settings/lists', ['class' => 'btn btn-primary']) : '' ?>
                <?= Yii::$app->user->can('Manager') ? Html::a('Настроки автораздачи', '/settings/auto-load', ['class' => 'btn btn-primary']) : '' ?>
	    </div>

    	<?php } ?>    
    	
        </div>
      </div>
    </div>