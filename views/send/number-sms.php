<?php
/**
 * User: Parvez
 * Date: 10/22/2017
 * Time: 11:54 PM
 */
include_once __DIR__ . "/../../layout/header.php";
?>
    <h2>Send SMS to Numbers</h2>
    <br>

    <div id="sms-output">

    </div>

    <div id="number-sms">
        <br>
        <div class="form-group">
            <label for="text" class="control-label">Enter SMS</label>
            <textarea name="text" id="text" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="numbers" class="control-label">Enter Mobile Numbers</label>
            <textarea name="numbers" id="numbers"
                      class="form-control" placeholder="Space Separated"></textarea>
        </div>
        <div class="form-group">
            <label for="mask" class="control-label">Mask Option</label>
            <select disabled name="mask" id="mask" class="form-control control-sm">
            </select>
        </div>
        <div class="form-group">
            <button type="button" disabled class="btn btn-info">Preview</button>
            <button type="button" id="btnSend" class="btn btn-success" onclick="sendSms()">Send</button>
        </div>
    </div>

    <script src="/js/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['authorization'] = 'Basic Ym9kaXV6emFtYW46SVBkcGc0';
        axios.defaults.headers.common['content-type'] = 'application/json';
        axios.defaults.headers.common['accept'] = 'application/json';
        const baseUrl = 'http://107.20.199.106/restapi/sms/1/text/single';

        function sendSms() {
            $('#btnSend').prop('disabled', true).text('Sending...');
            const text = $('#text').val();
            const numbers = $('#numbers').val().split(' ');
            const mask = $('#mask').val();

            for (let i = 0; i < numbers.length; i++) {
                if (numbers[i].startsWith('01'))
                    numbers[i] = '88' + numbers[i];
            }

            const smsBody = JSON.stringify({
                from: mask,
                to: numbers,
                text: text
            });

            axios.post(baseUrl, smsBody)
                .then(resp => {
                    let total = resp.data.messages.length;
                    let sent = 0;
                    let smsCount = 0;

                    for (let i = 0; i < total; i++) {
                        const message = resp.data.messages[i];
                        const grp = message.status.groupId;
                        if (grp == 0 || grp == 1 || grp == 3) {
                            sent++;
                            smsCount += message.smsCount;
                        }
                    }

                    /**
                     * Store sending info
                     */
                    let report = {
                        user_id: '<?= \App\Auth::userId($session) ?>',
                        entry_count: total,
                        sms_count: smsCount,
                        body: text
                    };

                    report = JSON.stringify(report);
                    axios.post('ajax/number.php', report)
                        .then(resp => {
                            if (!resp.data.ok)
                                console.log(resp.errors);
                        });

                    const output = sent + ' SMSs sent out of ' + total;
                    $('#sms-output').html(
                        '<div class="alert alert-info alert-dismissable">' +
                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + output +
                        '</div>'
                    );
                    $('#btnSend').prop('disabled', false).text('Send');
                })
        }
    </script>

<?php include_once __DIR__ . "/../../layout/footer.php"; ?>