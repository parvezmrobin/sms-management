<?php
if (!isset($_REQUEST['id'])) {
    header("Location: /views/sendsms.php");
}
?>

<?php include_once __DIR__ . "/../../layout/header.php"; ?>
<?php $group = \DbModel\Model::find('groups', $_REQUEST['id']) ?>
    <div class="panel panel-info">
        <div class="panel-heading text-center">
            <span class="h2">Group Edit</span>
        </div>
        <form action="index.php?group-id=<?= $_REQUEST['id'] ?>" method="post" class="panel-body form-horizontal">
            <div class="form-group">
                <label for="name" class="control-label col-md-4">Group Name</label>
                <div class="col-md-8">
                    <input type="text" name="name" id="name" class="form-control"
                    value="<?= $group->group_name ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    <input type="submit" name="group-edit" id="group-edit" class="btn btn-info"
                    value="Edit">
                </div>
            </div>
        </form>
    </div>
<?php include_once __DIR__ . "/../../layout/footer.php"; ?>