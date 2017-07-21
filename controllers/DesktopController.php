<?php
namespace app\controllers;

use app\models\Comments;
use app\models\UsersClients;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use Yii;
use app\models\Clients;
use app\models\System;
use app\models\Sentsms;
use yii\filters\AccessControl;
use app\models\Scripts;
use app\models\UsersClientsSearch;

class DesktopController extends Controller{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['import'],
                        'allow' => true,
                        'roles' => ['GodMode'],
                    ],
                    [
                        'actions' => ['index','client-card','add-comment','sms-send','sms-save','scripts','view-script','calls','find-client'],
                        'allow' => true,
                        'roles' => ['Manager','Operator'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(){
        $statistic = Clients::find()
            ->select('count(clients.id) as count,call_status_id')
            ->leftJoin('users_clients', '`users_clients`.`client_id` = `clients`.`id`')
            ->andWhere(['`users_clients`.`user_id`'=> Yii::$app->user->getId()])
            ->andWhere(['>=','`users_clients`.date',date('Y-m-d 00:00:00')])
            ->andWhere(['<=','`users_clients`.date',date('Y-m-d 23:59:59')])
            ->andWhere(['`users_clients`.status'=>1])
            ->andWhere(['NOT IN','clients.call_status_id',[0,1,5,6]])
            ->groupBy('clients.call_status_id')
            ->All();


        $todayCountCalls = Clients::find()
            ->leftJoin('users_clients', '`users_clients`.`client_id` = `clients`.`id`')
            ->andWhere(['`users_clients`.`user_id`'=> Yii::$app->user->getId()])
            ->andWhere(['>=','`users_clients`.date',date('Y-m-d 00:00:00')])
            ->andWhere(['<=','`users_clients`.date',date('Y-m-d 23:59:59')])
            ->andWhere(['NOT IN','clients.call_status_id',[0,1,5,6]])
            ->andWhere(['`users_clients`.status'=>1])
            ->count();

        return $this->render('index',['todayCountCalls'=>$todayCountCalls,'statistic'=>$statistic]);
    }

    public function actionClientCard($id = false){
        $session = Yii::$app->session;
        $sms = '';
        if($id != false){
            $client = Clients::find()->where(['id'=>$id])->One();
            $client->is_being_edited = 1;
            $client->save();
            $user_client = new UsersClients();
            $user_client->user_id = Yii::$app->user->getId();
            $user_client->client_id = $client->id;
            $user_client->date = date('Y-m-d H:i:s');
            $user_client->status = 0;
            if(!$user_client->save()){
                print_r($user_client->getErrors());die;
            }

        }elseif($edit_user_id = $session->get('edit_client_id')) {
            $client = Clients::find()->where(['id' => $edit_user_id])->One();
            if (Sentsms::find()->where(['client_id' => $client->id, 'user_id' => Yii::$app->user->getId(), 'status' => 0])->One()) {
                $smsDB = Sentsms::find()->where(['client_id' => $client->id, 'user_id' => Yii::$app->user->getId(), 'status' => 0])->One();
                if (isset($smsDB) && !empty($smsDB->text)) {
                    $sms = $smsDB->text;
                }
            }
        }elseif (Clients::find()
            ->leftJoin('users_clients', '`users_clients`.`client_id` = `clients`.`id`')
            ->andWhere(['`users_clients`.`user_id`'=> Yii::$app->user->getId()])
            ->andWhere(['clients.call_status_id'=>2])
            ->andWhere(['<','clients.next_call',date('Y-m-d H:i:s',strtotime('+ 5 minutes'))])
            ->orderBy('next_call')
            ->One()){
            $client = Clients::find()
                ->leftJoin('users_clients', '`users_clients`.`client_id` = `clients`.`id`')
                ->andWhere(['`users_clients`.`user_id`'=> Yii::$app->user->getId()])
                ->andWhere(['clients.call_status_id'=>2])
                ->andWhere(['<','clients.next_call',date('Y-m-d H:i:s',strtotime('+ 5 minutes'))])
                ->orderBy('next_call')
                ->One();
                if(!$client){
                    return 'Не обработанне клинты кончились';

                }
                $client->is_being_edited = 1;
                $client->save();
                $user_client = new UsersClients();
                $user_client->user_id = Yii::$app->user->getId();
                $user_client->client_id = $client->id;
                $user_client->date = date('Y-m-d H:i:s');
                $user_client->status = 0;
                if(!$user_client->save()){
                    print_r($user_client->getErrors());die;
                }
                $session->set('edit_client_id', $client->id);

        }else{
            $usedClient = ArrayHelper::getColumn(Comments::find()->select('client_id')->distinct()->all(),'client_id');
            $client = Clients::find()->where(['<>','call_status_id','4'])->andWhere(['<>','call_status_id','3'])->andWhere(['call_status_id'=>'0'])->andWhere(['is_being_edited'=> 0])->andWhere(['NOT IN','id',$usedClient])->orderBy('RAND()')->One();
            if(!$client){
                return 'Не обработанне клинты кончились';

            }
            $client->is_being_edited = 1;
            $client->save();
            $user_client = new UsersClients();
            $user_client->user_id = Yii::$app->user->getId();
            $user_client->client_id = $client->id;
            $user_client->date = date('Y-m-d H:i:s');
            $user_client->status = 0;
            if(!$user_client->save()){
                print_r($user_client->getErrors());die;
            }
            $session->set('edit_client_id', $client->id);

        }


        if ($client->load(Yii::$app->request->post()) && (strtotime('now') - $session->get('time_start')>=10)) {
            $client->next_call = date('Y-m-d H:i:s',strtotime($client->next_call));
            if(!$client->save()){
                $session['time_start'] = strtotime('now');
                return $this->render('client-card',['client'=>$client,'sms'=>$sms]);
            }
            $session->remove('time_start');
            $client->is_being_edited = 0;
            if($client->call_status_id != 2){
                $client->next_call =  '';
            }
            $client->save();
            $user_client = UsersClients::find()->where(['client_id'=>$client->id,'user_id'=>Yii::$app->user->getId(),'status'=>0])->One();
            if($user_client){
                $user_client->status = 1;
                if(!$user_client->save()){
                    print_r($user_client->getErrors());die;
                }
            }

            $session->remove('edit_client_id');

            return $this->redirect(['index']);
        }
        else {
            $session['time_start'] = strtotime('now');
            return $this->render('client-card',[
                'client'=>$client,
                'sms'=>$sms
            ]);
        }
    }

    public function actionAddComment(){
        if(Yii::$app->request->isPost){
            $request = Yii::$app->request->post('comment');
            $comment = new Comments();
            $comment->action_id = $request['action_id'];
            //$comment->type_id = $request['type_id'];
            $comment->text = $request['text'];
            $comment->created_by_user = Yii::$app->user->getId();
            $comment->client_id = $request['client_id'];
            $comment->date = date('Y-m-d H:i:s');
            $comment->status = 1;
            if($comment->save()){
                return $comment->user->second_name .
                    '(' . date('d.m.Y H:i:s', strtotime($comment->date)) .
                    '): '.(isset($actions[$comment->action_id])?$actions[$comment->action_id]:'')
                    .' - '. $comment->text;
            }else{
                print_r($comment->getErrors());
            }
        }
    }

    public function actionImport(){
        $n = 0;
        //print_r($_FILES);die;
        if(isset($_FILES['csv'])){
            $csv = file_get_contents($_FILES['csv']['tmp_name']);
            $lines = explode( "\n", $csv );
            $headers = explode(';', array_shift( $lines ) );
            $data = array();
            foreach ( $lines as $line ) {
                $row = array();
                foreach ( explode(';', $line ) as $key => $field )
                    $row[ $headers[ $key ] ] = $field;
                $row = array_filter( $row );
                $data[] = $row;
            }
            //print_r($data);

            foreach ($data as $key => $value){
                if(isset($value['phone']) && !empty($value['phone'])){
                    if(substr($value['phone'],0,1) == 8 || substr($value['phone'],0,1) == 7){
                        $value['phone'] = substr($value['phone'],1);
                    }
                    $value['phone'] = str_replace('+7','',$value['phone']);
                    if(Clients::find()->where(['LIKE','phone',$value['phone']])->count() == 0){
                        $client = new Clients();
                        //$name = explode(' ',$value['name']);
                        $client->first_name = isset($value['first_name']) ? $value['first_name'] : '';
                        $client->second_name = isset($value['second_name']) ? $value['second_name'] : '';
                        $client->last_name = isset($value['last_name']) ? $value['last_name'] : '';
                        $client->birthday = isset($value['birthday']) ? $value['birthday'] : '';
                        $client->phone = $value['phone'];
                        $client->call_status_id = 0;
                        //$client->last_call = '0000-00-00 00:00:00';
                        if(isset($value['car'])){
                            $client->car = $value['car'];
                        }
                        if(isset($value['children'])){
                            $client->car = $value['children'];
                        }
                        if(isset($value['shop_id'])){
                            $client->car = $value['shop_id'];
                        }
                        if(isset($value['helper_id'])){
                            $client->car = $value['helper_id'];
                        }
                        if(isset($value['fitness_id'])){
                            $client->car = $value['fitness_id'];
                        }
                        $client->status = 1;
                        if(!$client->save()){
                            print_r($client->getErrors());
                        }

                        $n++;

                    }
                }
            }
        }
        \Yii::$app->getSession()->setFlash('error', 'Добавлено '.$n.' клиентов.');
        return $this->render('import');
    }

    public function actionSmsSend(){

        $params = Yii::$app->request->post('sms');
        $result = [
            'status'=>'true',
            'message'=>'Не отправлено'
        ];
        if(!empty($params)){
            if(!empty($params['sms']) && !empty($params['client_id'])){
                $client = Clients::find()->where(['id'=>$params['client_id']])->one();
                if(!empty($client) && !empty($client->phone)){
                    if(Sentsms::find()->where(['client_id'=>$client->id,'user_id'=>Yii::$app->user->getId(),'status'=>0])->One()){
                        $sms = Sentsms::find()->where(['client_id'=>$client->id,'user_id'=>Yii::$app->user->getId(),'status'=>0])->One();
                    }
                    else{
                        $sms = new Sentsms();
                    }
                    $sms->client_id = $params['client_id'];
                    $sms->text = $params['sms'];
                    $sms->user_id = (!empty(Yii::$app->user->id)?Yii::$app->user->id:0);
                    $sms->date = date('Y-m-d H:i:s');
                    $sms->status = 1;
                    if(!$sms->save()){
                        $result = [
                            'status'=>'true',
                            'message'=>'Сохранено с ошибками'
                        ];
                        //print_r($sms->getErrors());
                    }
                    else{
                        $result = [
                            'status'=>'true',
                            'message'=>'Сохранено и отправлено'
                        ];
                    }
                    System::sendSms('7'.$client->phone, $params['sms']);
                }

            }
        }
        return json_encode($result);

    }

    public function actionSmsSave(){

        $params = Yii::$app->request->post('sms');
        $result = ['status'=>'false'];
        if(!empty($params)){
            if(!empty($params['sms']) && !empty($params['client_id'])){
                $client = Clients::find()->where(['id'=>$params['client_id']])->one();
                if(!empty($client) && !empty($client->phone)){
                    if(Sentsms::find()->where(['client_id'=>$client->id,'user_id'=>Yii::$app->user->getId(),'status'=>0])->One()){
                        $sms = Sentsms::find()->where(['client_id'=>$client->id,'user_id'=>Yii::$app->user->getId(),'status'=>0])->One();
                    }else{
                        $sms = new Sentsms();
                    }
                    $sms->client_id = $params['client_id'];
                    $sms->text = $params['sms'];
                    $sms->user_id = (!empty(Yii::$app->user->id)?Yii::$app->user->id:0);
                    $sms->date = date('Y-m-d H:i:s');
                    $sms->status = 0;
                    if(!$sms->save()){
                        print_r($sms->getErrors());
                    }
                    $result = ['status'=>'save'];
                }

            }
        }
        return json_encode($result);

    }

    public function actionScripts(){

        $scripts = Scripts::find()->where(['status'=>1])->All();

        return $this->render('scripts',['scripts'=>$scripts]);
    }

    public function actionViewScript($id)
    {
        return $this->render('view-script', [
            'model' => Scripts::findOne($id),
        ]);
    }

    public function actionCalls(){
        $searchModel = new UsersClientsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('calls',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionFindClient()
    {
        if(Yii::$app->request->post('phone')){
            $phone = Yii::$app->request->post('phone');
            if(strlen($phone)== 10){
                $client = Clients::find()->where(['LIKE','phone',$phone])->One();
                if($client){
                    $this->redirect(['client-card','id'=> $client->id]);
                }
            }
        }
        return $this->render('find-client');
    }
}
