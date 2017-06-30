<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $searchModel app\models\ScriptsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Скрипты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scripts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
        if(Yii::$app->user->can('Manager')){

        echo Html::a('Управление скриптами', ['/scripts'], ['class' => 'btn btn-success']);

        }

    ?>
    <ul>
    <?php
        foreach ($scripts as $script){
           echo '<li><a href="/desktop/view-script?id='.$script->id.'">'.$script->name.'</a></li>';
        }
    ?>
    </ul>


</div>