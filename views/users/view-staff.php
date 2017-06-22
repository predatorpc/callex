<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->second_name." ".$model->first_name." ".$model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['staff']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update-staff', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete-staff', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'first_name',
            'second_name',
            'last_name',
            'phone',
            'email:email',
            'birthday',
            'bonus',
            'money',
            'created_at',
            'updated_at',
            'password_reset_token',
            'password_hash',
            'password',
            'auth_key',
            'club_id',
            'status',
        ],
    ]) ?>


    <h2>Карты принадлежащие <?=$this->title?></h2>

    <?php

    foreach ($cards as $card) {

        echo "<h3>ID Карты".$card->card_id."</h3><br>";

        echo DetailView::widget(
            [
                'model' => $card,
                'attributes' => [
                    'card_id',
                    'company_id',
                    'corporative',
                    'created_at',
                    'expires_at',
                    'status',
                ],
            ]
        );

    }
    ?>
</div>