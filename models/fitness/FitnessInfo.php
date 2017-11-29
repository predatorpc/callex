<?php
/**
 * Created by PhpStorm.
 * User: rr
 * Date: 27.11.17
 * Time: 9:52
 */

namespace app\models\fitness;


use yii\base\Model;

class FitnessInfo extends Model
{
    const URL_T = 'https://web.extremefitness.ru/ajax/callex';
    const URL = 'https://web.extremefitness.ru/ajax/callex';
    public $user;
    public $card;

    private $phone;

    public function __construct(array $config = [])
    {
        //для установки параметров доступны только phone и card
        //установка важных параметров

        if(!empty($config)){
            if(!empty($config['phone'])){
                $this->phone = preg_replace('/(\+7)|(|)|-/','', $config['phone']);
            }
            else{
                $this->phone = false;
            }

            if(!empty($config['card'])){
                $this->card = $config['card'];
            }
            else{
                $this->card = false;
            }
        }
        else{
            $this->phone = false;
            $this->card = false;
        }
    }

    /**
     * получаем данные
    */
    public function getUserInfo(){
        if(!empty($this->phone)){
            //делаем запрос на web
            return $this->requestFitnessGet(['phone'=>$this->phone]);
        }
        return false;
    }

    public function getCardInfo(){
        if(!empty($this->card)){
            //делаем запрос на web
            return $this->requestFitnessGet(['card'=>$this->card]);
        }
        return false;
    }




    private function requestFitnessGet($urlParams=false){
        $url ='';
        if(!empty($urlParams)){
            $url = '&'.http_build_query($urlParams);
        }
        //var_dump(self::URL.'?token='.\Yii::$app->params['webFitnessToken'].$url);die();

        $curl = curl_init(self::URL.'?token='.\Yii::$app->params['webFitnessToken'].$url);
        $options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPGET => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        );
        curl_setopt_array($curl, $options);
        $data = curl_exec($curl);
        //var_dump($data);

        $result=json_decode($data, true);
        curl_close($curl);
        return $result;
    }

}