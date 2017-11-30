<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Modal;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!--Ппанель уведомления -->
<div class="alert alert__fix js-alert-close">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <div class="messages"></div>
</div>
<!--./Ппанель уведомления -->
<?php
    // Модальное окно;
    Modal::begin([
        'header' => '<h4 class="modal-title" id="myModalLabel"></h4>',
        'size' => 'modal-min',
        'id' => 'window_pay',
    ]);

    Modal::end();
?>


<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Колл-центр v1.01a',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    ?>
    <a href="/"><img src="/images/callex.png"></a>
    <?php
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
//            ['label' => 'Home', 'url' => ['/site/index']],
//            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Сайт EF.RU', 'url' => ['https://www.extremefitness.ru/']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Вход', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Выход (' . Yii::$app->user->identity->name . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container-fluid"><br>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
    <footer class="footer">
        <div class="container-fluid">
            <p class="pull-left">&copy; Callex TM of ExtremeFitness, Ltd. <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>
</div>



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
