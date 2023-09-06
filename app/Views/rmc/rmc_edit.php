<div id="location">
<?php
echo $lang->menu->configuration . '&nbsp;&gt;&nbsp;' . $lang->menu->networks . '&nbsp;&gt;&nbsp;' . $lang->menu->rmc;
?>
	<button class="btn_help" onclick="openHelp('rmc_edit', '<?=$lang->_lang?>')">Help</button>
</div>

<style>
input.readonly {
    background-color: #ebebe4;
    border: 2px inset #eeeeee;
}
</style>

<div id="edit_section">
    <h2>:: <?=$lang->menu->rmc?></h2>
    <div class="box01">
        <form id="form_edit" method="post" action="/?c=rmc_edit&m=save">

            <h3><?=$lang->menu->basic?></h3>
            <table class="tbl_view">
            <tr>
                <th width="150"><?=$lang->common->connect_to_rmc?></th>
                <td width="1">:</td>
                <td><?=Form::checkbox('connect', '', $connect, '1')?></td>
            </tr>
            <tr>
                <th width="150"><?=$lang->common->server_url?></th>
                <td width="1">:</td>
                <td><input type="text" name="reflectorURI" <?php /*  value="<?=$properties['webtunnel.reflectorURI']?>"  */ ?> style="width: 350px;" /></td>
            </tr>
            <tr>
                <th width="150"><?=$lang->common->domain_uuid?></th>
                <td width="1">:</td>
                <td><input type="text" name="domain" <?php /* value="<?=$properties['webtunnel.domain']?>" */ ?>  style="width: 350px;" /></td>
            </tr>
            <tr>
                <th width="150"><?=$lang->common->device_id?></th>
                <td width="1">:</td>
                <td><input type="text" name="deviceId" <?php /* value="<?=$properties['webtunnel.deviceId']?>" */ ?>  style="width: 350px;" /></td>
            </tr>
            </table>

            <div class="button_set">
                <button type="button" onclick="$('#form_edit').submit();"><?=$lang->button->save?></button>&nbsp;&nbsp;
                <button type="button" onclick="this.form.reset(); $(this.form).find('input[name=connect]').change();"><?=$lang->button->reset?></button>&nbsp;&nbsp;
                <button type="button" onclick="location.href='/?c=rmc_edit';"><?=$lang->button->cancel?></button>
            </div>

        </form>
    </div>
</div>

<?PHP echo view('common/js'); ?>
<script type="text/javascript">
$(function() {
    $("#form_edit input[name='connect']").bind("change", function() {
        if( $(this).is(":checked") )
            $(this.form).find("input[type='text']").attr("readonly", false).removeClass("readonly");
        else
            $(this.form).find("input[type='text']").attr("readonly", true).addClass("readonly");
    }).change();
});
</script>
