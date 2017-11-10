<?php
/**
 * User: Parvez
 * Date: 11/9/2017
 * Time: 5:04 PM
 */
include_once __DIR__ . "/../../layout/header.php";

$query = new \Symfony\Component\HttpFoundation\ParameterBag($_GET);

if (!$id = $query->get('id')) {
    (new \Symfony\Component\HttpFoundation\RedirectResponse('campaign.php'))->send();
}

$excelSms = \DbModel\Model::find("excel_sms", $id);

$dataFields = $excelSms->oneToMany('excel_sms_data', 'id');

$data = "";
foreach ($dataFields as $item) {
    $data .= $item->data;
}

$csv = \League\Csv\Reader::createFromString($data);
$sheet = (new \League\Csv\Statement())
    ->offset(1)
    ->process($csv);

$header = (new \League\Csv\Statement())
    ->limit(1)
    ->process($csv)
    ->jsonSerialize()[0];

$numberColumnKey = array_search($excelSms->number, $header);

$SMSs = []; //Contains the generated SMSs

$columnCount = count($header);

foreach ($sheet as $row) {
    $sms = $excelSms->body;

    foreach ($row as $index => $value) {
        $sms = str_replace("{{{$header[$index]}}}", $value, $sms);
    }

    $SMSs[$row[$numberColumnKey]] = $sms;
}

//dump($SMSs);
?>

<h2>
    Excel SMS Report
    <small> : <?= \DbModel\Model::find('campaign', $id)->campaign_name ?></small>
</h2>
<hr>
<div class="panel panel-body panel-primary">
<table class="table" id="">
    <thead>
    <tr>
        <th>Number</th>
        <th>SMS</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($SMSs as $number => $SMS): ?>
        <tr>
            <td><?= $number ?></td>
            <td><?= $SMS ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<?php
include_once __DIR__ . "/../../layout/footer.php"; ?>
