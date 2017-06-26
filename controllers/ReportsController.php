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
                        'actions' => ['calls','power'],
                        'allow' => true,
                        'roles' => ['Manager'],
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

    public function actionPower(){
        $searchModel = new CommentsSearch();
        $dataProvider = $searchModel->searchPower(Yii::$app->request->queryParams);

        return $this->render('power', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}