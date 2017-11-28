<?php
namespace app\models;

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
            [['gender', 'client_fit_id', 'client_shop_id', 'call_status_id', 'client_helper_id', 'is_being_edited', 'children', 'car', 'anketa', 'status'], 'integer'],
            [['first_name', 'second_name', 'last_name', 'district'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 300],
            [['call_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => CallStatuses::className(), 'targetAttribute' => ['call_status_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'Имя',
            'second_name' => 'Отчество',
            'last_name' => 'Фамилия',
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

    public function afterValidate()
    {
        parent::afterValidate(); // TODO: Change the autogenerated stub
    }
}
