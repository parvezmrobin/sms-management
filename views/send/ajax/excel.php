<?php
/**
 * User: Parvez
 * Date: 11/8/2017
 * Time: 1:31 PM
 */
include_once __DIR__ . "/../../../index.php";

use DbModel\Model;

$request = json_decode(
    \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getContent()
);

$errorBag = [];
if (isset($request->name)) {
    $name = $request->name;
} else {
    $name = uniqid('campaign_');
}

if (isset($request->user_id)) {
    $userId = $request->user_id;
} else {
    $errorBag[] = 'No user id provided';
}

if (isset($request->entry_count)) {
    $entryCount = $request->entry_count;
} else {
    $errorBag[] = 'No entry count value provided';
}

if (isset($request->sms_count)) {
    $smsCount = $request->sms_count;
} else {
    $errorBag[] = 'No SMS count provided';
}

if (isset($request->body)) {
    $body = $request->body;
} else {
    $errorBag[] = 'No SMS body provided';
}


if (count($errorBag)) {
    die(json_encode([
        'ok' => false,
        'errors' => $errorBag
    ]));
}

$campaignId = Model::createFromArray([
    'campaign_name' => $name,
    'user_id' => $userId,
    'campaignable_type' => 0,
    'entry_count' => $entryCount,
    'sms_count' => $smsCount
])->create('campaign');

$chunks = str_split($body, 250);

foreach ($chunks as $i => $chunk) {
    Model::createFromArray([
        'campaign_id' => $campaignId,
        'serial' => $i + 1,
        'data' => $chunk
    ])->create('excel_sms_data');
}

echo json_encode([
    'ok' => true,
    'campaign_id' => $campaignId,
    'total_chunks' => count($chunks)
]);