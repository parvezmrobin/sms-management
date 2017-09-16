<?php
/**
 * Created by PhpStorm.
 * User: Parvez
 * Date: 9/17/2017
 * Time: 12:55 AM
 */

include_once __DIR__ . "/../../layout/header.php";
?>

    <h1>Campaign Report</h1>
    <br>
    <div class="form-group col-md-6" style="padding-left: 0;">
        <label for="date-from">From</label>
        <input type="date" name="date-from" id="date-from" class="control-sm form-control">
    </div>

    <div class="form-group col-md-6">
        <label for="date-to">To</label>
        <input type="date" name="date-to" id="date-to" class="control-sm form-control">
    </div>

    <div class="form-group">
        <label for="search">Search Keyword</label>
        <input type="text" name="searhc" id="search" class="control-sm form-control">
    </div>
    <div class="form-group">
        <button class="btn btn-info" type="button">Search</button>
    </div>
<?php
include_once __DIR__ . "/../../layout/footer.php"; ?>