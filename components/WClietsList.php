<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use app\models\Clients;

class WClietsList extends Widget
{
    public $message;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = 'Hello Worlddd';
        }
    }

    public function run()
    {
        return Html::encode($this->message);
    }
}
?>