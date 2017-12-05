<?php

namespace app\commands;

use app\models\Clients;
use yii\console\Controller;


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
        while ($i<200000){
            $clientsAll = Clients::find()->where(['>','id',$i])->andWhere(['<=','id',($i+1000)])->andWhere(['status'=>1]);
            foreach ($clientsAll->each() as $client) {
                //приветси все номера в порядок
                echo $client->phone .' -> ';
                $client->phone = '+7'.preg_replace('/(\+78)|(\+7)|(|)|-|\+1/', '', $client->phone);
                echo $client->phone ."\n";
                //поиск дублей
                if($client->save(true)){
                    $fixPhone++;
                }
                $clientDoubles = Clients::find()
                    ->where(['status'=>1])
                    ->andWhere(['like','phone', preg_replace('/(\+7)|(|)|-/', '', $client->phone)])
                    ->andWhere(['!=', 'id', $client->id])
                    ->all();
                if(!empty($clientDoubles)){
                    $doubleClients++;
                    foreach ($clientDoubles as $clientDouble){
                        $clientDouble->status=-99;
                        if($clientDouble->save(true)){
                            $double++;
                        }
                    }
                }
                unset($clientDoubles);
            }
            unset($clientsAll);
            $i+=1000;
        }
        echo "найдено дублей у абонентов: ".$doubleClients."\nУдалено дублей".$double."\nИсправлено номеров: ".$fixPhone;
    }

}