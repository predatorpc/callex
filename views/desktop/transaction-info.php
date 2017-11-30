<?php
/**
 * Created by PhpStorm.
 * User: mono-pc
 * Date: 29.11.2017
 * Time: 14:39
 */
?>

<div class="table-scroll" style="max-height: 300px;">
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th class="text-center">№</th>
            <th class="text-center">Дата</th>
            <th class="text-center">Метод</th>
            <th class="text-center">Тип</th>
            <th class="text-center">Баланс</th>
        </tr>
        </thead>

        <tbody>
        <?php if(!empty($data['data']['card']['pays'])):
            $i = 1;
            ?>
            <?php foreach($data['data']['card']['pays'] as $transaction): ?>
            <tr class="text-center">
                <td><?=$i++?></td>
                <td><?=$transaction['date']?></td>
                <td><?=$transaction['method']?></td>
                <td><?=$transaction['type']?></td>
                <td><?=$transaction['money']?></td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7" class="text-warning"><b>Нет записей</b></td></tr>
        <?php endif; ?>
        </tbody>

    </table>
</div>
