<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "anketa".
 *
 * @property int $id
 * @property string $first_name
 * @property string $second_name
 * @property string $last_name
 * @property string $phone
 * @property int $fitness
 * @property int $shop
 * @property int $gender
 * @property string $commnet
 * @property string $date
 * @property int $status
 */
class Anketa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'anketa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'phone','age' ], 'required'],
            [['age', 'fitness', 'shop', 'gender', 'status'], 'integer'],
            [['commnet'], 'string'],
            [['date'], 'safe'],
            [['first_name', 'second_name', 'last_name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => '*Имя',
            'second_name' => 'Отчество',
            'last_name' => '*Фамилия',
            'phone' => '*Телефон',
            'fitness' => 'Экстрифитнесс',
            'shop' => 'Экстримшоп',
            'gender' => 'Пол',
            'commnet' => 'Комментарий',
            'date' => 'Дата',
            'status' => 'Статус',
            'age' => '*Возраст',
        ];
    }
}
