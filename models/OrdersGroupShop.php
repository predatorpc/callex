<?php
namespace app\models;


class OrdersGroupShop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders_groups';
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
            [['order_id', 'delivery_id', 'store_id', 'address_id', 'status','type_id'], 'integer'],
            [['delivery_date'], 'safe'],
            [['delivery_price'], 'number']
        ];
    }
}