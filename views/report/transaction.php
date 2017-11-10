<?php
/**
 * User: Parvez
 * Date: 11/9/2017
 * Time: 10:30 PM
 */
include_once __DIR__ . "/../../layout/header.php";
$query = new \Symfony\Component\HttpFoundation\ParameterBag($_GET);
?>
    <link rel="stylesheet" href="./../../css/datatables.min.css">
    <h1>Campaign Report</h1>
    <br>

    <form class="form-horizontal" action="transaction.php" method="get">
        <div class="form-group col-md-6">
            <label for="date-from" class="col-md-2 control-label">From</label>
            <div class="col-md-10">
                <input type="date" name="date-from" id="date-from" class="control-sm form-control"
                       value="<?= $query->get('date-from')?? "" ?>">
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="date-to" class="col-md-2 control-label">To</label>
            <div class="col-md-10">
                <input type="date" name="date-to" id="date-to" class="control-sm form-control"
                       value="<?= $query->get('date-to')?? "" ?>">
            </div>
        </div>

        <div class="clearfix">

        </div>
        <div class="form-group col-md-6">
            <div class="col-md-10 col-md-offset-2">
                <button class="btn btn-info" type="submit">Search</button>
            </div>
        </div>
        <div class="clearfix">

        </div>
    </form>
<?php
$conditions = ['user_id = ' . \App\Auth::userId($session)];

if ($query->has('date-from')) {
    if (strlen($from = $query->get('date-from')))
        $conditions[] = "time >= '" . $from . "'";
}

if ($query->has('date-to')) {
    if (strlen($to = $query->get('date-to')))
        $conditions[] = "time <= '" . $to . "'";
}

$smsCost = \App\Config::from('app')->get('smsCost');

$transactions = \DbModel\Model::raw(
    "(SELECT campaign.time, campaign.campaign_name as title, (campaign.sms_count * " . $smsCost . ") as amount, concat(1, campaign.campaignable_type) as type 
FROM campaign WHERE " . implode(" AND ", $conditions) . ")
UNION
(SELECT single_sms.time, body as title, (single_sms.sms_count * " . $smsCost . ") as amount, '3' as type FROM single_sms WHERE " . implode(" AND ", $conditions) . ")
        UNION
(SELECT recharges.time, 'Recharge' as title, recharges.amount, '2' as type FROM recharges WHERE " . implode(" AND ", $conditions) . ")
        ORDER BY time"
);
?>
    <style>
        .table > thead > tr > th {
            text-align: center;
        }
    </style>
    <div class="panel panel-primary panel-body">
        <table class="table" id="campaigns">
            <thead class="text-primary">
            <tr>
                <th>Title</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Time</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr <?= !strcasecmp($transaction->type, '2') ?
                    'class="bg-info" style="text-align:right"' : 'class="bg-warning"' ?>>
                    <td>
                        <?= $transaction->title ?>
                    </td>
                    <td>৳ <?= $transaction->amount ?></td>
                    <td><?= !strcasecmp($transaction->type, '10') ? "Excel" :
                            (!strcasecmp($transaction->type, '11') ? "Group" :
                                (!strcasecmp($transaction->type, '2') ? "Recharge" : "Single SMS")) ?></td>
                    <td><?= Carbon\Carbon::parse($transaction->time)->diffForHumans() ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="/js/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#campaigns').DataTable();
        })
    </script>
<?php
include_once __DIR__ . "/../../layout/footer.php"; ?>