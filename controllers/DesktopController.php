<?php
namespace app\controllers;

use yii\base\Controller;
use Yii;
use app\models\Clients;

class DesktopController extends Controller{

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionClientCard(){
        $user_id = Yii::$app->user->getId();
        $client = Clients::find()->where([''])->One();
        return $this->render('client-card');

    }
}
