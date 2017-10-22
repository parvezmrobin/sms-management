<?php include_once __DIR__ . "/../../layout/header.php"; ?>
<link rel="stylesheet" href="../../css/datatables.min.css">
<?php
use DbModel\Model;

$createGroup = function ($groupName) use ($session) {
    Model::$database = \App\Config::from('db')->get('database');

    (new Model())->set('user_id', $session->get('user-id'))
        ->set('group_name', $groupName)
        ->save('groups');
};

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
if ($request->query->has('new-group')) {
    $createGroup($request->query->get('new-group'));
}
if ($request->request->has('group-edit')) {
    Model::createFromArray([
        'id' => $request->get('group-id'),
        'group_name' => $request->get('name')
    ])->updateById('groups');
}

if ($request->query->has('delete-id')) {
    Model::createFromArray(['id' => $request->get('delete-id')])
        ->deleteById('groups');
}

$groups = Model::where(
    'groups LEFT JOIN contact_group ON group_id = groups.id',
    'user_id = ' . $session->get('user-id'),
    ['groups.id', 'groups.group_name', 'COUNT(contact_id) as no_of_contact'],
    'GROUP BY groups.id, groups.group_name'
);

?>


<div class="panel panel-primary">
    <div class="panel-heading">
        <span class="h2">Contact Group</span>
    </div>
    <form action="/views/group/index.php" method="get" class="panel-body form-horizontal">
        <div class="form-group">
            <label for="new-group" class="control-label col-md-2">Group Name</label>
            <div class="col-md-6">
                <input type="text" name="new-group" id="new-group" class="form-control">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-success pull-right">Create</button>
            </div>
        </div>
    </form>
</div>

<table class="table" id="data-table">
    <thead>
    <tr>
        <th>Group Name</th>
        <th>No of Contacts</th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($groups as $group): ?>
        <tr>
            <td><?= $group->group_name ?></td>
            <td><?= $group->no_of_contact ?></td>
            <td>
                <a href="edit.php?id=<?= $group->id ?>" type="button" class="btn btn-info">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
            </td>
            <td>
                <button onclick="confirmDelete(<?= $group->id ?>)" type="button" class="btn btn-danger">
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script src="../../js/datatables.min.js"></script>

<script type="application/javascript">
    $(document).ready(function () {
        $("#data-table").DataTable();
    });

    function confirmDelete(id) {
        if (confirm("Are you sure") == true){
            open("index.php?delete-id=" + id, '_self')
        }
    }
</script>

<?php include_once __DIR__ . "/../../layout/footer.php"; ?>
