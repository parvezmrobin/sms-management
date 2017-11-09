<?php
/**
 * User: Parvez
 * Date: 10/23/2017
 * Time: 1:01 PM
 */
include_once __DIR__ . "/../../layout/header.php"; ?>

<h1>Send SMS from Excel</h1>
<br>
<div id="sms-output">

</div>
<div id="excel">
    <br>
    <div class="form-group">
        <label for="file">Choose File</label>
        <input type="file" name="file" id="file" class="form-control control-sm" accept=".xlsx, .xls">
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
        <label for="text" class="control-label">Enter SMS</label>
        <textarea name="text" id="text" class="form-control"></textarea>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="add-col" class="control-label">Add Column</label>
            <select name="add-col" id="add-col" multiple class="form-control control-sm"></select>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="mask" class="control-label">Mask Option</label>
                <select name="mask" id="mask" class="form-control control-sm"></select>
            </div>
            <div class="form-group">
                <label for="name" class="control-label">Campaign Name</label>
                <input type="text" name="name" id="name" class="form-control control-sm">
            </div>
        </div>
    </div>

    <div class="form-group">
        <button type="button" class="btn btn-info" onclick="showOutput()">Preview</button>
        <button type="button" class="btn btn-success" onclick="sendSms()">Send</button>
    </div>
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

<script src="/js/axios.min.js"></script>
<script src="/js/xlsx.full.min.js"></script>

<script>

    /**
     * Excel Part
     */
    const X = XLSX;
    let jsonSheets, csvSheets, messageFormat;

    function generateMessages(selectedSheet) {
        const mobColumn = $("#mob-number").val();
        const textArea = document.getElementById('text');
        messageFormat = textArea.value;

        let output = [];
        const rows = jsonSheets[selectedSheet];

        for (let i = 0; i < rows.length; i++) {
            let row = rows[i];
            let message = messageFormat;

            for (let key in row) {
                if (row.hasOwnProperty(key)) {
                    let value = row[key];
                    let regexp = new RegExp('{{' + key + '}}', 'g');
                    message = message.replace(regexp, value);
                }
            }

            output[row[mobColumn]] = message;
        }
//        console.log(output);

        return output;
    }

    function showOutput() {
        const selectedSheet = document.getElementById('sheet').value;
        const messages = generateMessages(selectedSheet);
        const outDiv = document.getElementById('output');
        let outString = '<table class="table">' +
            '<thead><tr><th>Number</th><th>SMS</th><tr><tbody>';

        let count = 0;
        for (let i in messages) {
            if (messages.hasOwnProperty(i)) {
                if (count == 10)
                    break;
                let msg = messages[i];
                outString += ("<tr><td>" + i + "</td><td>" + msg + '</td></tr>');
                count++;
            }
        }
        outString += "</tbody></table>";

        outDiv.innerHTML = outString;

        $('#preview-modal').modal('toggle');
    }

    function processWorkBook(workbook) {
        let _jsonSheets = {}, _csvSheets = {};
        workbook.SheetNames.forEach(function (sheetName) {
            const rowObjectArray = X.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
            const csvSheet = XLSX.utils.sheet_to_csv(workbook.Sheets[sheetName]);
            if (rowObjectArray.length > 0) {
                _jsonSheets[sheetName] = rowObjectArray;
                _csvSheets[sheetName] = csvSheet;
            }
        });

        jsonSheets = _jsonSheets;
        csvSheets = _csvSheets;
        console.log(_csvSheets);

//        console.log(res);

        let sheetsHtml = '';

        for (let sheetName in _jsonSheets) {
            sheetsHtml += '<option value="' + sheetName + '">' + sheetName + '</option>';
        }

        document.getElementById('sheet').innerHTML = sheetsHtml;
        $('#sheet').change();
    }

    const file = document.getElementById('file');
    function handleFile(e) {
        const file = e.target.files[0];

        const reader = new FileReader();
        reader.onload = function (e) {
            if (typeof console !== 'undefined') console.log('onload', new Date());
            const data = e.target.result;
            let wb = X.read(data, {type: 'binary'});

            processWorkBook(wb);
        };
        reader.readAsBinaryString(file);
    }

    function sheetChanged() {
        $("#text").val("");

        const sheet = this.value;
        const addColumnSelect = $('#add-col')[0];
        if (sheet === "") {
            addColumnSelect.innerHTML = "";
            return;
        }
        const firstElement = jsonSheets[sheet][0];

        let columns = "";
        for (let i in firstElement) {
            columns += "<option>" + i + "</option>";
        }

        addColumnSelect.innerHTML = columns;
        $("#mob-number").html(columns)
    }

    $('#sheet').change(sheetChanged).change();

    if (file.addEventListener) file.addEventListener('change', handleFile, false);

    $('#add-col').change(function () {
        const column = $(this).val();

        $('#text').first().val(function () {
            const pos = $(this).prop("selectionStart");
            const value = $(this).val();
            return value.substr(0, pos) + "{{" + column + "}}" + value.substr(pos);
        });
    });
    /**
     * Excel Part Ends
     */

    /**
     * Ajax Part
     */
    axios.defaults.headers.common['authorization'] = 'Basic Ym9kaXV6emFtYW46SVBkcGc0';
    axios.defaults.headers.common['content-type'] = 'application/json';
    axios.defaults.headers.common['accept'] = 'application/json';
    const baseUrl = 'http://107.20.199.106/restapi/sms/1/text/multi';

    function sendSms() {
        $('#btnSend').prop('disabled', true).text('Sending...');
        const selectedSheet = document.getElementById('sheet').value;
        const text = csvSheets[selectedSheet];
        const messagesArray = generateMessages(selectedSheet);
        const mask = $('#mask1').val();

        let messagesToBeSent = [];

        for (let i in messagesArray) {
            if (messagesArray.hasOwnProperty(i)) {
                messagesToBeSent.push({
                    from: mask,
                    to: i.startsWith('1') ? '880' + i : i,
                    text: messagesArray[i]
                })
            }
        }

        const smsBody = JSON.stringify({
            messages: messagesToBeSent
        });

        axios.post(baseUrl, smsBody)
            .then(resp => {
                /*Generate Report*/
                let total = resp.data.messages.length;
                let sent = 0, smsCount = 0;

                for (let i = 0; i < total; i++) {
                    const message = resp.data.messages[i];
                    const grp = message.status.groupId;
                    if (grp == 0 || grp == 1 || grp == 3) {
                        sent++;
                        smsCount += message.smsCount;
                    }
                }

                /*Store Report*/
                let report = {
                    user_id: '<?= \App\Auth::userId($session) ?>',
                    entry_count: total,
                    sms_count: smsCount,
                    body: text,
                    format: messageFormat,
                    numberCol: $("#mob-number").val()
                };
                if (name) {
                    report.name = name;
                }

                report = JSON.stringify(report);
                axios.post('ajax/excel.php', report)
                    .then(resp => {
                        if (!resp.data.ok)
                            console.log(resp.errors);
                    });

                /*Show Report*/
                const output = sent + ' SMSs sent out of ' + total;
                $('#sms-output').html(
                    '<div class="alert alert-info alert-dismissable">' +
                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + output +
                    '</div>'
                );
                $('#btnSend').prop('disabled', false).text('Send');
            })
    }
    /**
     * Ajax Part Ends
     */
</script>

<?php include_once __DIR__ . "/../../layout/footer.php"; ?>
