<?php
/**
 * Created by PhpStorm.
 * User: Parvez
 * Date: 9/15/2017
 * Time: 8:46 PM
 */
include_once __DIR__ . "/../../layout/header.php";
?>
<h1>Send SMS</h1>
<br>

<div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#home" aria-controls="home" role="tab" data-toggle="tab">
                Number SMS
            </a>
        </li>
        <li role="presentation">
            <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
                Excel SMS
            </a>
        </li>
        <li role="presentation">
            <a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">
                Group SMS
            </a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <!--Number SMS Start-->
        <div role="tabpanel" class="tab-pane active" id="home">
            <br>
            <div class="form-group">
                <label for="text1" class="control-label">Enter SMS</label>
                <textarea name="text1" id="text1" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="numbers" class="control-label">Enter Mobile Numbers</label>
                <textarea name="numbers" id="numbers"
                          class="form-control" placeholder="Comma Separated"></textarea>
            </div>
            <div class="form-group">
                <label for="mask1" class="control-label">Mask Option</label>
                <select name="mask1" id="mask1" class="form-control control-sm"></select>
            </div>
            <div class="form-group">
                <label for="name1" class="control-label">Campaign Name</label>
                <input type="text" name="name1" id="name1" class="form-control control-sm">
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-info">Preview</button>
                <button type="button" class="btn btn-success">Send</button>
            </div>
        </div>
        <!--Number SMS End-->

        <!--Excel SMS Start-->
        <div role="tabpanel" class="tab-pane" id="profile">
            <br>
            <div class="form-group">
                <label for="file">Choose File</label>
                <input type="file" name="file" id="file" class="form-control control-sm">
                <button type="button" class="btn btn-info">Upload</button>
            </div>

            <div class="form-group col-md-6" style="padding-left: 0;">
                <label for="sheet" class="control-label">Sheet Name</label>
                <select name="sheet" id="sheet" class="form-control control-sm"></select>
            </div>

            <div class="form-group col-md-6" style="padding-left: 0;">
                <label for="mob-number" class="control-label">Mobile Number Column</label>
                <select name="mob-number" id="mob-number" class="form-control control-sm"></select>
            </div>

            <div class="form-group">
                <label for="text2" class="control-label">Enter SMS</label>
                <textarea name="text2" id="text2" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="add-col" class="control-label">Add Column</label>
                <select name="add-col" id="add-col" multiple class="form-control control-sm"></select>
            </div>

            <div class="form-group">
                <label for="mask2" class="control-label">Mask Option</label>
                <select name="mask2" id="mask2" class="form-control control-sm"></select>
            </div>
            <div class="form-group">
                <label for="name2" class="control-label">Campaign Name</label>
                <input type="text" name="name2" id="name2" class="form-control control-sm">
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-info" onclick="showOutput()">Preview</button>
                <button type="button" class="btn btn-success">Send</button>
            </div>
        </div>
        <!--Excel SMS End-->

        <!--Group SMS Start-->
        <div role="tabpanel" class="tab-pane" id="messages">
            <br>
            <div class="form-group col-md-5" style="padding-left: 0;">
                <label for="groups" class="control-label">Available Groups</label>
                <select name="groups" id="groups" multiple class="form-control control-sm"></select>
            </div>
            <div class="col-md-2" style="padding-top: 2em;">
                <div class="">
                    <button type="button" class="btn btn-default">&gt;&gt;&gt;</button>
                </div>
                <div class="">
                    <button type="button" class="btn btn-default">&lt;&lt;&lt;</button>
                </div>
            </div>
            <div class="form-group col-md-5">
                <label for="groups-added" class="control-label">Added Groups</label>
                <select name="groups-added" id="groups-added" multiple class="form-control control-sm"></select>
            </div>

            <div class="form-group">
                <label for="text3" class="control-label">Enter SMS</label>
                <textarea name="text3" id="text3" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="mask3" class="control-label">Mask Option</label>
                <select name="mask3" id="mask3" class="form-control control-sm"></select>
            </div>
            <div class="form-group">
                <label for="name3" class="control-label">Campaign Name</label>
                <input type="text" name="name3" id="name3" class="form-control control-sm">
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-info">Preview</button>
                <button type="button" class="btn btn-success">Send</button>
            </div>
        </div>
        <!--Group SMS End-->
    </div>

    <!-- Modal -->
    <div id="preview-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">SMS Preview</h4>
                </div>
                <div id="output" class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

</div>

<script src="/js/xlsx.full.min.js"></script>
<script>
    const X = XLSX;
    let result;

    function generateMessages() {
        const mobColumn = $("#mob-number").val();
        const textArea = document.getElementById('text2');
        const msg = textArea.value;

        let output = [];
        const selectedSheet = document.getElementById('sheet').value;
        const rows = result[selectedSheet];

        for (let i = 0; i < rows.length; i++) {
            let row = rows[i];
            let msg1 = msg;

            for (let key in row) {
                if (row.hasOwnProperty(key)) {
                    let value = row[key];
                    let regexp = new RegExp('{{' + key + '}}', 'g');
                    msg1 = msg1.replace(regexp, value);
                }
            }

            output[row[mobColumn]] = msg1;
        }
        console.log(output);

        return output;
    }

    function showOutput() {
        const output = generateMessages();
        const outDiv = document.getElementById('output');
        let outString = '<table class="table">' +
            '<thead><tr><th>Number</th><th>SMS</th><tr><tbody>';

        for (let i in output) {
            if (output.hasOwnProperty(i)) {
                let msg = output[i];
                outString += ("<tr><td>" + i + "</td><td>" + msg + '</td></tr>');
            }
        }
        outString += "</tbody></table>";

        outDiv.innerHTML = outString;

        $('#preview-modal').modal('toggle');
    }

    function process_wb(workbook) {
        let res = {};
        workbook.SheetNames.forEach(function (sheetName) {
            const roa = X.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
            if (roa.length > 0) {
                res[sheetName] = roa;
            }
        });

        result = res;
        console.log(res);


        let sheetsHtml = '';

        for (let sheetName in res) {
            sheetsHtml += '<option value="' + sheetName + '">' + sheetName + '</option>';
        }

        document.getElementById('sheet').innerHTML = sheetsHtml;
        $('#sheet').change();
    }

    const file = document.getElementById('file');
    function handleFile(e) {
        const f = e.target.files[0];

        const reader = new FileReader();
        reader.onload = function (e) {
            if (typeof console !== 'undefined') console.log('onload', new Date());
            const data = e.target.result;
            let wb;

            wb = X.read(data, {type: 'binary'});

            process_wb(wb);
        };
        reader.readAsBinaryString(f);
    }

    function sheetChanged() {
        $("#text2").val("");

        const sheet = this.value;
        const addColumnSelect = $('#add-col')[0];
        if (sheet === "") {
            addColumnSelect.innerHTML = "";
            return;
        }
        const firstElement = result[sheet][0];

        let columns = "";
        for (let i in firstElement) {
            columns += "<option>" + i + "</option>";
        }

        addColumnSelect.innerHTML = columns;
        $("#mob-number").html(columns)
    }

    $('#sheet').change(sheetChanged).change();

    if (file.addEventListener) file.addEventListener('change', handleFile, false);

    $("#add-col").change(function () {
        const sms = $("#text2")[0];
        sms.value = (sms.value + "{{" + this.value + "}}");
    })
</script>


<?php include_once __DIR__ . "/../../layout/footer.php"; ?>
