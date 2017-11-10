<?php
/**
 * User: Parvez
 * Date: 11/9/2017
 * Time: 4:16 PM
 */
include_once __DIR__ . "/../../layout/header.php";

$query = new \Symfony\Component\HttpFoundation\ParameterBag($_GET);

if (!$id = $query->get('id')) {
    (new \Symfony\Component\HttpFoundation\RedirectResponse('campaign.php'))->send();
}

$groups = \DbModel\Model::raw(
    "SELECT group_sms_id, body, group_id, group_name FROM group_sms INNER JOIN group_sms_groups ON group_sms.id = group_sms_groups.group_sms_id INNER JOIN groups ON group_sms_groups.group_id = groups.id WHERE campaign_id = " . 1
);
$groups = $groups->groupBy('group_sms_id');
?>

<h2>Group SMS Report</h2>
<div class="panel panel-body panel-primary">
<table class="table" id="report">
    <thead>
    <tr>
        <th>SMS Body</th>
        <th>Groups</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($groups as $group): ?>
        <tr>
            <td><?= $group->getIterator()->current()->body ?></td>
            <td>
                <ul>
                    <?php foreach ($group as $item): ?>
                        <li>
                            <a href="./../contact/index.php?group=<?= $item->group_id ?>">
                                <?= $item->group_name ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php
include_once __DIR__ . "/../../layout/footer.php"; ?>
