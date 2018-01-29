<?php

namespace app\commands;

use app\models\AutoCall;
use app\models\Clients;
use app\models\TrackingAutoCall;
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

    public function actionManagerClients(){
        Clients::updateAll(['next_call'=>NULL, 'next_call_by_user'=>NULL], ['AND', ['status'=>1], ['<', 'next_call', Date('Y-m-d 00:00:00', strtotime('-2 day', time()))]]);
        $sqlUpdDelUser = "
        Update `clients`, users
        set clients.next_call = NULL, 
            clients.next_call_by_user = NULL
        WHERE clients.next_call <>0 and 
                clients.status=1 and 
        (
            (
                users.id = clients.next_call_by_user and 
                users.status=0
            ) OR
            (
             clients.next_call_by_user IS NULL
             )
        )
        ";
        $sqlUpdDelByNExtCall = "
        UPDATE `clients`
        SET clients.next_call = NULL,
            clients.next_call_by_user = NULL
        WHERE clients.next_call <>0 and 
            clients.status=1 and 
            clients.next_call < '".Date('Y-m-d 00:00:00', strtotime('-2 day', time()))."'
        ";
        \Yii::$app->db->createCommand($sqlUpdDelUser)->execute();
        //\Yii::$app->db->createCommand($sqlUpdDelByNExtCall)->execute();//равно первой строке

    }

    public function actionAutoCall(){
        $testPhones = [
            '+79237042936',
            //'+79994636006',
            //'+79529257146',
            //'+79137730726',
            //'+79137778236'

        ];
        $flagConncet = false;
        try{
            $autoCall = new AutoCall(['ssh'=>false, 'extension'=>1000]);
            echo "connected\n";
            $flagConncet = true;
        }
        catch (Exception $e){
            $flagConncet =false;
        }
        if($flagConncet){
            try {
                foreach ($testPhones as $testPhone){
                    echo "\n*********************\n";
                    echo 'phone:'.$testPhone;
                    if($autoCall->createCardToCall($testPhone)){
                        echo "file created and moved success\n";
                    }
                }
            }
            catch (\Exception $e){
                echo "cannot create file on remote server\n";
            }

        }
    }

    public function actionFileCallTracking(){
        $str = 'Channel: Local/89658285276@from-internal
Callerid: 1000
MaxRetries: 5
RetryTime: 300
WaitTime: 20
Context: test-sound
Extension: 1000
Priority: 1
Archive: yes
#phone89237042936#date2017-12-12';
        //$matches='';
        //preg_match('/(?P<phone>(#phone[\d]{11})):(?P<date>(#date[\d-]{10}))/',  $str, $matches);
        preg_match('/(?P<name_phone>(#phone))(?P<phone>([\d]{11}))(?P<name_date>(#date))(?P<date>([\d-]{10}))/',  $str, $matches);
        print_r($matches);die();
        // собственнно цель
        // отслеживать количество файлов в папку mnt/acter и после того как там будет меньше 2 переносить след файл
        // так же если есть файлы в
        $t = new TrackingAutoCall();
        $t->startTracking();


    }

}