<?php
namespace app\controllers;
use app\models\Comments;
use app\models\CommentsSearch;
use app\models\Clients;

use yii\web\Controller;
use Yii;


class ReportsController extends Controller{

    public function actionCalls(){


        $searchModel = new CommentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('calls', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
}