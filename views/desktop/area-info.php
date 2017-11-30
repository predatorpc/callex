<?php
/**
 * Created by PhpStorm.
 * User: mono-pc
 * Date: 29.11.2017
 * Time: 15:35
 */


?>
<div class="table-scroll" style="max-height: 300px;">
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th class="text-center">№</th>
            <th class="text-center">Интересовался</th>
        </thead>

        <tbody>
        <?php if(!empty($model)):
            $i = 1;
            ?>
            <?php foreach($model as $item): ?>
            <tr class="text-center">
                <td><?=$i++?></td>
                <td><b><?=date('d.m.Y H:i', strtotime($item['date_creation']))?></b> - <b><?=date('d.m.Y H:i', strtotime($item['date_disable']))?></b></td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7" class="text-warning"><b>Нет записей</b></td></tr>
        <?php endif; ?>
        </tbody>

    </table>
</div>
