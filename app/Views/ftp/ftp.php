<div id="location">
<?php
echo $lang->menu->networks . '&nbsp;&gt;&nbsp;' . $lang->menu->ftp;
$lang->menu->configuration . '&nbsp;&gt;&nbsp;' . $lang->menu->networks . '&nbsp;&gt;&nbsp;' . $lang->menu->ftp;
?>
	<button class="btn_help" onclick="openHelp('ftp', '<?=$lang->_lang?>')">Help</button>
</div>

<div id="edit_section" class="hide">
    <h2>:: <?=$lang->menu->ftp?></h2>
    <div class="box01">

        <form id="form_edit3" method="post" action="/?c=ftp&m=update3">
        <?=Form::hidden("No")?>
        <?=Form::hidden("mode")?>
        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->network->FTPEnable?> </th>
            <td width="1">:</td>
            <td><?=Form::checkbox('FTPEnable', '', false, '1', array('onclick' => 'enable_form_edit3()'))?></td>
        </tr>
        <tr>
            <th><?=$lang->network->FTPAddress?> </th>
            <td width="1">:</td>
            <td><?=Form::input('FTPAddress')?></td>
        </tr>
        <tr>
            <th><?=$lang->network->FTPPort?> </th>
            <td width="1">:</td>
            <td><?=Form::inputnum('FTPPort', '', array("MAXLENGTH" => "5"))?></td>
        </tr>
        <tr>
            <th><?=$lang->network->FTPID?> </th>
            <td width="1">:</td>
            <td><?=Form::input('FTPID')?></td>
        </tr>
        <tr>
            <th><?=$lang->network->FTPPassword?> </th>
            <td width="1">:</td>
            <td><?=Form::password('FTPPassword')?></td>
        </tr>
        <tr>
            <th><?=$lang->network->FTPPassive?> </th>
            <td width="1">:</td>
            <td><?=Form::checkbox('FTPPassive')?></td>
        </tr>
        <tr>
            <th><?=$lang->network->FTPDir?> </th>
            <td width="1">:</td>
            <td><?=Form::input('FTPDir', '', array("style" => "width:300px"))?> <span id="card_btn"><input size="30" type="button" onclick="$('#form_edit3 input[name=mode]').val('test'); $('#form_edit3').submit();" value='Test' /></span></td>
        </tr>
        </table>

        <div class="button_set">
            <button type="button" onclick="$('#form_edit3 input[name=mode]').val('save'); $('#form_edit3').submit();"><?=$lang->button->save?></button>&nbsp;&nbsp;
            <button type="button" onclick="open_edit(_seq)"><?=$lang->button->reset?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_edit()"><?=$lang->button->cancel?></button>
        </div>
        </form>
    </div>
</div>


<div id="view_section" class="hide">
    <h2>:: <?=$lang->menu->ftp?></h2>
    <div class="box01">

        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->network->FTPEnable?> </th>
            <td width="1">:</td>
            <td id="view_FTPEnableStr"></td>
        </tr>
        <tr>
            <th><?=$lang->network->FTPAddress?> </th>
            <td width="1">:</td>
            <td id="view_FTPAddress"></td>
        </tr>
        <tr>
            <th><?=$lang->network->FTPPort?> </th>
            <td width="1">:</td>
            <td id="view_FTPPort"></td>
        </tr>
        <tr>
            <th><?=$lang->network->FTPID?> </th>
            <td width="1">:</td>
            <td id="view_FTPID"></td>
        </tr>
        <tr>
            <th><?=$lang->network->FTPPassive?> </th>
            <td width="1">:</td>
            <td id="view_FTPPassiveStr"></td>
        </tr>
        <tr>
            <th><?=$lang->network->FTPDir?> </th>
            <td width="1">:</td>
            <td id="view_FTPDir"></td>
        </tr>
        </table>

        <div class="button_set">
<? if( $this->is_auth(97, 3) != TRUE) { ?>
            <button type="button" onclick="alert('<?=$lang->user->error_not_permission?>');"><?=$lang->button->edit?></button>
<? } else { ?>
            <button type="button" onclick="open_edit(_seq);"><?=$lang->button->edit?></button>
<? } ?>
        </div>
    </div>
</div>

<?PHP echo view('common/js'); ?>
<script type="text/javascript">
function create_list()
{
    open_view(0);
}

$(document).ready(function() {
    load_list();
});

// edit data 채우기
function set_edit()
{
    set_edit_network('form_edit1');
    set_edit_network('form_edit2');
    set_edit_network('form_edit3');
    set_edit_network('form_edit4');
    set_edit_network('form_edit5');
}

function set_edit_network(form)
{
    $.each(_current, function(name, value) {
        var element = $("#"+ form +" input[name='"+ name +"']");
        if( element.attr("type") == "checkbox" )
        {
            if( value == "1")   element.attr("checked", true);
            else                element.attr("checked", false);
        }
        else if( element.attr("type") == "radio" )
        {
            $("#"+ form +" input:radio[name='"+ name +"']").filter("input[value='"+ value +"']").attr('checked', true);
        }
        else
        {
            element.val(value);
        }

        if( $("#"+ form +" select[name='"+ name +"']").attr("multiple") == true )
            $("#"+ form +" select[name='"+ name +"']").val(eval(value));
        else
            $("#"+ form +" select[name='"+ name +"']").val(value);
    });

    enable_form_edit1();
    enable_form_edit2();
    enable_form_edit3();
    enable_form_edit4();
}

function enable_form_edit1()
{
    if( $("#form_edit1 input:radio[name='IPType']:checked").val() == '1' )
            flag = false;
    else    flag = true;

    $("#form_edit1 input[name='IPAddress']").attr("disabled", flag);
    $("#form_edit1 input[name='Subnet']").attr("disabled", flag);
    $("#form_edit1 input[name='Gateway']").attr("disabled", flag);
}

function enable_form_edit2()
{
    if( $("#form_edit2 input[name='DDNSEnable']").attr("checked") )
            flag = false;
    else    flag = true;

    $("#form_edit2 select[name='DDNSServer']").attr("disabled", flag);
    $("#form_edit2 input[name='DDNSServer']").attr("disabled", flag);
    $("#form_edit2 input[name='DDNSID']").attr("disabled", flag);
    $("#form_edit2 input[name='DDNSPassword']").attr("disabled", flag);
}

function enable_form_edit3()
{
    if( $("#form_edit3 input[name='FTPEnable']").attr("checked") )
            flag = false;
    else    flag = true;

    $("#form_edit3 input[name='FTPAddress']").attr("disabled", flag);
    $("#form_edit3 input[name='FTPPort']").attr("disabled", flag);
    $("#form_edit3 input[name='FTPID']").attr("disabled", flag);
    $("#form_edit3 input[name='FTPPassword']").attr("disabled", flag);
    $("#form_edit3 input[name='FTPPassive']").attr("disabled", flag);
    $("#form_edit3 input[name='FTPDir']").attr("disabled", flag);
}

function enable_form_edit4()
{
    if( $("#form_edit4 input[name='SMTPEnable']").attr("checked") )
            flag = false;
    else    flag = true;

    $("#form_edit4 input[name='SMTPServer']").attr("disabled", flag);
    $("#form_edit4 input[name='SMTPID']").attr("disabled", flag);
    $("#form_edit4 input[name='SMTPPassword']").attr("disabled", flag);
    $("#form_edit4 input[name='SMTPNumber']").attr("disabled", flag);
}
</script>
