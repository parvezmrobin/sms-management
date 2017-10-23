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
        <label for="text" class="control-label">Enter SMS</label>
        <textarea name="text" id="text" class="form-control"></textarea>
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
//        console.log(output);

        return output;
    }

    function showOutput() {
        const output = generateMessages();
        const outDiv = document.getElementById('output');
        let outString = '<table class="table">' +
            '<thead><tr><th>Number</th><th>SMS</th><tr><tbody>';

        let count = 0;
        for (let i in output) {
            if (output.hasOwnProperty(i)) {
                if(count == 10)
                    break;
                let msg = output[i];
                outString += ("<tr><td>" + i + "</td><td>" + msg + '</td></tr>');
                count++;
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
//        console.log(res);


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
        const output = generateMessages();
        const mask = $('#mask1').val();

        let messages = [];

        for (let i in output) {
            if (output.hasOwnProperty(i)) {
                messages.push({
                    from: mask,
                    to: i.startsWith('1')? '880' + i: i,
                    text: output[i]
                })
            }
        }

        const smsBody = JSON.stringify({
            messages: messages
        });

        axios.post(baseUrl, smsBody)
            .then(resp => {
                let total = resp.data.messages.length;
                let sent = 0;

                for (let i = 0; i < total; i++) {
                    const message = resp.data.messages[i];
                    const grp = message.status.groupId;
                    if (grp == 0 || grp == 1 || grp == 3) {
                        sent++;
                    }
                }

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
