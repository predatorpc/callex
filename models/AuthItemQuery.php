<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AuthItem]].
 *
 * @see AuthItem
 */
class AuthItemQuery extends \yii\db\ActiveQuery
{
    public static function getDb()
    {
        return \Yii::$app->db;
    }
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return AuthItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AuthItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}