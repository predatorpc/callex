<?php
/**
 * Created by PhpStorm.
 * User: rr
 * Date: 05.12.17
 * Time: 10:54
 */

namespace app\models;


use yii\base\Model;

class AutoCall extends Model
{

    /**
     * суть модели в том что бы можно было задать параметры для выходного файла для всей выборки
     * в последующем выне модели можно делать выюорку и потом передавать только номер телефона для которого все будеит строиться
    */

    private $channel = 'Local/89658285276@from-internal';
    private $callerId = 1000;
    private $maxRetries = 5;
    private $retryTime = 300;
    private $waitTime = 20;
    private $context = 'test-sound';
    private $extension = 1000;
    private $priority = 1;
    private $archive = 'yes';

    private $dateToCall;


    /*
     *
Channel: Local/89658285276@from-internal
Callerid: 1000
MaxRetries: 5
RetryTime: 300
WaitTime: 20
Context: test-sound
Extension: 1000
Priority: 1
Archive: yes
    */

    public function __construct(array $config = []){

        if (isset($config['callerId']) && is_numeric($config['callerId'])) {
            $this->callerId = $config['callerId'];
        }

        if (isset($config['maxRetries']) && is_numeric($config['maxRetries'])) {
            $this->maxRetries = $config['maxRetries'];
        }

        if (isset($config['retryTime']) && is_numeric($config['retryTime'])) {
            $this->retryTime = $config['retryTime'];
        }

        if (isset($config['waitTime']) && is_numeric($config['waitTime'])) {
            $this->waitTime = $config['waitTime'];
        }

        if (isset($config['context']) && is_string($config['context'])) {
            $this->context = $config['context'];
        }

        if (isset($config['extension']) && is_numeric($config['extension'])) {
            $this->extension = $config['extension'];
        }

        if (isset($config['priority']) && is_numeric($config['priority'])) {
            $this->priority = $config['priority'];
        }

        if (isset($config['dateToCall']) && is_numeric($config['dateToCall'])) {
            $this->dateToCall = Date('Y-m-D', strtotime($config['priority']));
        }
        else{
            $this->dateToCall = Date('Y-m-d');
        }
    }

    public function createCardToCall($phone = false){

        $phone = $this->adaptationPhone($phone);

        if(!empty($phone)){
            $file = "";
            $file  = "Channel:".str_replace('#phone#', $phone, $this->channel)."\n"
                ."Callerid: ".$this->callerId."\n"
                ."MaxRetries: ".$this->maxRetries."\n"
                ."RetryTime: ".$this->retryTime."\n"
                ."WaitTime: ".$this->waitTime."\n"
                ."Context: ".$this->context."\n"
                ."Extension: ".$this->extension."\n"
                ."Priority: ".$this->priority."\n"
                ."Archive: ".$this->archive."\n";

            $fileName = $phone.'_'.\Yii::$app->uniqueId.'.call';
            $dirName =$_SERVER['DOCUMENT_ROOT'] . '/autocall/'.(!empty($this->dateToCall)?$this->dateToCall:Date('Y-m-d'));
            //создаем директорию с датой
            //var_dump($dirName);die();
            if(!file_exists($dirName)){
                mkdir($dirName, 0777, true);
            }
            return file_put_contents($dirName.'/'.$fileName, $file);
        }

        return false;



    }

    private function adaptationPhone($phone = false){
        //проверить длинну телефонного номера
        // начинаться должен на +79
        //остальные числа на 0
        //возвращаем обработанный телефон
        if(!empty($phone)){
            $phone = preg_replace('/(\+7)|(|)|-/','',$phone);
            if(intval(substr($phone, 0, 1))==9 && strlen($phone)==10){
                return '8'.$phone;
            }

        }
        return false;
    }






}