<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property integer $club_id
 *
 */

//class User extends ActiveRecordRelation implements IdentityInterface
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_FULL_DELETED = -1;
    const STATUS_ACTIVE = 1;
    /**
     * @inheritdoc
     */

    public static function getDb()
    {
        return \Yii::$app->db;
    }

    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            //password is neccessary within self registration only
            //[['name','email'],'required'],
            //[['name','email','password_hash'],'required'],
            [['name', 'first_name', 'second_name', 'last_name', 'phone', 'email',], 'required'],
            //[['name', 'first_name', 'second_name', 'last_name', 'phone', 'email', 'birthday', 'bonus', 'money', 'created_at', 'updated_at', 'password_reset_token', 'password_hash', 'auth_key'], 'required'],
            [['bonus', 'money', 'created_at', 'updated_at', 'status', 'club_id',  'company_id'], 'integer'],
            [['name', 'first_name', 'second_name', 'last_name', 'email', 'password', 'password_reset_token', 'password_hash', 'auth_key'], 'string', 'max' => 255],
            [['phone'], 'string', 'min' => 12, 'max' => 12],
            [['phone'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED,self::STATUS_FULL_DELETED]],
            [['birthday','password', 'agree', 'gender', 'typeof', 'staff'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID Пользователя'),
            'name' => Yii::t('app', 'Имя пользователя'),
            'first_name' => Yii::t('app', 'Имя'),
            'second_name' => Yii::t('app', 'Фамилия'),
            'last_name' => Yii::t('app', 'Отчество'),
            'phone' => Yii::t('app', 'Телефон'),
            'email' => Yii::t('app', 'Email'),
            'birthday' => Yii::t('app', 'ДР'),
            'bonus' => Yii::t('app', 'Бонусы'),
            'money' => Yii::t('app', 'Деньги'),
            'created_at' => Yii::t('app', 'Регистрация'),
            'updated_at' => Yii::t('app', 'Обновление'),
            'password_reset_token' => Yii::t('app', 'Сброс пароля'),
            'password_hash' => Yii::t('app', 'Пароль (ХЕШ)'),
            'auth_key' => Yii::t('app', 'Ключ авторизации'),
            'club_id' => Yii::t('app', 'ID Привязки к клубу'),
            'company_id' => Yii::t('app', 'ID Привязки к корпорации'),
            'status' => Yii::t('app', 'Активность'),
            'status' => Yii::t('app', 'Активность'),
            'staff' => Yii::t('app', 'Сотрудник'),
            'gender' => Yii::t('app', 'Пол'),
            'agree' => Yii::t('app', 'Прочитал Договор/Лицензионное соглашение'),
            'typeof' => Yii::t('app', 'Тип сотрудника'),

        ];
    }

    public static function findIdentity($id){
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($name){
        return static::findOne(['name' => $name, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByPhone($phone){
        if(empty(static::findOne(['phone' => $phone, 'status' => self::STATUS_ACTIVE]))){
            return static::findOne(['phone' => '+7'.$phone, 'status' => self::STATUS_ACTIVE]);
        }
        else
            return static::findOne(['phone' => $phone, 'status' => self::STATUS_ACTIVE]);
    }


    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        $validatePassword = false;

        if(!isset($this->password_hash) || empty($this->password_hash)){

            if(md5('%'.$password.'%') == $this->password){
                $validatePassword = true;

                $this->password_hash = Yii::$app->security->generatePasswordHash($password);
                $this->auth_key = Yii::$app->security->generateRandomString();

                $allRoles = \Yii::$app->authManager->getRolesByUser($this->id);

                if(!isset($allRoles) || empty($allRoles)){
                    $auth = Yii::$app->authManager;
                    $userRole = $auth->getRole('user');
                    $auth->assign($userRole, $this->id);
                }

                if(!$this->save()){

                    //print_r($this->errors);die();
                };
            }
        }else{
            $validatePassword = Yii::$app->security->validatePassword($password, $this->password_hash);
        }

        return $validatePassword;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getUsersPays(){
        return $this->hasMany(Transactions::className(), ['id' => 'user_id']);
    }

    public function getPromoCodes(){
        return Codes::find()->where(['user_id' => $this->id])->all();
    }


}
