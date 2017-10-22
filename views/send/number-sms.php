<?php
/**
 * User: Parvez
 * Date: 10/22/2017
 * Time: 11:54 PM
 */
include_once __DIR__ . "/../../layout/header.php";
?>
    <h1>Send SMS to Numbers</h1>
    <br>

    <div id="number-sms">
        <br>
        <div class="form-group">
            <label for="text1" class="control-label">Enter SMS</label>
            <textarea name="text1" id="text1" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="numbers" class="control-label">Enter Mobile Numbers</label>
            <textarea name="numbers" id="numbers"
                      class="form-control" placeholder="Space Separated"></textarea>
        </div>
        <div class="form-group">
            <label for="mask1" class="control-label">Mask Option</label>
            <input type="text" name="mask1" id="mask1" class="form-control control-sm">
        </div>
        <div class="form-group">
            <label for="name1" class="control-label">Campaign Name</label>
            <input type="text" name="name1" id="name1" class="form-control control-sm">
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
            const text = $('#text1').val();
            const numbers = $('#numbers').val().split(' ');
            const mask = $('#mask1').val();

            for (let i = 0; i < numbers.length; i++) {
                if(numbers[i].startsWith('01'))
                    numbers[i] = '88' + numbers[i];
            }

            const smsBody = JSON.stringify({
                from: mask,
                to: numbers,
                text: text
            });

            axios.post(baseUrl, smsBody)
                .then(resp=>{
                    console.log(resp);
                    $('#btnSend').prop('disabled', false).text('Send');
                })
        }
    </script>

<?php include_once __DIR__ . "/../../layout/footer.php"; ?>