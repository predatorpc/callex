<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\Clients;
use Yii;

class WClietsList extends Widget
{
    public $message;
    public $clients;

    public function init()
    {
        parent::init();
        $this->clients = Clients::find()->leftJoin('users_clients', '`users_clients`.`client_id` = `clients`.`id`')->andWhere(['`users_clients`.`user_id`'=> Yii::$app->user->getId()])->asArray()->All();

        if ($this->clients === null) {
            $this->clients = [];
        }
    }

    public function run()
    {
//        echo Html::ul(ArrayHelper::getColumn($this->clients,'name'));die;
////        return
    }
}
?>