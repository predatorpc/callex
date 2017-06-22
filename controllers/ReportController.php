<?php
namespace app\controllers;

use yii\web\Controller;
use Yii;


class ReportControllerController extends Controller{

    public function actionCalls(){

        return $this->render('calls');
    }
}