<?php
/**
 * Created by PhpStorm.
 * User: Parvez
 * Date: 10/3/2017
 * Time: 11:38 PM
 */

include_once __DIR__ . "/../../layout/header.php";

$query = new \Symfony\Component\HttpFoundation\ParameterBag($_GET);
?>
<link rel="stylesheet" href="./../../css/datatables.min.css">

<h1>SMS Report</h1>
<br>

<form class="form-horizontal" action="sms.php" method="get">
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

    <div class="form-group col-md-6">
        <label for="search" class="col-md-2 control-label">Search</label>
        <div class="col-md-10">
            <input type="text" name="search" id="search" placeholder="Search Keyword"
                   class="control-sm form-control" value="<?= $query->get('search')?? "" ?>">
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

if ($query->has('search')) {
    if (strlen($search = $query->get('search')))
        $conditions[] = "body like '%" . $search . "%'";
}

$campaigns = \DbModel\Model::raw(
        "SELECT * FROM single_sms WHERE " . implode(" AND ", $conditions)
);
?>
<br>
<div class="panel panel-primary panel-body">
    <table class="table" id="campaigns">
        <thead>
        <tr>
            <th>SMS Body</th>
            <th>Total Entry</th>
            <th>Total SMS</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($campaigns as $campaign): ?>
            <tr>
                <td><?= $campaign->body ?></td>
                <td><?= $campaign->entry_count ?></td>
                <td><?= $campaign->sms_count ?></td>
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
