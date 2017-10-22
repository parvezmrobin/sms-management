<?php
if (!isset($_REQUEST['id'])) {
    header("Location: /views/sendsms.php");
}
?>

<?php include_once __DIR__ . "/../../layout/header.php"; ?>
<?php $contact = \DbModel\Model::find('contacts', $_REQUEST['id']) ?>
    <div class="panel panel-info">
        <div class="panel-heading text-center">
            <span class="h2">Contact Edit</span>
        </div>
        <form action="index.php?edit-id=<?= $_REQUEST['id'] ?>" method="post" class="panel-body form-horizontal">
            <div class="form-group">
                <label for="name" class="control-label col-md-4">Contact Name</label>
                <div class="col-md-8">
                    <input type="text" name="name" id="name" class="form-control"
                    value="<?= $contact->contact_name ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="number" class="control-label col-md-4">Contact Number</label>
                <div class="col-md-8">
                    <input type="number" name="number" id="number" class="form-control"
                    value="<?= $contact->contact ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    <input type="submit" name="contact-edit" id="contact-edit" class="btn btn-info"
                    value="Edit">
                </div>
            </div>
        </form>
    </div>
<?php include_once __DIR__ . "/../../layout/footer.php"; ?>