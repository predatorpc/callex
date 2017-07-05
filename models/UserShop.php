<?php
namespace app\models;


class UserShop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
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
        return
            [
                [['name'], 'string'],
                [['phone'], 'varchar'],
            ];
    }
}