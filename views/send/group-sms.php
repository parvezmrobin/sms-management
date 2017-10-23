<?php
/**
 * User: Parvez
 * Date: 10/23/2017
 * Time: 9:17 AM
 */
include_once __DIR__ . "/../../layout/header.php"; ?>

    <script src="/js/axios.min.js"></script>

<?php
$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

if ($request->request->has('send')) {
    $groups = $request->request->get('groups-added');
    $groups = \DbModel\Model::where('groups', 'id IN (' . implode(',', $groups) . ')');
    $contacts = $groups->manyToMany('contacts', 'contact_group', 'group_id', 'contact_id');
    ?>
    <div class="alert alert-info alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        SMS sent to groups: <?= implode(', ', $groups->only('group_name')) ?>
        <p class="text-warning" id="sms-output"></p>
    </div>
    <script>
        axios.defaults.headers.common['authorization'] = 'Basic Ym9kaXV6emFtYW46SVBkcGc0';
        axios.defaults.headers.common['content-type'] = 'application/json';
        axios.defaults.headers.common['accept'] = 'application/json';
        const baseUrl = 'http://107.20.199.106/restapi/sms/1/text/single';

        $('#btnSend').prop('disabled', true).text('Sending...');
        const text = '<?= $request->get('text') ?>';
        const numbers = <?= json_encode($contacts->only('contact')) ?>;
        const mask = '<?= $request->get('mask') ?>';

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

                for (let i = 0; i < total; i++) {
                    const message = resp.data.messages[i];
                    const grp = message.status.groupId;
                    if(grp == 0 || grp == 1 || grp == 3){
                        sent++;
                    }
                }

                $('#sms-output').text(sent + ' SMSs sent out of ' + total);

                $('#btnSend').prop('disabled', false).text('Send');
            })
    </script>
    <?php
}

$groups = \DbModel\Model::all('groups');
?>
    <h1>Send SMS to Groups</h1>
    <br>

    <form method="post" action="group-sms.php" class="form-horizontal">
        <br>
        <div class="form-group col-md-5" style="padding-left: 0;">
            <label for="groups" class="control-label">Available Groups</label>
            <select name="groups" id="groups" multiple class="form-control control-sm">
                <?php foreach ($groups as $group): ?>
                    <option value="<?= $group->id ?>"><?= $group->group_name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2" style="padding-top: 2em;">
            <div class="">
                <button type="button" class="btn btn-default" onclick="addGroup()">&gt;&gt;&gt;</button>
            </div>
            <div class="">
                <button type="button" class="btn btn-default" onclick="removeGroup()">&lt;&lt;&lt;</button>
            </div>
        </div>

        <div class="form-group col-md-5">
            <label for="groups-added" class="control-label">Added Groups</label>
            <select name="groups-added[]" id="groups-added"
                    multiple required class="form-control control-sm"></select>
        </div>
        <div class="clearfix"></div>
        <div class="form-group">
            <label for="text" class="control-label">Enter SMS</label>
            <textarea name="text" id="text" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label for="mask" class="control-label">Mask Option</label>
            <select name="mask" id="mask" class="form-control control-sm"></select>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Campaign Name</label>
            <input type="text" name="name" id="name" class="form-control control-sm">
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-info">Preview</button>
            <input name="send" id="btnSend" type="submit" class="btn btn-success" value="Send">
        </div>
    </form>

    <script>
        function addGroup() {
            $('#groups-added').append($('#groups').find(':selected'))
        }

        function removeGroup() {
            $('#groups').append($('#groups-added').find(':selected'));
        }
    </script>

<?php include_once __DIR__ . "/../../layout/footer.php"; ?>