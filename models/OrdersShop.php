<?php
namespace app\models;


class OrdersShop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return \Yii::$app->db_shop;

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'code_id', 'type', 'call_status', 'basket_id', 'add_Bonus', 'add_Rubl','negative_review', 'status'], 'integer'],
            [['extremefitness'], 'number'],
//            [['comments'], 'required'],
            [['comments', 'comments_call_center'], 'string'],
            [['actions_json'], 'string', 'max' => 500],
            [['date'], 'safe']
        ];
    }
}