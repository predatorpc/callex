<?php
namespace app\models;


class GoodsShop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
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
            [['show','type_id', 'shop_id', 'producer_id', 'country_id', 'weight_id', 'bonus', 'order', 'delay', 'count_pack', 'count_min', 'rating', 'discount', 'main', 'new', 'sale', 'user_id', 'user_last_update', 'position', 's', 'confirm','master_active','count_buy','type', 'status','iwish','hit'], 'integer'],
            [['description','color_bg'], 'string'],
            [['comission'], 'number'],
            [['code', 'full_name', 'name', 'link', 'seo_title', 'seo_description', 'seo_keywords'], 'string', 'max' => 128],
            [['producer_name'], 'string', 'max' => 128],
            [['price_out'], 'number'],
            [['productImage','date', 'date_create', 'date_update'], 'safe'],
        ];
    }

}

