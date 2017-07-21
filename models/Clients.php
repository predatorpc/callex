<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clients".
 *
 * @property int $id
 * @property string $first_name
 * @property string $second_name
 * @property string $last_name
 * @property string $birthday
 * @property int $gender
 * @property string $phone
 * @property string $district
 * @property int $car
 * @property int $children
 * @property int $call_status_id
 * @property int $client_shop_id
 * @property int $client_helper_id
 * @property int $client_fit_id
 * @property string $date_create
 * @property string $date_update
 * @property int $status 1
 *
 * @property Desktop[] $desktops
 */
class Clients extends \yii\db\ActiveRecord
{

    public $user_id;
    public $count;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['birthday', 'last_call', 'next_call', 'date_create', 'date_update'], 'safe'],
            [['gender', 'car', 'children', 'client_shop_id', 'call_status_id', 'client_helper_id', 'client_fit_id', 'is_being_edited', 'anketa', 'status'], 'integer'],
            [['first_name', 'second_name', 'last_name', 'district'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['call_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => CallStatuses::className(), 'targetAttribute' => ['call_status_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallStatus()
    {
        return $this->hasOne(CallStatuses::className(), ['id' => 'call_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersClients()
    {
        return $this->hasMany(UsersClients::className(), ['client_id' => 'id']);
    }
}
