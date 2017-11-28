<?php
namespace app\controllers;

use app\models\ClientsSearch;
use app\models\Comments;
use app\models\CommentsSearch;
use app\models\Clients;

use app\models\shop\OrdersGroupShop;
use app\models\shop\OrdersItemsShop;
use app\models\shop\OrdersShop;

use app\models\fitness\UsersPaysWebFit;
use app\models\fitness\CardsWebFit;

use yii\filters\AccessControl;

use yii\web\Controller;
use Yii;


class ReportsController extends Controller{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['calls','power','today'],
                        'allow' => true,
                        'roles' => ['Manager'],
                    ],
                ],
            ],
        ];
    }

    public function actionCalls(){


        $searchModel = new CommentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('calls', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionPower(){
        $searchModel = new ClientsSearch();
        $dataProvider = $searchModel->searchPower(Yii::$app->request->queryParams);

        return $this->render('power', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionToday(){
        $callex = Clients::find()
            ->select('count(clients.id) as count,call_status_id')
            ->leftJoin('users_clients', '`users_clients`.`client_id` = `clients`.`id`')
            ->andWhere(['>=','`users_clients`.date',date('Y-m-d 00:00:00')])
            ->andWhere(['<=','`users_clients`.date',date('Y-m-d 23:59:59')])
            ->andWhere(['`users_clients`.status'=>1])
            //->andWhere(['NOT IN','clients.call_status_id',[0,1,5,6]])
            ->groupBy('clients.call_status_id')
            ->All();

        $comments = Comments::find()
            ->select('count(id) as count,action_id')
            //->leftJoin('users_clients', '`users_clients`.`client_id` = `clients`.`id`')
            ->andWhere(['>=','date',date('Y-m-d 00:00:00')])
            ->andWhere(['<=','date',date('Y-m-d 23:59:59')])
            ->andWhere(['status'=>1])
            ->andWhere(['<>','action_id',0])
            ->groupBy('action_id')
            ->asArray()
            ->All();

        $orders = OrdersShop::find()
            ->where(['status'=>1])
            ->andWhere(['>=','date',date('Y-m-d 00:00:00')])
            ->andWhere(['<=','date',date('Y-m-d 23:59:59')])->All();

        $data = [
            'Скидка' => 0, //Скидка
            'Расчет бонусами' => 0, //Расчет бонусами
            'Прибыль' => 0, //Прибыль
            'Выручка' => 0, //Выручка
            'Доставка' => 0, //Доставка
            'Выплаты поставщикам' => 0, //Выплаты поставщикам
            'Кэшбэк' => 0, //Кэшбэк
            'Себестоимость' => 0, //Себестоимость
            'Комиссия за товар' => 0, //Комиссия за товар'
            'Отмены' => 0, //Отмены

            'Быстрая доставка' => 0, //Быстрая доставка
            'Спортивные товары' => 0, //Спортивные товары
            'Товары экстримфитнесса' => 0, //Товары экстримфитнесса
            'Другое' => 0, //Другое

            'Количество заказов' => 0, //Количество заказов
            'Полная стоимость' => 0, //Полная стоимость

        ];
        foreach ($orders as $order){
            foreach (OrdersGroupShop::find()->where(['order_id' => $order->id])->All() as $ordersGroup){
                if($ordersGroup->status == 1){
                    $data['Доставка'] += $ordersGroup->delivery_price;
                    foreach (OrdersItemsShop::find()->where(['order_group_id' => $ordersGroup->id])->All() as $ordersItem){
                        if($ordersItem->status == 1){
                            $fullPrice = $ordersItem->price * $ordersItem->count;
                            $discount = $ordersItem->discount * $ordersItem->count;
                            $bonus = $ordersItem->bonus * $ordersItem->count;
                            $paymentsToSuppliers = ($ordersItem->price - $ordersItem->comission)* $ordersItem->count;
                            $cashback = $ordersItem->fee * $ordersItem->count;
                            $commission = ($ordersItem->discount + $ordersItem->comission) * $ordersItem->count;
                            $data['Полная стоимость'] += $fullPrice;
                            $data['Скидка'] += $discount;
                            $data['Расчет бонусами'] += $bonus;
                            $data['Выплаты поставщикам'] += $paymentsToSuppliers;
                            $data['Кэшбэк'] += $cashback;
                            $data['Комиссия за товар'] += $commission;
                            $data['Выручка'] += $fullPrice - $bonus - $discount;
                            $data['Прибыль'] += $fullPrice - ($fullPrice - $commission +  $discount +  $cashback - $discount)  - $bonus - $discount;
                            $data['Себестоимость'] += $fullPrice - $commission +  $discount +  $cashback;
                            if($ordersItem->product->type_id == 1014){
                                $data['Быстрая доставка'] += $fullPrice;
                            }else if($ordersItem->product->type_id == 1011){
                                $data['Товары экстримфитнесса'] += $fullPrice;
                            }else if(in_array($ordersItem->product->type_id,[1009,1012,1010])){
                                $data['Спортивные товары'] += $fullPrice;
                            }else{
                                $data['Другое'] += $fullPrice;
                            }
                        }else if($ordersItem->status == 0){
                            $data['Отмены'] += ($ordersItem->price - $ordersItem->discount - $order->bonus) * $ordersItem->count;
                        }
                    }
                    $data['Выручка'] += $ordersGroup->delivery_price;
                    $data['Прибыль'] += $ordersGroup->delivery_price;
                }
            }
            $data['Количество заказов']++;
        }

        $cards = CardsWebFit::find()->select('cards.card_type_id, cards.price, cards.payment, count(cards.id) as "countNew"')
            ->where(['cards.status'=>1, 'cards.service_field'=>NULL])
            ->leftJoin('users','1=1')
            ->andWhere(['>=','cards.date_creation',date('Y-m-d 00:00:00')])
            ->andWhere(['<=','cards.date_creation',date('Y-m-d 23:59:59')])
            ->andWhere('(((cards.corporative =1 and cards.company_id<>0) or cards.barter=1) and cards.user_id is NULL AND users.id = 4358 ) or (users.id = cards.user_id and  users.status=1 and users.name = "--noname" ) or (users.id = cards.user_id and users.status=1 )')
            ->groupBy('card_type_id')->asArray()->All();


        $totalMoneyWebFit = UsersPaysWebFit::find()
            ->andWhere(['between', 'date', date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')])
            ->andWhere(['status'=>1, 'active'=>1, 'pay_type_id'=>1])
            ->andWhere(['IS NOT','terminal_id',NULL])
            ->sum('money');
        $countTransWebFit = UsersPaysWebFit::find()
            ->andWhere(['between', 'date', date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')])
            ->andWhere(['status'=>1, 'active'=>1, 'pay_type_id'=>1])
            ->andWhere(['IS NOT','terminal_id',NULL])
            ->count('id');

        $this->layout = 'clean';

        return $this->render('today', [
            'callex' => $callex,
            'commetns' => $comments,
            'orders' => $data,
            'cards' => $cards,
            'totalMoneyWebFit' => $totalMoneyWebFit,
            'countTransWebFit' => $countTransWebFit,

        ]);
    }
}