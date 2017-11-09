<?php
/**
 * User: Parvez
 * Date: 11/9/2017
 * Time: 8:13 PM
 */
include_once __DIR__ . "/../../../index.php";

use DbModel\Model;

$request = json_decode(
    \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getContent()
);

$errorBag = [];

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

$singleSmsId =  Model::createFromArray([
    'user_id' => $userId,
    'body' => $body,
    'sms_count' => $smsCount,
    'entry_count' => $entryCount,
])->create('single_sms');

echo json_encode([
    'ok' => true,
    'single_sms_id' => $singleSmsId,
]);