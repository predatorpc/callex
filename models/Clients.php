<?php
namespace app\models;

use phpDocumentor\Reflection\Types\Self_;
use Yii;

class Clients extends \yii\db\ActiveRecord
{

    public $user_id;
    public $count;

    public static function tableName(){
        return 'clients';
    }


    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['birthday', 'last_call', 'next_call', 'date_create', 'date_update'], 'safe'],
            [['gender', 'client_fit_id', 'client_shop_id', 'call_status_id', 'client_helper_id', 'next_call_by_user', 'is_being_edited', 'children', 'car', 'anketa', 'service_field_rand', 'status'], 'integer'],
            [['first_name', 'second_name', 'last_name', 'district'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 300],
            [['call_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => CallStatuses::className(), 'targetAttribute' => ['call_status_id' => 'id']],
            [['next_call_by_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['next_call_by_user' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'Имя',
            'second_name' => 'Фамилия',
            'last_name' => 'Отчество',
            'birthday' => 'Дата рождения',
            'gender' => 'Пол',
            'phone' => 'Телефон',
            'district' => 'Район',
            'car' => 'Машина',
            'children' => 'Дети',
            'call_status_id' => 'Статус звонка',
            'client_shop_id' => 'ID клиента шопа',
            'client_helper_id' => 'ID клиента хелпера',
            'client_fit_id' => 'ID клиента фитнесса',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'next_call_by_user' => 'Next Call By User',
            'status' => 'Статус',
            'email'=>'email'
        ];
    }

    public function getCallStatus(){
        return $this->hasOne(CallStatuses::className(), ['id' => 'call_status_id']);
    }

    public function getClientsInfoLinks(){
        return $this->hasMany(ClientsInfoLinks::className(), ['client_id' => 'id']);
    }

    public function getComments(){
        return $this->hasMany(Comments::className(), ['client_id' => 'id']);
    }

    public function getUsersClients(){
        return $this->hasMany(UsersClients::className(), ['client_id' => 'id']);
    }

    public function checkRelevanceInfo($infoId = false){
        $result = false;
        if(!empty($infoId) && is_numeric($infoId)){
            $cliInfoLink = ClientsInfoLinks::find()->where(['client_id'=>$this->id, 'info_id'=>$infoId, 'date_disable'=>NULL, 'status_show'=>1])->one();
            if(!empty($cliInfoLink)){
                $result = true;
            }
        }
        return $result;

    }

    public function getNextCallByUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'next_call_by_user']);
    }

    public function getFeedbackTrainers()
    {
        return $this->hasMany(FeedbackTrainer::className(), ['client_id' => 'id']);
    }


    /**
     * Получение клиента для звонка
    */
    public static function getClientToCall(){
        $client = false;
        $time = time();
        while((empty($client)) || ($time+2)<time()){
            $client = self::getReCallClient();
            if(empty($client)) {
                $client = self::getNextClient();
            }

        }
        return $client;
    }

    //next client card
    public static function getNextClient(){
        return self::find()
            ->where(['clients.status'=>1, 'clients.is_being_edited'=>0, 'clients.service_field_rand'=>rand(1,1000)])
            ->orderBy(['last_call'=>SORT_ASC])
            ->one();
    }

    //recall client
    public static function getReCallClient(){
        return self::find()
            ->where(['next_call_by_user'=>Yii::$app->user->id, 'status'=>1])
            ->andWhere(['<', 'clients.next_call', Date('Y-m-d H:i:s',strtotime('+ 5 minutes')) ])
            ->andWhere(['>', 'clients.next_call', Date('Y-m-d H:i:s',strtotime('-2 day', time())) ])
            ->orderBy(['next_call'=>SORT_ASC])
            ->one();
    }


