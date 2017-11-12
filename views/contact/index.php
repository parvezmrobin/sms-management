<?php
/**
 * User: Parvez
 * Date: 10/18/2017
 * Time: 1:16 AM
 */

include_once __DIR__ . "/../../layout/header.php"; ?>
<link rel="stylesheet" href="./../../css/datatables.min.css">
<?php
use DbModel\Model;

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
if ($request->request->has('create-contact')) {
    $contactId = (new Model())->set('contact_name', $request->get('name'))
        ->set('contact', $request->get('number'))
        ->store('contacts');

    Model::createFromArray([
        'name' => $request->get('name'),
        'user_id' => $session->get('user-id'),
        'contact_id' => $contactId
    ])->store('contact_user');

    if ($request->request->has('group') && $request->get('group') != 0) {
        Model::createFromArray([
            'contact_id' => $contactId,
            'group_id' => $request->get('group')
        ])->store('contact_group');
    }
}

if ($request->request->has('contact-edit')) {
    Model::createFromArray([
        'id' => $request->get('edit-id'),
        'contact_name' => $request->get('name'),
        'contact' => $request->get('number')
    ])->updateById('contacts');
}

if ($request->query->has('delete-id')) {
    Model::createFromArray(['id' => $request->get('delete-id')])
        ->deleteById('contacts');
}

$groups = Model::where('groups', "user_id = {$session->get('user-id')}");
if ($request->query->has('group')) {
    $group = $request->get('group');
    if (!strcasecmp($group, "0")) {
        $contacts = Model::where('contacts INNER JOIN contact_user ON contacts.id = contact_user.contact_id',
            "user_id = '" . \App\Auth::userId($session) . "'",
            "DISTINCT contacts.*");
    } else {
        $contacts = Model::where(
            "contacts INNER JOIN contact_group ON contacts.id = contact_group.contact_id " .
            "INNER JOIN contact_user ON contacts.id = contact_user.contact_id",
            "group_id = '{$group}' AND user_id = '" . \App\Auth::userId($session) . "'",
            "DISTINCT contacts.*"
        );
    }
    $g = $group;
} else {
    $contacts = Model::where('contacts INNER JOIN contact_user ON contacts.id = contact_user.contact_id',
        "user_id = '" . \App\Auth::userId($session) . "'",
        "DISTINCT contacts.*");
    $g = "";
}
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <span class="h2">Contacts</span>
    </div>

    <div class="panel-body">
        <form action="index.php" method="get" class="form-horizontal">
            <div class="form-group">
                <label for="group" class="control-label col-md-2">Group</label>
                <div class="col-md-8">
                    <select name="group" id="group" class="form-control">
                        <option value="0">None</option>
                        <?php foreach ($groups as $group): ?>
                            <?php $isSelected = !strcasecmp($g, $group->id) ?>
                            <option value="<?= $group->id ?>" <?= $isSelected ? "selected" : "" ?> >
                                <?= $group->group_name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="submit" value="Load" class="btn btn-info">
                </div>
            </div>
        </form>
        <hr>
        <table class="table" id="contacts">
            <thead>
            <tr>
                <th>Name</th>
                <th>Number</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td><?= $contact->contact_name ?></td>
                    <td><?= $contact->contact ?></td>
                    <td>
                        <a href="edit.php?id=<?= $contact->id ?>" type="button"
                           class="btn btn-info">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                    </td>
                    <td>
                        <button onclick="confirmDelete(<?= $contact->id ?>)" type="button"
                                class="btn btn-danger">
                            <span class="glyphicon glyphicon-minus"></span>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="./../../js/datatables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#contacts').DataTable();
    });

    function confirmDelete(id) {
        if (confirm("Are you sure") == true) {
            open("index.php?delete-id=" + id, '_self')
        }
    }
</script>

<?php include_once __DIR__ . "/../../layout/footer.php"; ?>
