<?php
namespace app\models;




class UserFitness extends \yii\db\ActiveRecord
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
    return \Yii::$app->db_fitness;

    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return
            [
                [['second_name','first_name','last_name'], 'string'],
                [['gender'], 'integer'],
                [['phone'], 'varchar'],
            ];
    }
}