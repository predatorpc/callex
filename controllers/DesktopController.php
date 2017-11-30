<?php
namespace app\controllers;

use app\components\WTest;
use app\models\ClientsInfo;
use app\models\ClientsInfoLinks;
use app\models\Comments;
use app\models\fitness\FitnessInfo;
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
                        'actions' => ['index','client-card','add-comment','sms-send','sms-save','scripts','view-script','calls','find-client', 'client-old-info', 'client-change-info','client-transaction-info'],
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
            ->andWhere(['NOT IN','clients.call_status_id',[0,1,5,6,9]])
            ->groupBy('clients.call_status_id')
            ->All();


        $todayCountCalls = Clients::find()
            ->leftJoin('users_clients', '`users_clients`.`client_id` = `clients`.`id`')
            ->andWhere(['`users_clients`.`user_id`'=> Yii::$app->user->getId()])
            ->andWhere(['>=','`users_clients`.date',date('Y-m-d 00:00:00')])
            ->andWhere(['<=','`users_clients`.date',date('Y-m-d 23:59:59')])
            ->andWhere(['NOT IN','clients.call_status_id',[0,1,5,6,9]])
            ->andWhere(['`users_clients`.status'=>1])
            ->count();

        return $this->render('index',['todayCountCalls'=>$todayCountCalls,'statistic'=>$statistic]);
    }

    public function actionClientCard(){
        // id клиента храниться в сессии edit_client_id и время начала звонка
        // если id клента пустое или его нет то нжно получить нового
        // нового выбираем из очереде перезвонов и если очередь пустая то тогда из CLients
        // из клинетов выбираем сперва тех у кого Call_status_id = 0
        // потом из клиентов которым когда либо уже звонили
        $session = Yii::$app->session;
        $session->set('edit_client_id',168168);

        $editUserId = $session->get('edit_client_id');
        if(!empty($editUserId) && is_numeric($editUserId)) {
            $client = Clients::find()->where(['id' => $editUserId, 'is_being_edited'=>1])->One();
        }
        else{
            //найти нового абонента
            //устновить флаг редиктирования абонента
            //присвоить текущему пользователю абонента
            //записать в сессию
            $client = Clients::getClientToCall();
            $client->is_being_edited = 1;
            if($client->save(true)){
                $userClient = UsersClients::find()->where(['client_id'=>$client->id, 'status'=>1])->one();
                if(!empty($userClient)){
                    $client = false;
                }
                else{
                    $userClient = new UsersClients();
                    $userClient->user_id = Yii::$app->user->id;
                    $userClient->client_id = $client->id;
                    $userClient->status = 1;
                    if(!$userClient->save(true)){
                        $client = false;
                    }
                }

            }
            else{
                $client = false;
            }
        }
        if(!empty($client)){
            //записать абонента в сессию
            $session->set('edit_client_id', $client->id);
            $session['time_start'] = time();
        }


        if(Yii::$app->request->isPost){
            if(!empty(Yii::$app->request->post('Clients'))){//сохраняем данные клиента
                $clientUpd = Yii::$app->request->post('Clients');
                if($client->id == $clientUpd['id']){
                    if($client->load(Yii::$app->request->post()) && $client->save(true)){
                        return json_encode(['status'=>'success','message'=>'Успешно сохранился']);
                    }
                }


            }
            //print_r(Yii::$app->request->post());die();
        }

        return $this->render('client-card',[
            'client'=>$client,
        ]);


        /*
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
            $client->save(true);
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

            return $this->render('client-card',[
                'client'=>$client,
                'sms'=>$sms
            ]);
        }*/
    }

    public function actionAddComment(){
        $result = [
            'status'=>'error',
            'message'=>'Пустые данные'
        ];

        if(Yii::$app->request->isPost) {
            if (Yii::$app->request->isPost && !empty(Yii::$app->request->post('Comment'))) {
                $params = Yii::$app->request->post('Comment');
                if (!empty($params['action_id']) && !empty($params['text']) && !empty($params['client_id']) && is_numeric($params['client_id']) ) {
                    $client = Clients::find()->where(['id'=>$params['client_id']])->one();
                    if(!empty($client) && !empty($client->phone)){
                        $comment = new Comments();
                        $comment->action_id = $params['action_id'];
                        $comment->text = $params['text'];
                        $comment->created_by_user = (!empty(Yii::$app->user->id)?Yii::$app->user->id:NULL);
                        $comment->client_id = $client->id;
                        $comment->status = 1;
                        if($comment->save(true)){
                            $result = [
                                'status'=>'success',
                                'message'=>'Сохранено'
                            ];
                            //если все успешно то удалить сессию и отдать ссылку для релода страницы
                        }
                        else{
                            $result = [
                                'status'=>'error',
                                'message'=>'Не сохранено'
                            ];
                        }
                    }
                    else{
                        $result = [
                            'status'=>'error',
                            'message'=>'Клиент не найден'
                        ];
                    }
                }
                else{
                    $result = [
                        'status'=>'error',
                        'message'=>'Укажите все данные'
                    ];
                }
            }
        }

        return json_encode($result);
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

        $result = [
            'status'=>'error',
            'message'=>'Пустые данные'
        ];
        if(Yii::$app->request->isPost && !empty(Yii::$app->request->post('Sms'))){
            $params = Yii::$app->request->post('Sms');
            if(!empty($params['sms']) && !empty($params['client_id']) && is_numeric($params['client_id'])){
                $client = Clients::find()->where(['id'=>$params['client_id']])->one();
                if(!empty($client) && !empty($client->phone)){
                    $sms = new Sentsms();
                    $sms->client_id = $client->id;
                    $sms->text = $params['sms'];
                    $sms->user_id = (!empty(Yii::$app->user->id)?Yii::$app->user->id:NULL);
                    $sms->status = 1;
                    if(!$sms->save(true)){
                        $result = [
                            'status'=>'error',
                            'message'=>'Не сохранено'
                        ];
                    }
                    else{
                        System::sendSms('7'.preg_replace('/\D|(\G7)|(\G8)/','','+79237042936'), $params['sms']);
                        $result = [
                            'status'=>'success',
                            'message'=>'Сохранено и отправлено'
                        ];
                    }
                }
                else{
                    $result = [
                        'status'=>'error',
                        'message'=>'Нет номер телефона'
                    ];
                }
            }
        }
        return json_encode($result);

    }

    public function actionSmsSave(){//TODO: DELETE THIS ACTIONS

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

    public function actionClientOldInfo(){
        $post =  Yii::$app->request->post();

        if(!empty($post['clientOldInfo']) && !empty($post['infoItem']) &&!empty($post['client']) ){
            if(!is_numeric($post['infoItem']) && !is_numeric($post['client'])) return false;

            $info = ClientsInfo::find()->where(['status'=>1, 'id'=>$post['infoItem']])->one();
            if(!empty($info)){
                $clientInfoLinks = ClientsInfoLinks::find()->where(['info_id'=>$info->id, 'client_id'=>$post['client'], 'status_show'=>0, 'status'=>1])->andWhere(['<>', 'date_disable', 0])->all();

                return $this->renderAjax('area-info',[
                    'model'=>$clientInfoLinks
                ]);

            }
        }
    }


    //изменение значения чекбокса
    public function actionClientChangeInfo(){

        $clientInfo = Yii::$app->request->post('ClientInfoLinksId');
        $clientId = Yii::$app->request->post('ClientInfoLinksClientId');
        if(!empty($clientInfo) && is_numeric($clientInfo) && !empty($clientId) && is_numeric($clientId)){
            $info = ClientsInfo::find()->where(['status'=>1, 'id'=>$clientInfo])->one();
            if(!empty($info)){
                $clientInfoLinkUpd = ClientsInfoLinks::find()->where(['info_id'=>$info->id, 'client_id'=>$clientId, 'date_disable'=>null, 'status_show'=>1, 'status'=>1])->one();
                if(!empty($clientInfoLinkUpd)){//деактивировать инетерес
                    $clientInfoLinkUpd->date_disable = Date('Y-m-d H:i:s');
                    $clientInfoLinkUpd->status_show = 0;
                }
                else{//добавить новый
                    $clientInfoLinkUpd = new ClientsInfoLinks();
                    $clientInfoLinkUpd->client_id = $clientId;
                    $clientInfoLinkUpd->info_id = $info->id;
                    $clientInfoLinkUpd->status_show=1;
                    $clientInfoLinkUpd->status=1;
                }

                if($clientInfoLinkUpd->save(true)){
                    $client = Clients::find()->where(['id'=>$clientId])->One();
                    return \app\components\desktop\WClientsInfo::widget(['client'=>$client]);
                } else{
                    return 'Данные не сохранены';
                }

            } else{
                return 'Данные не верные';
            }
        }
    }

    // Транзакция;
    public function actionClientTransactionInfo(){

       $post = Yii::$app->request->post();
       if(!empty($post['transaction'])) {
           if(!is_numeric($post['card_id'])) return false;
           $fitnessInfo = new FitnessInfo(['card'=>$post['card_id']]);
           return $this->renderAjax('transaction-info',[
               'data'=>$fitnessInfo->getCardInfo()
           ]);
       }
    }


}
