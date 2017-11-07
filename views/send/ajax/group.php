<?php
/**
 * User: Parvez
 * Date: 11/7/2017
 * Time: 7:09 PM
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

if ($request->groups) {
    $groups = $request->groups;
} else {
    $errorBag[] = 'No group provided';
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
    'campaignable_type' => 1,
    'entry_count' => $entryCount,
    'sms_count' => $smsCount
])->create('campaign');

$groupSmsId = Model::createFromArray([
    'campaign_id' => $campaignId,
    'body' => $body
])->create('group_sms');

foreach ($groups as $group) {
    $groupIds[] = Model::createFromArray([
        'group_sms_id' => $groupSmsId,
        'group_id' => $group
    ])->create('group_sms_groups');
}

echo json_encode([
    'ok' => true,
    'campaign_id' => $campaignId,
    'group_id' => $groupSmsId
]);