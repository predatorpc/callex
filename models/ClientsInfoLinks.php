<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clients_info_links".
 *
 * @property int $id
 * @property int $client_id
 * @property int $info_id
 * @property string $date_creation
 * @property string $date_disable
 * @property int $status_show
 * @property int $create_by_user
 * @property int $status
 *
 * @property Clients $client
 * @property ClientsInfo $info
 * @property Users $createByUser
 */
class ClientsInfoLinks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clients_info_links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'info_id'], 'required'],
            [['client_id', 'info_id', 'status_show', 'create_by_user', 'status'], 'integer'],
            [['date_creation', 'date_disable'], 'safe'],
            ['date_creation', 'default', 'value'=>Date('Y-m-d H:i:s')],
            ['create_by_user', 'default', 'value'=>($this->isNewRecord?(!empty(Yii::$app->user->id)?Yii::$app->user->id:null):$this->create_by_user)],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clients::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['info_id'], 'exist', 'skipOnError' => true, 'targetClass' => ClientsInfo::className(), 'targetAttribute' => ['info_id' => 'id']],
            [['create_by_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['create_by_user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'info_id' => 'Info ID',
            'date_creation' => 'Date Creation',
            'date_disable' => 'Date Disable',
            'status_show' => 'Status Show',
            'create_by_user' => 'Create By User',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Clients::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfo()
    {
        return $this->hasOne(ClientsInfo::className(), ['id' => 'info_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateByUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'create_by_user']);
    }
}
