<?php
namespace app\models;


class OrdersItemsShop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders_items';
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
            [['order_group_id', 'good_id','store_id', 'variation_id', 'count', 'count_save', 'seller_status_id_', 'status_id', 'bonusBack', 'rublBack', 'status'], 'integer'],
            [['time', 'receive', 'release'], 'safe'],
            [['comission', 'price', 'discount', 'fee', 'bonus'], 'number'],
//            [['comments', 'comments_shop'], 'required'],
            [['comments', 'comments_shop', 'comments_call_center'], 'string'],
            [['user_name'], 'string', 'max' => 64],
            [['order_id','date','prod_id','item_id','count_item','image','producer_name','delivery_address','delivery_name','variation_id','itemVariantId'], 'safe'],
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(GoodsShop::className(), ['id' => 'good_id']);
    }
}