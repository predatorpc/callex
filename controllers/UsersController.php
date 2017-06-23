<?php


//////////////////////////////////////////////////////////////////////////////////////
//
// Контроллер пользователей фитнеса в т.ч. клиентов и сотрудников CORE
// 22/12/2016
// mmerzlyakov AKA predator_pc
// special for ExtremeFitness.ru
//
/////////////////////////////////////////////////////////////////////////////////////


namespace app\controllers;

use app\models\AuthAssignment;
use app\models\AuthItem;
use app\models\Cards;
use app\models\Clubs;
use app\models\Corporative;
use app\models\System;
use app\models\UsersPhotos;
use Yii;
use app\models\User;
use app\models\Users;
use app\models\UserSearch;
use app\models\Visits;
use app\models\VisitsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\File;
use yii\web\UploadedFile;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','update','create','view','upload','set-main-image',],
                        'allow' => true,
                        'roles' => ['Operator',],
                    ],
                    [
                        'actions' => ['delete-image',],
                        'allow' => true,
                        'roles' => ['Operator',],
                    ],
                    [
                        'actions' => [ 'staff','create-staff', 'update-staff', 'view-staff',],
                        'allow' => true,
                        'roles' => ['Manager',],
                    ],
                    [
                        'actions' => [
                            //'index',
                            //'update',
                            //'create',
                            //'view',
                            'delete',
                            //'upload',
                            //'staff',
                            //'create-staff',
                            //'update-staff',
                            //'view-staff',
                            //'delete-image',
                            //'set-main-image',
                        ],
                        'allow' => true,
                        'roles' => ['GodMode',],
                    ],
                ],
            ],

            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],*/
        ];
    }

//////////////////////////////////////////////////////////////////////////////////////
// AJAX
//////////////////////////////////////////////////////////////////////////////////////

//"Удаляем" картинку
    public function actionDeleteImage($image_id = null)
    {
        if(!empty($image_id)) {
            $usersPhotos = UsersPhotos::find()
                ->where('id = '.$image_id)->one();
            $usersPhotos->status=0;
            if($usersPhotos->save()) {
                return true;
            }else {
                return json_encode($usersPhotos->errors);
            }
        }
        return false;
    }

//Ставим главную картинку
    public function actionSetMainImage($id = null, $image_id = null)
    {
        if(!empty($image_id) && !empty($id)) {
            $usersPhotos = UsersPhotos::find()
                ->where('user_id = '.$id)->all();

            foreach ($usersPhotos as $usersPhoto) {
                $usersPhoto->main=0;
                $usersPhoto->save();
            }

            $usersPhotos = UsersPhotos::find()
                ->where('user_id = '.$id)
                ->andWhere('id = '.$image_id)->one();
            $usersPhotos->main=1;
            $usersPhotos->status=1;

            if($usersPhotos->save()) {
                return true;
            }else {
                return json_encode($usersPhotos->errors);
            }
        }
        return false;
    }

    public function actionUpload($model_id)
    {
        $model = new File();
        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstances($model, 'imageFile');
            $path = $model->upload($model_id);

            $str = str_replace('\\','',$path);
            $str = str_replace('[','',$str);
            $str = str_replace('"','',$str);
            $str = str_replace(']','',$str);

            $image = new UsersPhotos();
            $image->status=1;
            $image->path=$str;
            $image->user_id=$model_id;
            $image->save();
            return true;
        }
        return false;
    }

