<?php
/**
 * Created by PhpStorm.
 * User: rr
 * Date: 12.07.17
 * Time: 9:39
 */

namespace app\models;
use Yii;


class BeelinePhone
{
    const URL_TEST = 'https://vn.beeline.ru/com.broadsoft.xsi-actions/test/v2.0/user/userid/calls/new/';
    const URL = 'https://cloudpbx.beeline.ru/apis/portal';

    private $login;
    private $passwd;
    private $token;

    /*для совершения звонка нужно установить кто звонит userid и кому звонить*/
    private $userId;
    private $phoneNumber;


    public function __construct(){
        $this->login = Yii::$app->params['beelinePhoneLogin'];
        $this->passwd = Yii::$app->params['beelinePhonePasswd'];
        $this->token = Yii::$app->params['beelineToken'];
    }

    /*
     * Установка параметров
     * */

    /*Установка userId кто звонит с проверками*/
    public function setUserId($userId=false){
        $returnValue = false;
        if(!empty($userId)){
            $result = $this->findAbonent($userId);
            if(!empty($result)){
                foreach ($result as $key => $item){
                    if($item == $userId){
                        $this->userId = $item;
                        $returnValue = $this->userId;
                    }
                }
            }
        }
        return $returnValue;
    }

    /*устанока номера телеофона кому звонить*/
    public function setPhoneNumber($phone=false){
        $result = false;
        if(!empty($phone)){
            $phone = preg_replace('/\D|(\G7)|(\G8)/','',$phone);
            if(strlen($phone)==10 && is_numeric($phone)){
                $this->phoneNumber = $phone;
                $result = $phone;
            }
        }
        return $result;
    }

    /*
     * Конец установки параметров
     * */
    public function getAbonents(){
        return $this->requestV2PaymentGet(self::URL.'/abonents');
    }

    public function findAbonent($param=false){
        if(!empty($param)){
            return $this->requestV2PaymentGet(self::URL.'/abonents'.'/'.urlencode($param));
        }
        return false;
    }

    public function getCall($phone=false, $userId=false){
        if(!empty($phone)){
            $this->setPhoneNumber($phone);
        }
        if(!empty($userId)){
            $this->setUserId($userId);
        }

        if(!empty($this->phoneNumber) && !empty($this->userId)){
            return $this->call();
        }
    }

    private function call(){
        return $this->requestV2PaymentPost(self::URL.'/abonents'.'/'.urlencode($this->userId).'/call?phoneNumber=8'.$this->phoneNumber);
    }

    private function requestV2PaymentPost($url){
        $curl = curl_init($url);
        $options = [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            //CURLOPT_FOLLOWLOCATION => 1,
            //CURLOPT_SSL_VERIFYHOST => 0,
            //CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER =>[
                'X-MPBX-API-AUTH-TOKEN:'.$this->token
            ],

        ];
        curl_setopt_array($curl, $options);

        curl_setopt($curl, CURLOPT_POSTFIELDS, urlencode(http_build_query(['d'=>'d'])));
        $response = curl_exec($curl);
//        var_dump($response);
        curl_close($curl);
        return $response;
    }

    private function requestV2PaymentGet($url){
        $curl = curl_init($url);
        $options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPGET => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER =>[
                'X-MPBX-API-AUTH-TOKEN:'.$this->token
            ],

        );
        curl_setopt_array($curl, $options);
        $data = curl_exec($curl);
        $result=json_decode($data, true);
        curl_close($curl);
        return $result;
    }



}