
<!-- Nav tabs -->
<div class="tab__com">
    <ul class="nav nav-tabs" id="tab-com">
        <li class="active"><a href="#tab1" data-toggle="tab">Список транзакций</a></li>
        <li><a href="#tab2" data-toggle="tab">Список посещений</a></li>
    </ul
    <div class="tab-content">
        <div class="tab-pane active" id="tab1">
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
        </div>
        <div class="tab-pane" id="tab2">
            <div class="table-scroll" style="max-height: 300px;">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th class="text-center">№</th>
                        <th class="text-center">Клуб</th>
                        <th class="text-center">Дата</th>
                        <th class="text-center">Статус</th>
                        <th class="text-center">Приложение</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if(!empty($data['data']['card']['visits'])):
                        $i = 1;
                        ?>
                        <?php foreach($data['data']['card']['visits'] as $visits): ?>
                        <tr class="text-center">
                            <td><?=$i++?></td>
                            <td><?=$visits['club']?></td>
                            <td><?=$visits['date']?></td>
                            <td><?=($visits['success'] == 1 ? '<b class="text-success">Успех</b>' : '<b class="text-danger">Нет</b>')?></td>
                            <td><?=($visits['application'] == 1 ? '<b class="text-success">Да</b>' : '<b class="text-danger">Нет</b>')?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-warning"><b>Нет записей</b></td></tr>
                    <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>




