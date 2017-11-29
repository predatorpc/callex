<?php
namespace app\components\desktop;
use app\models\fitness\FitnessInfo;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

class WFitnessInfo  extends Widget
{
    public $client;

    public function run()
    {
        if(empty($this->client)) return false;
        //$this->client->phone
        $webFitness = new FitnessInfo(array('phone'=> $this->client->phone));
        $data = $webFitness->getUserInfo();
        if(empty($data['status'])) return 'Нет подключение к API';

        $gender = ($data['data']['user']['gender'] === 1 ? 'М' : ($data['data']['user']['gender'] === 2 ? 'Ж' : 'Безполый :)'));
        $status = (!empty($data['data']['user']['status']) ? 'Активная' : 'Не Активная');
        $email = (!empty($data['data']['user']['email']) ? $data['data']['user']['email'] : 'Нет');

        ?>
        <div class="table table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ф.И.О</th>
                        <th>Пол</th>
                        <th>День рождения</th>
                        <th>E-mail</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                   <tr>
                       <td><b><?= $data['data']['user']['second_name']?> <?= $data['data']['user']['first_name']?> <?= $data['data']['user']['last_name']?></b></td>
                       <td><?=$gender?></td>
                       <td><?=date('d.m.Y',strtotime($data['data']['user']['birthday']))?></td>
                       <td><?=$email?></td>
                       <td><b class="<?=$status ? 'text-success' : 'text-danger'?>"><?=$status?></b></td>
                   </tr>
                   <tr><td colspan="5"></td></tr>
                </tbody>
            </table>
            <div class="table-scroll">
               <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th class="text-center">№</th>
                    <th class="text-center">Номер карты</th>
                    <th class="text-center">Карта</th>
                    <th class="text-center">Описание</th>
                    <th class="text-center">Срок действия</th>
                    <th class="text-center">Баланс</th>
                    <th class="text-center">Статус</th>
                </tr>
                </thead>

                <tbody>
                <?php if(!empty($data['data']['user']['cards'])):
                    $i = 1;
                    ?>
                    <?php foreach($data['data']['user']['cards'] as $card): ?>
                    <tr class="text-center">
                        <td><?=$i++?></td>
                        <td><?=$card['card_id']?></td>
                        <td>-</td>
                        <td><?=$card['cardTypeName']?></td>
                        <td><?=$card['created_at']?> - <?=$card['expires_at']?></td>
                        <td><?=$card['balance']?></td>
                        <td><?=(!empty($card['status']) ? '<b class="text-success">Активная</b>' : '<b class="text-danger">Не активная</b>')?>   </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-warning"><b>Упс а карты нет?</b></td></tr>
                <?php endif; ?>
                </tbody>

            </table>
            </div>
        </div>

     <?php
    }

}