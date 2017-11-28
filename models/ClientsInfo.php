<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clients_info".
 *
 * @property int $id
 * @property string $name
 * @property int $group_id
 * @property int $status
 *
 * @property ClientsInfoGroups $group
 * @property ClientsInfoLinks[] $clientsInfoLinks
 */
class ClientsInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clients_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'group_id'], 'required'],
            [['group_id', 'status'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => ClientsInfoGroups::className(), 'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'group_id' => 'Group ID',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(ClientsInfoGroups::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientsInfoLinks()
    {
        return $this->hasMany(ClientsInfoLinks::className(), ['info_id' => 'id']);
    }
}