//////////////////////////////////////////////////////////////////////////////////////
// END AJAX
//////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////
// Actions
//////////////////////////////////////////////////////////////////////////////////////

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        $model_file = new File();
//        $companies = Corporative::find()->select('id, name')->where('status = 1')->asArray()->all();
    //    $clubs = Clubs::find()->select('id, name')->where('status = 1')->asArray()->all();


        if ($model->load(Yii::$app->request->post())) {
            $model->name = "--noname";
            if(!$model->save(true)){
        //        var_dump($model->errors);die();
                System::txtLogs($model->errors, 'createUsers');
            }
            else {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'model_file' => $model_file,
        //    'clubs' => $clubs,
        //    'companies' => $companies,
        ]);


    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        //$model = $this->findModel($id);
        $model = Users::find()->where('id = '.$id)->one();

        //var_dump($model);die();

        $model_file = new File();
    //    $companies = Corporative::find()->select('id, name')->where('status = 1')->asArray()->all();
    //    $clubs = Clubs::find()->select('id, name')->where('status = 1')->asArray()->all();


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        
            if($model->staff==1){
                if(Yii::$app->user->can('Admin')){
                    $auth = AuthAssignment::find()->where(['user_id'=>$id])->one();
                    if(empty($auth)){
                        $auth = new AuthAssignment();
                        $auth->user_id = $model->id;
                        $auth->item_name = 'User';
                        $auth->description = 'User';
                        $auth->save(true);
                    }
                }
                else{
                    $model->staff=0;
                    $model->save(true);
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            // var_dump($model->errors);
            return $this->render('update', [
                'model' => $model,
                'model_file' => $model_file,
            //    'clubs' => $clubs,
            //    'companies' => $companies,
            ]);
        }
    }


    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateStaff()
    {
        /*$model = new Users();
        $model->load(Yii::$app->request->post());
        print_r($model);die();*/
        $model = new Users();
        $model_file = new File();
    //    $companies = Corporative::find()->select('id, name')->where('status = 1')->asArray()->all();
    //    $clubs = Clubs::find()->select('id, name')->where('status = 1')->asArray()->all();
	    $modelRole = AuthItem::find()->all();


        if ($model->load(Yii::$app->request->post())) {
            if($model->save(true)){
                if($model->staff==1){
                    if(Yii::$app->user->can('Admin')){
                        $modelAuthItems = new AuthItem();
                        $modelAuthItems->load(Yii::$app->request->post());
                        if(!empty($modelAuthItems->name)){
                            $auth = AuthAssignment::find()->where(['user_id'=>$model->id])->one();
                            if(empty($auth)){
                                $auth = new AuthAssignment();
                                $auth->user_id = $model->id;
                            }
                            $auth->item_name = $modelAuthItems->name;
                            $auth->description = $modelAuthItems->name;
                            $auth->save(true);
                        }
                    }
                    else{
                        $model->staff=0;
                        $model->password_hash = '';
                        $model->auth_key = '';
                        $model->save(true);
                    }
                }
                return $this->redirect(['view-staff', 'id' => $model->id]);
            }
            else{
        	
                $model->password = $model->confirmPassword;
            }

        }
        return $this->render('create-staff', [
                'model' => $model,
                'model_file' => $model_file,
            //    'clubs' => $clubs,
            //    'companies' => $companies,
                'modelRole' => $modelRole,
            ]);

    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateStaff($id)
    {
        $model = Users::find()->where(['id'=>$id])->with('role')->one();
        $model_file = new File();
    //    $companies = Corporative::find()->select('id, name')->where('status = 1')->asArray()->all();
    //    $clubs = Clubs::find()->select('id, name')->where('status = 1')->asArray()->all();
        $modelRole = AuthItem::find()->all();
        
        if(Yii::$app->request->isPost){
            if(Yii::$app->user->can('Admin')){
                if(!empty(Yii::$app->request->post('AuthAssignment'))){
                    $updatedUserRole = Yii::$app->authManager->getRolesByUser($id);
                    if(empty($updatedUserRole['Admin'])){
                        $auth = AuthAssignment::find()->where(['user_id'=>$id])->one();
                        if(empty($auth)){
                            $auth = new AuthAssignment();
                        }
                        $auth->load(Yii::$app->request->post());
                        $auth->save(true);
                    }
                }
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save(true)) {
            return $this->redirect(['view-staff', 'id' => $model->id]);
        }
        else {
            return $this->render('update-staff', [
                'model' => $model,
                'model_file' => $model_file,
            //    'clubs' => $clubs,
            //    'companies' => $companies,
                'modelRole' => $modelRole,
            ]);
        }
    }


/////////////////////////////////////////////////////////////////////////////////////////////////////
// STD Actions
/////////////////////////////////////////////////////////////////////////////////////////////////////


    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionStaff()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchStaff(Yii::$app->request->queryParams);

        return $this->render('staff', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    //    $cards = Cards::find()->where('status = 1')->andWhere('user_id = '.$id)->all();

      //  var_dump($cards);die();

        return $this->render('view', [
            'model' => $this->findModel($id),
    //        'cards' => $cards,
        ]);
    }

    public function actionViewStaff($id)
    {
    //    $cards = Cards::find()->where('status = 1')->andWhere('user_id = '.$id)->all();

        return $this->render('view-staff', [
            'model' => $this->findModel($id),
        //    'cards' => $cards,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id);//->delete();
        //$this
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////
// STD Actions
/////////////////////////////////////////////////////////////////////////////////////////////////////


}