/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////
//
//    public static function getClientToCall(){
//        $client = Clients::getReCallClient();
//        if(empty($client)){
//            $client = Clients::getNewClient();
//            if(empty($client)){
//                $client = Clients::getClientsEmptyComments();
//                if(empty($client)){
//                    $client = Clients::getClientsUsersFree();
//                    if(empty($client)){
//                        $client = Clients::getSelfClientCall();
//                        if(empty($client)){
//                            $client = false;
//                        }
//                    }
//                }
//            }
//        }
//        return $client;
//    }
//
//    //новые
//    public static function getNewClient(){
//        //
//        // *SQL*
//        //SELECT clients.*
//        //FROM `clients`
//        //LEFT JOIN comments on comments.client_id=clients.id and comments.status=1
//        //LEFT JOIN users_clients on users_clients.client_id = clients.id and users_clients.status=1
//        //WHERE clients.status=1
//        //GROUP by clients.id
//        //HAVING count(comments.id)=0 and count(users_clients.id)=0
//        //LIMIT 1
//        //-------
//        //нет пользователь удален или нет комментария
//        //SELECT users.phone, clients.*
//        //FROM `clients`
//        //LEFT JOIN comments on comments.client_id=clients.id and comments.status=1
//        //LEFT JOIN users_clients on users_clients.client_id = clients.id and users_clients.status=1
//        //LEft join users on users.id = users_clients.user_id and users.status=1
//        //WHERE clients.status=1
//        //GROUP by clients.id
//        //HAVING count(comments.id)=0 or count(users.id)=0
//
//
//        //получаем новых клиентом кому не звонили еще
//        //то есть тех у которых нет комментария и нет пользователя
//        return self::find()
//            ->leftJoin('comments', 'comments.client_id=clients.id and comments.status=1')
//            ->leftJoin('users_clients', 'users_clients.client_id = clients.id and users_clients.status=1')
//            ->leftJoin('users', 'users.id = users_clients.user_id and users.status=1')//TODO ???? хз на сколько оправдано
//            ->where(['clients.status'=>1, 'clients.is_being_edited'=>0, 'clients.service_field_rand'=>rand(1,1000)])
//            ->groupBy('clients.id')
//            //->having(['count(comments.id)'=>0, 'count(users_clients.id)'=>0])
//            ->having(['count(comments.id)'=>0, 'count(users.id)'=>0])
//            ->orderBy('RAND()')
//            ->limit(1)
//            ->one();
//
//    }
//
//    //перезвонить
//    public static function getReCallClient(){
////*******SQL*******
////        SELECT clients.*
////        FROM `clients`, users_clients
////        WHERE users_clients.user_id = 4359 and
////                users_clients.status=1 and
////                clients.id = users_clients.client_id and
////                clients.call_status_id`=2 AND
////                clients.status`=1 and
////                clients.next_call` < '2017-11-28 07:47:13'
////        ORDER BY `next_call`
//        return self::find()->select('clients.*')->from('clients, users_clients')
//            ->where(['users_clients.user_id'=>Yii::$app->user->id, 'users_clients.status'=>1,])
//            ->andWhere('clients.id = users_clients.client_id')
//            ->andWhere(['clients.status'=>1, /*'clients.is_being_edited'=>0, clients.service_field_rand'=>rand(1,1000)*/ ])
//            ->andWhere(['<', 'clients.next_call', Date('Y-m-d H:i:s',strtotime('+ 5 minutes')) ])
//            ->orderBy('next_call')
//            ->One();
//    }
//
//    //пустые комментарии
//    public static function getClientsEmptyComments(){
//        return self::find()
//            ->leftJoin('comments', 'comments.client_id=clients.id and comments.status=1')
//            ->leftJoin('users_clients', 'users_clients.client_id = clients.id and users_clients.status=1')
//            ->leftJoin('users', 'users.id = users_clients.user_id and users.status=1')//TODO ???? хз на сколько оправдано
//            ->where(['clients.status'=>1, 'clients.is_being_edited'=>0, 'clients.service_field_rand'=>rand(1,1000)])
//            ->groupBy('clients.id')
//            //->having(['count(comments.id)'=>0, 'count(users_clients.id)'=>0])
//            ->having(['count(comments.id)'=>0, 'count(users.id)'=>0])
//            ->orderBy('RAND()')
//            ->limit(1)
//            ->one();
//    }
//
//    //пользователь уже уволен
//    public static function getClientsUsersFree(){
//        return self::find()
//            ->leftJoin('users_clients', 'users_clients.client_id = clients.id and users_clients.status=1')
//            ->leftJoin('users', 'users.id = users_clients.user_id and users.status=1')//TODO ???? хз на сколько оправдано
//            ->where(['clients.status'=>1, 'clients.is_being_edited'=>0, 'clients.service_field_rand'=>rand(1,1000)])
//            ->groupBy('clients.id')
//            ->having(['count(users.id)'=>0])
//            ->orderBy('RAND()')
//            ->limit(1)
//            ->one();
//    }
//
//    //звонить собственным клиентам
//    public static function getSelfClientCall(){
//        return self::find()->select('clients.*')->from('clients, users_clients')
//            ->where(['users_clients.user_id'=>Yii::$app->user->id, 'users_clients.status'=>1,])
//            ->andWhere('clients.id = users_clients.client_id')
//            ->andWhere(['clients.status'=>1, 'clients.is_being_edited'=>0, 'next_call'=>NULL, 'clients.service_field_rand'=>rand(1,1000) ])
//            ->orderBy(['last_call'=>SORT_ASC])
//            ->One();
//    }




}
