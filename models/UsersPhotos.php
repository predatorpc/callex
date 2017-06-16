<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_photos".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $image_path
 * @property integer $status
 * @property integer $created_at
 */
class UsersPhotos extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->db;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['user_id', 'path', 'status'], 'required'],
            [['user_id', 'status', 'created_at'], 'integer'],
            [['path'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID Фото',
            'user_id' => 'ID Пользователя',
            'path' => 'Путь картинки',
            'status' => 'Активность',
            'created_at' => 'Дата регистрации',
        ];
    }

    //TODO: Больше методов в моделях по получению собственных данных модели работает быстрее

    public static function getFirstImage($model)
    {
        if(!empty($model)) {
            $ret =  self::find()->where('user_id = ' . $model)
                ->andWhere('main = 1')
                ->andWhere('status = 1')->one();

            if(empty($ret))
            {
                $ret = self::find()->where('user_id = ' . $model)
                    ->andWhere('status = 1')->one();
                return $ret;
            }

            return $ret;
        }
        return false;
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
