<div id="location">
<?
echo $lang->menu->configuration.'&nbsp;&gt;&nbsp;'.$lang->menu->networks.'&nbsp;&gt;&nbsp;'.$lang->menu->updatesev;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>

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
    set_edit_network('form_edit3');
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
    if( $("#form_edit3 input[name='UpdateEnable']").attr("checked") )  
            flag = false;
    else    flag = true;

    $("#form_edit3 input[name='UpdateAddress']").attr("disabled", flag);
    $("#form_edit3 input[name='UpdatePort']").attr("disabled", flag);
    $("#form_edit3 input[name='UpdateID']").attr("disabled", flag);
    $("#form_edit3 input[name='UpdatePassword']").attr("disabled", flag);
    $("#form_edit3 input[name='UpdatePassive']").attr("disabled", flag);
    $("#form_edit3 input[name='UpdateDir']").attr("disabled", flag);
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


<div id="edit_section" class="hide">
    <h2>:: <?=$lang->menu->updatesev?></h2>
    <div class="box01">

        <form id="form_edit3" method="post" action="/?c=<?=$this->class?>&m=update3">
        <?=Form::hidden("No")?>
        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->network->UpdateEnable?></th>
            <td width="1">:</td>
            <td><?=Form::checkbox('UpdateEnable', '', FALSE, '1', array('onclick'=>'enable_form_edit3()'))?></td>
        </tr>
        <tr>
            <th><?=$lang->network->UpdateAddress?> </th>
            <td width="1">:</td>
            <td><?=Form::input('UpdateAddress')?></td>
        </tr>
        <tr>
            <th><?=$lang->network->UpdatePort?> </th>
            <td width="1">:</td>
            <td><?=Form::input('UpdatePort')?></td>
        </tr>
        <tr>
            <th><?=$lang->network->UpdateID?> </th>
            <td width="1">:</td>
            <td><?=Form::input('UpdateID')?></td>
        </tr>
        <tr>
            <th><?=$lang->network->UpdatePassword?> </th>
            <td width="1">:</td>
            <td><?=Form::password('UpdatePassword')?></td>
        </tr>
        <tr>
            <th><?=$lang->network->UpdatePassive?> </th>
            <td width="1">:</td>
            <td><?=Form::checkbox('UpdatePassive')?></td>
        </tr>
        <tr>
            <th><?=$lang->network->UpdateDir?> </th>
            <td width="1">:</td>
            <td><?=Form::input('UpdateDir','',array("style"=>"width:450px"))?></td>
        </tr>
        </table>

        <div class="button_set">
            <button type="button" onclick="$('#form_edit3').submit()"><?=$lang->button->save?></button>&nbsp;&nbsp;
            <button type="button" onclick="open_edit(_seq)"><?=$lang->button->reset?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_edit()"><?=$lang->button->cancel?></button>
        </div>
        </form>
    </div>
</div>


<div id="view_section" class="hide">
    <h2>:: <?=$lang->menu->updatesev?></h2>
    <div class="box01">

        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->network->UpdateEnable?> </th>
            <td width="1">:</td>
            <td id="view_UpdateEnableStr"></td>
        </tr>
        <tr>
            <th><?=$lang->network->UpdateAddress?> </th>
            <td width="1">:</td>
            <td id="view_UpdateAddress"></td>
        </tr>
        <tr>
            <th><?=$lang->network->UpdatePort?> </th>
            <td width="1">:</td>
            <td id="view_UpdatePort"></td>
        </tr>
        <tr>
            <th><?=$lang->network->UpdateID?> </th>
            <td width="1">:</td>
            <td id="view_UpdateID"></td>
        </tr>
        <tr>
            <th><?=$lang->network->UpdatePassive?> </th>
            <td width="1">:</td>
            <td id="view_UpdatePassiveStr"></td>
        </tr>
        <tr>
            <th><?=$lang->network->UpdateDir?> </th>
            <td width="1">:</td>
            <td id="view_UpdateDir"></td>
        </tr>
        </table>

        <div class="button_set">
<? if( $this->is_auth(72, 14) != TRUE) { ?>
            <button type="button" onclick="alert('<?=$this->lang->user->error_not_permission?>');"><?=$lang->button->edit?></button>
<? } else { ?>
            <button type="button" onclick="open_edit(_seq);"><?=$lang->button->edit?></button>
<? } ?>        
        </div>
    </div>
</div>
