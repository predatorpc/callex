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
        $this->clients = Clients::find()->select('clients.*')
            ->from('clients, users_clients')
        ->where(['users_clients.user_id'=>Yii::$app->user->id, 'users_clients.status'=>1,])
        ->andWhere('clients.id = users_clients.client_id')
        ->andWhere(['clients.status'=>1, ])
        ->andWhere(['<>', 'clients.next_call', 0 ])
        ->orderBy('next_call')
            ->groupBy('clients.id')
        ->all();
        /*Clients::find()
            ->leftJoin('users_clients', '`users_clients`.`client_id` = `clients`.`id`')
            ->andWhere(['`users_clients`.`user_id`'=> Yii::$app->user->getId()])
            ->andWhere(['clients.call_status_id'=>2])
            ->andWhere(['<>','clients.next_call','NULL'])
            ->orderBy('clients.next_call')
            ->All();*/

        if ($this->clients === null) {
            $this->clients = [];
        }
    }

    public function run()
    {
        $html =  '<div class="side_bar"><div class="list-group">';
        foreach ($this->clients as $client){
            $timeString = '';
            $seconds = strtotime($client->next_call) - strtotime('now');
            if($seconds > 0){
                $times = $this->seconds2times($seconds);
                if(isset($times[4])){
                    $timeString .= $times[4].' года ';
                }
                if(isset($times[3])){
                    $timeString .= $times[3].' дней ';
                }
                if(isset($times[2])){
                    $timeString .= $times[2].' часов ';
                }
                if(isset($times[1])){
                    $timeString .= $times[1].' минут ';
                }
            }else{
                $timeString .= 'уже должны были';
            }





            $html .= ' <a href="/desktop/client-card?id='.$client->id.'" class="list-group-item">
                       <b class="list-group-item-heading" style="font-size: 16px;">'.$client->first_name.' '.$client->last_name.' '.$client->second_name.'</b>
                       <p class="list-group-item-text">
                         Перезвонить через '.$timeString.'
                       </p>
                   </a>';
        }
        $html .=  '</div></div>';
        echo $html;
    }

    /**
     * Преобразование секунд в секунды/минуты/часы/дни/года
     *
     * @param int $seconds - секунды для преобразования
     *
     * @return array $times:
     *        $times[0] - секунды
     *        $times[1] - минуты
     *        $times[2] - часы
     *        $times[3] - дни
     *        $times[4] - года
     *
     */
    private function seconds2times($seconds)
    {
        $times = array();

        // считать нули в значениях
        $count_zero = false;

        // количество секунд в году не учитывает високосный год
        // поэтому функция считает что в году 365 дней
        // секунд в минуте|часе|сутках|году
        $periods = array(60, 3600, 86400, 31536000);

        for ($i = 3; $i >= 0; $i--)
        {
            $period = floor($seconds/$periods[$i]);
            if (($period > 0) || ($period == 0 && $count_zero))
            {
                $times[$i+1] = $period;
                $seconds -= $period * $periods[$i];

                $count_zero = true;
            }
        }

        $times[0] = $seconds;
        return $times;
    }
}
?>