<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $name
 * @property string $first_name
 * @property string $second_name
 * @property string $last_name
 * @property string $phone
 * @property string $email
 * @property integer $birthday
 * @property integer $bonus
 * @property integer $money
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password_reset_token
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $status
 */
class Users extends \yii\db\ActiveRecord
{
    const SCENARIO_IMPORT = 'import';//без обязательных полей
    const SCENARIO_REGISTRAION = 'registration';//с обязательными полями
    const SCENARIO_REGISTRAION_STEP_ONE = 'registration_step_one';//с обязательными полями

    public $confirmPassword='';
    public $passwordNew='';
    public $userNameSearch='';
    public $roleName='';
    public $promo;
    //public $code;

    //s: for self registration
    public $day;
    public $month;
    public $year;
    public $cardId;
    public $type_id;
    //e: for self registration
    public $pin;
    public $smsKey;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }
    
    public static function getDb()
    {
        return \Yii::$app->db;
    }

    public function rules()
    {
        return [
            /*[['name', 'first_name', 'second_name', 'last_name', 'phone', 'email', 'birthday', 'bonus', 'money', 'created_at', 'updated_at', 'password_reset_token', 'password_hash', 'auth_key', 'status'], 'required'],
            [['bonus', 'money', 'created_at', 'updated_at', 'status', 'club_id', 'company_id'], 'integer'],
            [['name', 'first_name', 'second_name', 'last_name', 'email', 'password_reset_token', 'password_hash', 'auth_key'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
            [['phone'], 'unique'],
            [['birthday','password', 'agree', 'gender', 'typeof', 'staff'],'safe'],*/


            [['phone'], 'required','message'=>Yii::t('app',''), 'on'=>self::SCENARIO_REGISTRAION_STEP_ONE],
            [['phone'], 'match', 'pattern' => '/^[0-9]{10}$/', 'message'=>Yii::t('app',''), 'on'=>self::SCENARIO_REGISTRAION_STEP_ONE],
            [['phone'], 'string', 'max' => 10, 'message'=>'', 'on'=> self::SCENARIO_REGISTRAION_STEP_ONE],
            [['pin'], 'required','message'=>Yii::t('app',''), 'message'=>'', 'on'=>self::SCENARIO_REGISTRAION_STEP_ONE],
            [['pin'], 'integer', 'max'=>9999, 'message'=>'', 'on'=>self::SCENARIO_REGISTRAION_STEP_ONE],
            [['pin'], 'compare', 'compareAttribute' => 'smsKey', 'message'=>'', 'on'=>self::SCENARIO_REGISTRAION_STEP_ONE],

            // Правила валидация;
            [['second_name'], 'required','message'=>'', 'on'=>self::SCENARIO_REGISTRAION],//Yii::t('app','Введите фамилию')
            [['first_name'], 'required','message'=>'', 'on'=>self::SCENARIO_REGISTRAION],//Yii::t('app','Введите имя')
            [['last_name'], 'required','message'=>'','on'=>self::SCENARIO_REGISTRAION],//Yii::t('app','Введите отчество')
            [['gender'], 'required','message'=>'', 'on'=>self::SCENARIO_REGISTRAION],//Yii::t('app','Выберите пол')
            [['day','month','year'], 'required','message'=>'', 'on'=>self::SCENARIO_REGISTRAION],
            [['phone'], 'required','message'=>'', 'on'=>self::SCENARIO_REGISTRAION],//Yii::t('app','Введите телефон')
            //[['pin'], 'required','message'=>Yii::t('app',''), 'on'=>self::SCENARIO_REGISTRAION],
            //[['pin'], 'string', 'max'=>4, 'on'=>self::SCENARIO_REGISTRAION],
            //[['pin'], 'compare', 'compareAttribute' => 'smsKey', 'on'=>self::SCENARIO_REGISTRAION],


            //import
            [['phone'], 'required', 'message'=>Yii::t('app','Введите телефон'), 'on'=>self::SCENARIO_IMPORT],

            //по умолчанию
            [['second_name'], 'required','message'=>Yii::t('app','Введите фамилию'), 'on'=>self::SCENARIO_DEFAULT],
            [['first_name'], 'required','message'=>Yii::t('app','Введите имя'), 'on'=>self::SCENARIO_DEFAULT],
            [['last_name'], 'required','message'=>Yii::t('app','Введите отчество'), 'on'=>self::SCENARIO_DEFAULT],
            //temp comment
            //[['gender'], 'required','message'=>Yii::t('app','Выберите пол'), 'on'=>self::SCENARIO_DEFAULT],
            //[['birthday'], 'required','message'=>'Введите дату рождения', 'on'=>self::SCENARIO_DEFAULT],
            [['phone'], 'required','message'=>Yii::t('app','Введите телефон'), 'on'=>self::SCENARIO_DEFAULT],
            //[['email'], 'required','message'=>Yii::t('app','Введите email'), 'on'=>self::SCENARIO_DEFAULT],



            [['birthday', 'date_update_row'], 'safe'],
            [['bonus', 'money'], 'number'],
            [['promo',/*'code',*/'day','month','year','pin', 'created_at', 'updated_at', 'club_id', 'company_id', 'staff', 'gender', 'agree', 'typeof', 'sms_key', 'check_phone', 'status', 'smsKey'], 'integer'],
            [['name', 'first_name', 'second_name', 'last_name', 'email', 'password_reset_token', 'password_hash', 'password', 'confirmPassword', 'auth_key'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
            [['phone'], 'unique'],
            ['date_creation', 'default', 'value'=>Date('Y-m-d H:i:s')],
            [['passwordNew', 'confirmPassword'], 'validPass'],
        //    [['club_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clubs::className(), 'targetAttribute' => ['club_id' => 'id']],
            [['cardId', 'type_id'], 'integer'],
    	    [['club_id', 'phone_id'], 'safe'],
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
            'staff' => Yii::t('app', 'Сотрудник'),
            'gender' => Yii::t('app', 'Пол'),
            'sms_key' => 'Sms Key',
            'check_phone' => 'Check Phone',
            'agree' => Yii::t('app', 'Прочитал Договор/Лицензионное соглашение'),
            'typeof' => Yii::t('app', 'Тип сотрудника'),
            'phone_id' => Yii::t('app', 'id звонилки'),

        ];
    }

    public function validPass($attribute, $params){
        //TODO:: валидация пароля и поставить hash
        //if($this->isNewRecord && $this->staff==1){
        if($this->staff==1){
            if(strlen($this->passwordNew)>0){
                if(strlen($this->confirmPassword)>0){
                    if(strcmp($this->confirmPassword, $this->passwordNew)==0) {
                        $this->password_hash = Yii::$app->security->generatePasswordHash($this->passwordNew);
                        $this->auth_key = Yii::$app->security->generateRandomString();
                    }
                    else{
                        $this->addError('confirmPassword', 'Пароли не совпадают');
                    }
                }
                else{
                    $this->addError('confirmPassword', 'Введите подтверждение пароля');
                }
            }
            else{
                $this->addError('passwordNew', 'Введите пароль');
            }
        }
        $this->passwordNew='';
        $this->confirmPassword='';

    }

    public function getCards()
    {
        return $this->hasMany(Cards::className(), ['user_id' => 'id']);
    }


    public static function getUserByCard($card_id = null){

        if(!empty($card_id)) {
            $card = Cards::find()->where(['card_id' => $card_id])->one();

            if (!empty($card)) {
                $user = Users::find()->where(['id' => $card->user_id])->one();

                if (!empty($user)) {
                    return $user;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        else
            return false;
    }

    public static function getUserByPhone($phone = null){

        if(!empty($phone)) {
            $user = Users::find()->where(['phone' => $phone])->one();
            if(!empty($user))
                return $user;
            else
                return false;
        }
        else
            return false;

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClub()
    {
        return $this->hasOne(Clubs::className(), ['id' => 'club_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersPays()
    {
        return $this->hasMany(UsersPays::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersPaysCreateor()
    {
        return $this->hasMany(UsersPays::className(), ['create_by_user' => 'id']);
    }

    public function getUsersPhotos()
    {
        return $this->hasMany(UsersPhotos::className(), ['user_id' => 'id']);
    }
    public function getRole()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }

    public function generateSmsKey(){
        $this->sms_key = rand(1000,9999);
        $this->check_phone = 0;
        if($this->save(true)){
            //System::sendSms($this->phone, $this->sms_key);
            System::sendTelegrammPayments($this->sms_key);
            return $this->sms_key;
        }
        return false;

    }
    public function vilidSmsKey($key=false){
        if(!empty($key)&& !empty($this->id) ){
            if($this->sms_key == $key){
                $this->check_phone=1;
                $this->save(true);
                return true;
            }
        }
        return  false;

    }
}
