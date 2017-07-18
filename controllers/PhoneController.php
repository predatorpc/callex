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

use Yii;

use yii\web\Controller;


class PhoneController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        //$beeline = new BeelinePhone();
        //System::mesprint($beeline->getCall(9237042936, 229));die();
        //$beeline = new BeelinePhone();
        //System::mesprint($beeline->setPhoneNumber(9237042936));
        //System::mesprint($beeline->setUserId(9607967813));
        //System::mesprint($beeline->getCall());
        //System::mesprint($beeline->getCall(9237042936, 9607967813));

        $params = Yii::$app->request->post();
        if(!empty($params['phone']) && !empty($params['user'])){
            $beeline = new BeelinePhone();
            //return $beeline->getCall(9237042936, 9607967813);
            return $beeline->getCall($params['phone'], $params['user']);

        }
        return false;

    }


}