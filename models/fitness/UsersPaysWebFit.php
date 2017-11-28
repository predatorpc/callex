<?php
namespace app\models\fitness;


class UsersPaysWebFit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_pays';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return \Yii::$app->db_fitness;

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_id', 'pay_method_id', 'pay_type_id', 'money'], 'required'],
            [['card_id', 'user_id', 'pay_method_id', 'pay_type_id', 'terminal_id', 'create_by_user', 'active', 'status'], 'integer'],
            [['money'], 'number'],
            [['date', 'companyName',/*'date_start', 'date_end'*/], 'safe'],
            [['comment'], 'string', 'max' => 255],
            [['transaction_id'], 'string', 'max' => 50],
            [['error_code'], 'string', 'max' => 10],
//            [['card_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cards::className(), 'targetAttribute' => ['card_id' => 'id']],
//            [['pay_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PayMethod::className(), 'targetAttribute' => ['pay_method_id' => 'id']],
//            [['pay_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PayTypes::className(), 'targetAttribute' => ['pay_type_id' => 'id']],
//            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
//            [['create_by_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['create_by_user' => 'id']],
        ];
    }

}

