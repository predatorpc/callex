<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property integer $user_id
 * @property integer $created_at
 * @property string $description
 *
 * @property User $user
 */
class AuthAssignment extends \yii\db\ActiveRecord
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
        return 'auth_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['description'], 'string'],
            [['item_name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_name' => 'Item Name',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return AuthAssignmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuthAssignmentQuery(get_called_class());
    }
    
    public static function getRoleTreeName($name=false){
            //отдает дерово ролей
                    $roleTree = [];
                            if(!empty($name)){
                                        $childRoles = Yii::$app->authManager->getChildren($name);
                                                    $roleTree = array_keys($childRoles);
                                                                //var_dump($roleTree);
                                                                            while($childRolesCur = current($childRoles)){
                                                                                            //$roleTree[$childRolesCur->name] = $childRolesCur;
                                                                                                            $roleTree = array_merge($roleTree, self::getRoleTreeName($childRolesCur->name));
                                                                                                                            next($childRoles);
                                                                                                                                        }
                                                                                                                                                }
                                                                                                                                                        //var_dump(array_keys($roleTree));
                                                                                                                                                                return $roleTree;
                                                                                                                                                                    }
                                                                                                                                                                    
                                                                                                                                                                        public static function getRoleTree($name=false){
                                                                                                                                                                                //отдает дерово ролей
                                                                                                                                                                                        $roleTree = [];
                                                                                                                                                                                                if(!empty($name)){
                                                                                                                                                                                                            $childRoles = Yii::$app->authManager->getChildren($name);
                                                                                                                                                                                                                        while($childRolesCur = current($childRoles)){
                                                                                                                                                                                                                                        $roleTree[$childRolesCur->name] = $childRolesCur;
                                                                                                                                                                                                                                                        $roleTree[] = self::getRoleTree($childRolesCur->name);
                                                                                                                                                                                                                                                                        next($childRoles);
                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                                                    //var_dump(array_keys($roleTree));
                                                                                                                                                                                                                                                                                                            return $roleTree;
                                                                                                                                                                                                                                                                                                                }
}
