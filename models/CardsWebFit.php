<?php
namespace app\models;


class CardsWebFit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cards';
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
            [['card_id', 'card_type_id', 'created_at', 'status'], 'required'],
            [['card_id', 'user_id', 'card_type_id', 'company_id', 'corporative', 'barter', 'active', 'club_id', 'promo_link_id', 'status', 'service_field', 'create_by_user', 'create_on_terminal','count_stops'], 'integer'],
            [['balance', 'payment', 'price'], 'number'],
            [['date_update_row', 'created_at', 'expires_at', 'change_at', 'lifetime'], 'safe'],
            ['date_creation', 'default', 'value'=>Date('Y-m-d H:i:s')],
            [['comment'], 'string'],
            [['card_id'], 'unique'],
//            [['card_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CardsTypes::className(), 'targetAttribute' => ['card_type_id' => 'id']],
//            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
//            [['club_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clubs::className(), 'targetAttribute' => ['club_id' => 'id']],
//            [['promo_link_id'], 'exist', 'skipOnError' => true, 'targetClass' => PromoLinks::className(), 'targetAttribute' => ['promo_link_id' => 'id']],
//            [['create_by_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['create_by_user' => 'id']],
//            [['create_on_terminal'], 'exist', 'skipOnError' => true, 'targetClass' => Terminal::className(), 'targetAttribute' => ['create_on_terminal' => 'id']],
            [['series_start', 'series_count','company_id', 'amount'],'integer'],
        ];
    }

}

