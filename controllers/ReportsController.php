<?php
namespace app\controllers;
use app\models\Comments;
use app\models\CommentsSearch;
use app\models\Clients;
use yii\filters\AccessControl;

use yii\web\Controller;
use Yii;


class ReportsController extends Controller{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['calls'],
                        'allow' => true,
                        'roles' => ['GodMode'],
                    ],
                ],
            ],
        ];
    }

    public function actionCalls(){


        $searchModel = new CommentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('calls', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
}