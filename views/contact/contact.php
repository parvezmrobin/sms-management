<?php
/**
 * User: Parvez
 * Date: 10/18/2017
 * Time: 1:02 AM
 */

include_once __DIR__ . "/../../layout/header.php"; ?>
<?php
use DbModel\Model;

$groups = Model::where('groups', "user_id = " . $session->get('user-id'));
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <span class="h2">Create Contact</span>
    </div>

    <form action="index.php" method="post" class="panel-body form-horizontal">
        <div class="form-group">
            <label for="name" class="control-label col-md-4">Contact Name</label>
            <div class="col-md-8">
                <input type="text" name="name" id="name" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="number" class="control-label col-md-4">Contact Number</label>
            <div class="col-md-8">
                <input type="text" name="number" id="number" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="group" class="control-label col-md-4">Group Name</label>
            <div class="col-md-8">
                <select name="group" id="group" class="form-control">
                    <option value="0">None</option>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?= $group->id ?>"><?= $group->group_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <input type="submit" name="create-contact" class="btn bnt-success">
            </div>
        </div>
    </form>
</div>

<?php include_once __DIR__ . "/../../layout/footer.php"; ?>
