<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback_trainer".
 *
 * @property int $id
 * @property int $clinet_id
 * @property int $trainer_fit_id
 * @property string $date_creation
 * @property int $create_by_user
 * @property int $feedback
 * @property int $status
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
            [['clinet_id', 'trainer_fit_id'], 'required'],
            [['clinet_id', 'trainer_fit_id', 'create_by_user', 'feedback', 'status'], 'integer'],
            [['date_creation'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clinet_id' => 'Clinet ID',
            'trainer_fit_id' => 'Trainer Fit ID',
            'date_creation' => 'Date Creation',
            'create_by_user' => 'Create By User',
            'feedback' => 'Feedback',
            'status' => 'Status',
        ];
    }
}
