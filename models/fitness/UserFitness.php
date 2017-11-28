<?php
namespace app\models\fitness;




class UserFitness extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
    return 'users';
    }

    public static function getDb()
    {
    return \Yii::$app->db_fitness;

    }

    public function rules()
    {
        return [
            [['birthday', 'date_creation', 'date_update_row'], 'safe'],
            [['staff', 'gender', 'check_phone', 'outsourcing', 'status'], 'integer'],
            [['name', 'first_name', 'second_name', 'last_name', 'email',], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
            [['phone'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'first_name' => 'First Name',
            'second_name' => 'Second Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'birthday' => 'Birthday',
            'staff' => 'Staff',
            'gender' => 'Gender',
            'check_phone' => 'Check Phone',
            'date_creation' => 'Date Creation',
            'date_update_row' => 'Date Update Row',
            'outsourcing' => 'Outsourcing',
            'status' => 'Status',
        ];
    }

    public function getCards()
    {
        return $this->hasMany(CardsWebFit::className(), ['user_id' => 'id']);
    }
}