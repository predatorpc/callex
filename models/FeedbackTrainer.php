<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback_trainer".
 *
 * @property int $id
 * @property int $client_id
 * @property int $trainer_fit_id
 * @property string $date_creation
 * @property int $create_by_user
 * @property string $feedback
 * @property int $status
 *
 * @property Clients $clinet
 * @property Users $createByUser
 */
class FeedbackTrainer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback_trainer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'trainer_fit_id'], 'required'],
            [['client_id', 'trainer_fit_id', 'create_by_user', 'status'], 'integer'],
            [['date_creation'], 'safe'],
            [['feedback'], 'string', 'max' => 500],
            ['create_by_user', 'default', 'value'=>($this->isNewRecord?(!empty(Yii::$app->user->id)?Yii::$app->user->id:null):$this->create_by_user)],
            ['status', 'default', 'value'=>1],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clients::className(), 'targetAttribute' => ['client_id' => 'id']],
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
            'client_id' => 'client_id ID',
            'trainer_fit_id' => 'Trainer Fit ID',
            'date_creation' => 'Date Creation',
            'create_by_user' => 'Create By User',
            'feedback' => 'Feedback',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClinet()
    {
        return $this->hasOne(Clients::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateByUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'create_by_user']);
    }
}
