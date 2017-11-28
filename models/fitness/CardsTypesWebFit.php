<?php
namespace app\models\fitness;


class CardsTypesWebFit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cards_types';
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
            [['name', 'description', 'lifetime_id'], 'required'],
            [['description'], 'string'],
            [['price', 'payment_price'], 'number'],
            [['lifetime_id', 'payment_period_id', 'commission_id', 'extend', 'last_month', 'stop_card', 'period', 'show_status', 'show_site_status', 'club_limit', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
//            [['lifetime_id'], 'exist', 'skipOnError' => true, 'targetClass' => CardsLifetime::className(), 'targetAttribute' => ['lifetime_id' => 'id']],
//            [['payment_period_id'], 'exist', 'skipOnError' => true, 'targetClass' => CardsPaymentPeriod::className(), 'targetAttribute' => ['payment_period_id' => 'id']],
//            [['payment_period_id'], 'default', 'value'=>null],
//            [['commission_id'], 'exist', 'skipOnError' => true, 'targetClass' => CardsCommission::className(), 'targetAttribute' => ['commission_id' => 'id']],
        ];
    }

}

