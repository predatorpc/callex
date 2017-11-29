<?php
namespace app\components\desktop;

use app\models\Clients;
use app\models\fitness\UserFitness;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use Yii;

class WFitnessInfo  extends Widget
{
    public $client;


    public function init()
    {
        parent::init();
        if ($this->client === null) {
            $this->client = false;
        }
    }

    public function run()
    {




    }

}