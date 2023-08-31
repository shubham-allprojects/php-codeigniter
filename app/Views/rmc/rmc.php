<div id="location">
<?
echo $lang->menu->networks.'&nbsp;&gt;&nbsp;'.$lang->menu->rmc;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>

<div id="view_section">
    <h2>:: <?=$lang->menu->rmc?></h2>
    <div class="box01">

        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->common->connect_to_rmc?></th>
            <td width="1">:</td>
            <td><?=$connect?></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->common->server_url?></th>
            <td width="1">:</td>
            <td><?=$properties['webtunnel.reflectorURI']?></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->common->domain_uuid?></th>
            <td width="1">:</td>
            <td><?=$properties['webtunnel.domain']?></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->common->device_id?></th>
            <td width="1">:</td>
            <td><?=$properties['webtunnel.deviceId']?></td>
        </tr>
        </table>

        <div class="button_set">
<? if( $this->is_auth(100, 3) != TRUE) { ?>
            <button type="button" onclick="alert('<?=$this->lang->user->error_not_permission?>');"><?=$lang->button->edit?></button>
<? } else { ?>
            <button type="button" onclick="location.href='/?c=<?=$this->class?>&m=edit';"><?=$lang->button->edit?></button>
<? } ?>
        </div>
    </div>
</div>
