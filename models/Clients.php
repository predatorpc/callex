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
            [['birthday', 'date_create', 'date_update'], 'safe'],
            [['gender', 'car', 'children', 'call_status_id', 'client_shop_id', 'client_helper_id', 'client_fit_id', 'status'], 'integer'],
            [['first_name', 'second_name', 'last_name', 'district'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'second_name' => 'Second Name',
            'last_name' => 'Last Name',
            'birthday' => 'Birthday',
            'gender' => 'Gender',
            'phone' => 'Phone',
            'district' => 'District',
            'car' => 'Car',
            'children' => 'Children',
            'call_status_id' => 'Call Status ID',
            'client_shop_id' => 'Client Shop ID',
            'client_helper_id' => 'Client Helper ID',
            'client_fit_id' => 'Client Fit ID',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesktops()
    {
        return $this->hasMany(Desktop::className(), ['client_id' => 'id']);
    }
}
