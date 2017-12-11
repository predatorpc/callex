<?php

namespace app\commands;

use app\models\AutoCall;
use app\models\Clients;
use yii\console\Controller;
use yii\console\Exception;


class CallexController extends Controller
{
    /**
     * @return string
     */

    public function actionClients(){
        //для проверки уникальных клиентов из фитнеса в колцентре
        $i=0;
        $double=0;
        $doubleClients=0;
        $fixPhone=0;
        $clients = Clients::find()->select('clients.id, clients.phone')
            ->leftJoin('clients cc', 'cc.phone=clients.phone and cc.status=1')
            ->where(['clients.status'=>1])
            ->groupBy('clients.phone')
            ->having('count(cc.id)>1')->asArray()->all();

        if(!empty($clients)){

            foreach ($clients as $client){
                echo $client['id'].' '.$client['phone'];
                $clientDoubles = Clients::find()
                    ->where(['status'=>1])
                    ->andWhere(['phone'=>$client['phone']])
                    ->andWhere(['!=', 'id', $client['id']])
                    ->all();
                if(!empty($clientDoubles)){
                    $doubleClients++; echo ' doubles: ';
                    foreach ($clientDoubles as $clientDouble){
                        echo '#'.$clientDouble->id. ' ';
                        $clientDouble->status=-99;
                        if($clientDouble->save(true)){
                            $double++;
                        }
                    }
                }
                echo "\n";
            }
        }

        echo "найдено дублей у абонентов: ".$doubleClients."\nУдалено дублей".$double."\nИсправлено номеров: ".$fixPhone;
    }

    public function actionAutoCall(){
        $flagConncet = false;
        try{
            $autoCall = new AutoCall();
            echo "connected\n";
            $flagConncet = true;
        }
        catch (Exception $e){
            $flagConncet =false;
        }
        if($flagConncet){
            try {

                //
                if($autoCall->createCardToCall('9237042936')){
                    echo "file created and moved success\n";
                }
            }
            catch (\Exception $e){
                echo "cannot create file on remote server\n";
            }

        }

        //$autoCall->createCardToCall('+79529257146');
        //$autoCall->createCardToCall('89137730726');
    }

}