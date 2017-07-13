<?php
/**
 * Created by PhpStorm.
 * User: rr
 * Date: 12.07.17
 * Time: 12:46
 */

namespace app\controllers;

use app\models\BeelinePhone;
use app\models\System;
use Codeception\Module\Cli;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;


class PhoneController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $result=[
            'status'=>'false',
            'error'=>'1',
            'message'=>'ошибка'
        ];
        $params = Yii::$app->request->post();
        if(!empty($params['phone']) && !empty($params['user'])){
            $beeline = new BeelinePhone();
            $result = $beeline->getCall($params['phone'], $params['user']);
            if(!empty($result)){
                $result=[
                    'status'=>'true',
                    'error'=>'0',
                    'message'=>'вызов сделан, возьми трубку'
                ];
            }
        }
        return json_encode($result);

    }


}